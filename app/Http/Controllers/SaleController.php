<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin|barber')->only(['create', 'store']);
    }
    public function index()
    {

        if (auth()->user()->hasRole('admin')) {
            $sales = Sale::with('saleDetails.product', 'services', 'user', 'client')->orderBy('created_at', 'desc')->get();
        } else {
            $sales = Sale::with('saleDetails.product', 'services', 'user', 'client')
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')->get();
        }

        return view('dashboard.sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        $services = Service::all();
        $barbers = User::role('barber')->get();
        $clients = User::role('client')->get();
        return view('dashboard.sales.create', compact('products', 'services', 'barbers', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'client_id' => 'nullable|exists:users,id',
            'payment_method' => 'nullable|string|max:50',

            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,service',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'client_id' => $request->input('client_id'),
                'payment_method' => $request->input('payment_method'),
                'total' => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {

                if ($item['type'] === 'product') {

                    $product = Product::findOrFail($item['id']);

                    if ($product->stock < $item['quantity']) {
                        DB::rollBack();

                        return back()
                            ->withErrors([
                                'items' => "No tenemos suficiente stock de {$product->name}."
                            ])
                            ->withInput();
                    }

                    $subtotal = $product->price * $item['quantity'];

                    $sale->saleDetails()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotal,
                    ]);

                    $product->decrement('stock', $item['quantity']);

                    $total += $subtotal;
                }

                if ($item['type'] === 'service') {

                    $service = Service::findOrFail($item['id']);

                    $price = $service->price;

                    $sale->services()->attach($service->id, [
                        'price' => $price,
                        'performed_by' => auth()->id(),
                    ]);

                    $total += $price;
                }
            }

            $sale->update([
                'total' => $total,
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.sales.index')
                ->with('success', 'Venta registrada!');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withErrors([
                    'error' => 'Error al crear venta: ' . $e->getMessage()
                ])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load([
            'saleDetails.product',
            'services',
            'user',
            'client',
        ]);

        return view('dashboard.sales.show', compact('sale'));
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
    public function destroy(Sale $sale)
    {
        return redirect()
            ->route('dashboard.sales.index')
            ->withErrors(['delete' => 'No es posible borrar este registro.']);
    }
}
