<?php

namespace App\Http\Controllers;


use App\Models\CashRegister;
use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function __construct() {
        $this->middleware('auth');
        $this->middleware(['role:admin'])->only(['store','destroy']);
    }
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily');

        $start = match($period) {
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            default => now()->startOfDay(),
        };

        $end = now();

        $sales = Sale::with(['saleDetails.product', 'services'])
                ->whereBetween('created_at', [$start, $end])
                ->get();
        
        $productsIncome = $sales->flatMap->saleDetails->sum(fn($detail) => $detail->subtotal);

        $servicesIncome = $sales->flatMap->services->sum(fn($servie) => $servie->pivot->price);
    
        $totalIncome = $productsIncome + $servicesIncome;

        return view('dashboard.cash_register.index', [
            'sales' => $sales,
            'totalIncome' => $totalIncome,
            'totalProducts' => $productsIncome,
            'totalServices' => $servicesIncome,
            'period' => $period,
        ]);
    }

    public function closeCashRegister(Request $request) {
        $period = $request->get('period', 'daily');
        $start = match ($period) {
            'weekly' => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            default => now()->startOfDay(),
        };
        $end = now();

        $sales = Sale::with(['saleDetails', 'services'])
                ->whereBetween('created_at', [$start, $end])
                ->get();

        $productsIncome = $sales->flatMap->saleDetails->sum(fn($d) => $d->subtotal);
        $servicesIncome = $sales->flatMap->services->sum(fn($s) => $s->pivot->price);
        $totalIncome = $productsIncome + $servicesIncome;

        CashRegister::create([
            'user_id' => auth()->id(),
            'date' => now(),
            'total' => $totalIncome,
        ]);

        return redirect()->back()->with('success', 'Caja cerrada correctamente!');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.cash_register.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       

        return redirect()->route('dashboard.cash_register.index')->with('success', 'Caja cerrada y registrada');
    }

    /**
     * Display the specified resource.
     */
    public function show(CashRegister $cashRegister)
    {
        return view('dashboard.cash_register.show', compact('cashRegister'));
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
        return redirect()->route('dashboard.cash_register.index')->with('success', 'Caja eliminada');
    }
}
