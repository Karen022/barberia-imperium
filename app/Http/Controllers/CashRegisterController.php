<?php

namespace App\Http\Controllers;


use App\Models\CashRegister;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        // Consultas base para las ventas del período.
        $salesQuery = Sale::whereBetween('created_at', [$start, $end]);

        $expenseQuery = Expense::where('status', 'active')
            ->whereBetween('expense_date', [
                $start->toDateString(),
                $end->toDateString(),
            ]);

        // Aplicar filtro de método de pago a los detalles.
        if ($paymentMethod !== 'all') {
            $salesQuery->where('payment_method', $paymentMethod);
            $expenseQuery->where('payment_method', $paymentMethod);
        }

        // Totales generales: siempre consideran todo el período.
        $periodSales = Sale::whereBetween('created_at', [$start, $end]);

        $productsIncome = DB::table('sales_details')
            ->join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->whereBetween('sales.created_at', [$start, $end])
            ->sum('sales_details.subtotal');

        $servicesIncome = DB::table('sale_service')
            ->join('sales', 'sales.id', '=', 'sale_service.sale_id')
            ->whereBetween('sales.created_at', [$start, $end])
            ->sum('sale_service.price');

        $totalIncome = $productsIncome + $servicesIncome;

        $totalExpenses = Expense::where('status', 'active')
            ->whereBetween('expense_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->sum('amount');

        $cashBalance = $totalIncome - $totalExpenses;

        // Métodos de pago disponibles.
        $paymentMethods = [
            'Efectivo',
            'Transferencia',
            'QR',
            'En Línea',
        ];

        // Resumen por método de pago.
        $paymentSummary = [];

        foreach ($paymentMethods as $method) {
            $methodSales = (clone $periodSales)
                ->where('payment_method', $method)
                ->pluck('id');

            $methodProducts = DB::table('sales_details')
                ->whereIn('sale_id', $methodSales)
                ->sum('subtotal');

            $methodServices = DB::table('sale_service')
                ->whereIn('sale_id', $methodSales)
                ->sum('price');

            $methodIncome = $methodProducts + $methodServices;

            $methodExpenses = Expense::where('status', 'active')
                ->whereBetween('expense_date', [
                    $start->toDateString(),
                    $end->toDateString(),
                ])
                ->where('payment_method', $method)
                ->sum('amount');

            $paymentSummary[$method] = [
                'income' => $methodIncome,
                'expenses' => $methodExpenses,
                'balance' => $methodIncome - $methodExpenses,
            ];
        }

        // Ventas paginadas.
        $sales = (clone $salesQuery)
            ->with(['saleDetails.product', 'services', 'user'])
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'sales_page')
            ->withQueryString();

        // Egresos paginados.
        $expenses = (clone $expenseQuery)
            ->with('user')
            ->orderByDesc('expense_date')
            ->paginate(10, ['*'], 'expenses_page')
            ->withQueryString();

        return view('dashboard.admin.cash_register.index', [
            'sales' => $sales,
            'expenses' => $expenses,
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
