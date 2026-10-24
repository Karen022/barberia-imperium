<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarberCommission;
use App\Models\CommissionPayment;
use App\Models\CommissionPaymentAllocation;
use App\Models\CommissionPeriod;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CommissionPaymentController extends Controller
{

    public function index(Request $request)
    {
        $query = CommissionPayment::query()
            ->with([
                'barber',
                'registeredBy',
            ])
            ->withCount('allocations');

        // Filtro por barber
        if ($request->filled('barber_id')) {
            $query->where('barber_id', $request->barber_id);
        }

        // Filtro por mes
        if ($request->filled('month')) {
            $query->whereMonth('payment_date', $request->month);
        }

        // Filtro por año
        if ($request->filled('year')) {
            $query->whereYear('payment_date', $request->year);
        }

        // Filtro por semana
        if ($request->filled('week_start') && $request->filled('week_end')) {
            $query->whereBetween('payment_date', [$request->week_start, $request->week_end]);
        }
        $payments = $query
            ->orderByDEsc('payment_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $barbers = User::role('barber')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('dashboard.admin.commissions.payments.index',
             compact(
                'payments',
                'barbers'
            ));
    }
    public function create(Request $request)
    {
        // Obtener todos los usuarios que tienen el rol de barber
        $barbers = User::role('barber')
            ->orderBy('name')
            ->get();

        $selectedBarber = null;
        $availableCommissions = collect();

        // Consultar las liquidaciones cuando se selecciona un barbero
        if ($request->filled('barber_id')) {
            $validated = $request->validate([
                'barber_id' => ['required', 'integer'],
            ]);

            $selectedBarber = $barbers->firstWhere(
                'id',
                (int) $validated['barber_id']
            );

            abort_unless($selectedBarber, 404);

            $availableCommissions = BarberCommission::query()
                ->with('period')
                ->withSum('paymentAllocations as total_paid', 'amount')
                ->where('barber_id', $selectedBarber->id)
                ->whereHas('period', function ($query) {
                    $query->where('status', 'closed');
                })
                ->get()
                ->filter(function ($commission) {
                    $pendingBalance = (float) $commission->barber_amount
                        - (float) ($commission->total_paid ?? 0);

                    return $pendingBalance > 0;
                })
                ->sortBy('period.start_date')
                ->values();
        }

        return view('dashboard.admin.commissions.payments.create', compact(
            'barbers',
            'selectedBarber',
            'availableCommissions'
        ));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barber_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'payment_date' => ['required', 'date'],
            'payment_method' => [
                'required',
                'string',
                'in:Efectivo,Transferencia,QR,En Línea',
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
            'allocations' => ['required', 'array', 'min:1'],
            'allocations.*.commission_id' => [
                'required',
                'integer',
                'distinct',
                'exists:barber_commissions,id',
            ],
            'allocations.*.amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        $barber = User::role('barber')->findOrFail($validated['barber_id']);

        DB::transaction(function () use ($validated, $barber) {
            $allocations = collect($validated['allocations']);

            $commissionIds = $allocations
                ->pluck('commission_id')
                ->map(fn($id) => (int) $id);

            $commissions = BarberCommission::whereIn('id', $commissionIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($commissions->count() !== $commissionIds->count()) {
                throw ValidationException::withMessages([
                    'allocations' => 'Una o más liquidaciones no son válidas.',
                ]);
            }

            foreach($commissions as $targetCommission) {
                if ($targetCommission->barber_id !==  (int) $validated['barber_id']) {
                    throw ValidationException::withMessages([
                        'allocations' => 'No puedes combinar liquidaciones de distintos barberos.',
                    ]);
                }
            }

            $periodIds = $commissions  
                ->pluck('commission_period_id')
                ->unique();

            $closedPeriods = CommissionPeriod::whereIn('id', $commissions->pluck('commission_period_id'))
                ->where('status', 'closed')
                ->pluck('id');
            
            if ($closedPeriods->count() !== $periodIds->count()) {
                throw ValidationException::withMessages([
                    'allocations' => 'Todas las liquidaciones deben pertenecer a periodos cerrados.',
                ]);
            }

            $totalAmount = 0;

            foreach ($allocations as $allocation){
                $targetCommission = $commissions->get(
                    (int) $allocation['commission_id']
                );

                $alreadyPaid = CommissionPaymentAllocation::where(
                    'barber_commission_id',
                    $targetCommission->id
                )->sum('amount');

                $pendingBalance = round(
                    (float) $targetCommission->barber_amount - (float) $alreadyPaid,
                    2
                );

                $allocationAmount = round((float) $allocation['amount'], 2);

                if ($allocationAmount > $pendingBalance) {
                    throw ValidationException::withMessages([
                        'allocations' => "El importe supera el saldo pendiente de la liquidación #{$targetCommission->id}.",
                    ]);
                }

                $totalAmount += $allocationAmount;
            }
            $totalAmount = round($totalAmount, 2);

            $payment = CommissionPayment::create([
                'barber_id' => $validated['barber_id'],
                'amount' => $totalAmount,
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'registered_by' => auth()->id(),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($allocations as $allocation){
                CommissionPaymentAllocation::create([
                    'commission_payment_id' => $payment->id,
                    'barber_commission_id' => $allocation['commission_id'],
                    'amount' => $allocation['amount'],
                ]);
            }

            Expense::create([
                'user_id' => auth()->id(),
                'expense_date' => $validated['payment_date'],
                'concept' => 'Pago de comisión - ' . $barber->name,
                'category' => 'Personal',
                'amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'description' => 'Pago de comisiones registrado por el administrador.',
                'status' => 'active',
                'commission_payment_id' => $payment->id,
            ]);
        });

        return redirect()->route('dashboard.commissions.payments.index')
            ->with('success', 'El pago de comisión se registró correctamente.');
    }

    public function show(User $barber)
    {
        // Verificar que el usuario tenga rol de barber
       abort_unless($barber->hasRole('barber'), 404);

        //Obtener las liquidaciones del barbero
        $commissions = BarberCommission::query()
            ->where('barber_id', $barber->id)
            ->withSum('paymentAllocations as total_paid', 'amount')
            ->get();
        
        //Calcular los totales
        $totalGenerated = $commissions->sum('barbe_amount');

        $totalPaid = $commissions->sum(function($commission) {
            return (float) ($commission->total_paid ?? 0);
        });

        $pendingBalance = max(0, $totalGenerated - $totalPaid);

        //Obtener el historial de pagos
        $payments = CommissionPayment::query()
            ->where('barber_id', $barber->id)
            ->with([
                'registeredBy',
                'allocations.barberCommission.period',
            ])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->paginate(10);

        return view('dashboard.admin.commissions.payments.show', compact(
            'barber',
            'totalGenerated',
            'totalPaid',
            'pendingBalance',
            'payments',
        ));
    }
}
