<?php

namespace App\Http\Controllers;


use App\Models\CashRegister;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:admin'])->only(['store', 'destroy']);
    }
    public function index(Request $request)
    {
        $period = $request->input('period', 'daily');
        $paymentMethod = $request->input('payment_method', 'all');

        $start = match ($period) {
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'yearly' => now()->startOfYear(),
            default => now()->startOfDay(),
        };

        $end = now();

        // Obtener todas las ventas del período
        $sales = Sale::with(['saleDetails.product', 'services'])
            ->whereBetween('created_at', [$start, $end])
            ->get();

        // Calcular ingresos por productos y servicios
        $productsIncome = $sales->flatMap->saleDetails
            ->sum(fn($detail) => $detail->subtotal);

        $servicesIncome = $sales->flatMap->services
            ->sum(fn($service) => $service->pivot->price);

        $totalIncome = $productsIncome + $servicesIncome;

        // Obtener egresos activos del período
        $expenses = Expense::where('status', 'active')
            ->whereBetween('expense_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->get();

        $totalExpenses = $expenses->sum('amount');

        $cashBalance = $totalIncome - $totalExpenses;

        // Métodos de pago disponibles
        $paymentMethods = [
            'Efectivo',
            'Transferencia',
            'QR',
            'En Línea',
        ];

        // Calcular ingresos, egresos y saldo por método
        $paymentSummary = [];

        foreach ($paymentMethods as $method) {

            $methodSales = $sales->where('payment_method', $method);

            $methodProducts = $methodSales->flatMap->saleDetails
                ->sum(fn($detail) => $detail->subtotal);

            $methodServices = $methodSales->flatMap->services
                ->sum(fn($service) => $service->pivot->price);

            $methodIncome = $methodProducts + $methodServices;

            $methodExpenses = $expenses
                ->where('payment_method', $method)
                ->sum('amount');

            $paymentSummary[$method] = [
                'income' => $methodIncome,
                'expenses' => $methodExpenses,
                'balance' => $methodIncome - $methodExpenses,
            ];
        }

        // Filtrar los detalles si se seleccionó un método
        $filteredSales = $paymentMethod === 'all'
            ? $sales
            : $sales->where('payment_method', $paymentMethod);

        $filteredExpenses = $paymentMethod === 'all'
            ? $expenses
            : $expenses->where('payment_method', $paymentMethod);

        return view('dashboard.admin.cash_register.index', [
            'sales' => $filteredSales,
            'expenses' => $filteredExpenses,
            'totalIncome' => $totalIncome,
            'totalProducts' => $productsIncome,
            'totalServices' => $servicesIncome,
            'totalExpenses' => $totalExpenses,
            'cashBalance' => $cashBalance,
            'period' => $period,
            'paymentMethod' => $paymentMethod,
            'paymentSummary' => $paymentSummary,
        ]);
    }

    public function closeCashRegister(Request $request)
    {
        $period = $request->input('period', 'daily');

        $start = match ($period) {
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'yearly' => now()->startOfYear(),
            default => now()->startOfDay(),
        };

        $end = now();

        $sales = Sale::with(['saleDetails', 'services'])
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $productsIncome = $sales->flatMap->saleDetails->sum(fn($d) => $d->subtotal);
        $servicesIncome = $sales->flatMap->services->sum(fn($s) => $s->pivot->price);

        $totalIncome = $productsIncome + $servicesIncome;

        $expenses = Expense::where('status', 'active')
            ->whereBetween('expense_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->get();

        $totalExpenses = $expenses->sum('amount');

        $cashBalance = $totalIncome - $totalExpenses;

        CashRegister::create([
            'user_id' => auth()->id(),
            'date' => now(),
            'total' => $cashBalance,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Caja cerrada correctamente!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.admin.cash_register.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        return redirect()->route('dashboard.admin.cash_register.index')->with('success', 'Caja cerrada y registrada');
    }

    /**
     * Display the specified resource.
     */
    public function show(CashRegister $cashRegister)
    {
        return view('dashboard.admin.cash_register.show', compact('cashRegister'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashRegister $cashRegister)
    {
        $cashRegister->delete();
        return redirect()->route('dashboard.admin.cash_register.index')->with('success', 'Caja eliminada');
    }
}
