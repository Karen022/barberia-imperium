<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct() {
        $this->middleware('auth'); // proteger todas las rutas con logueo requerido.
        $this->middleware('role:admin')->except(['index', 'show']); // solo admin puede crear/editar/eliminar
    }
    public function index()
    {
        $products = Product::orderBy('name')->get();
        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        try {
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $validated['image'] = basename($path);
            }

            Product::create($validated);

            return redirect()
                ->route('dashboard.products.index')
                ->with('success', "Producto agregado satisfactoriamente");
        } catch (\Throwable $e) {
            Log::error('Error al registrar el producto.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo registrar el producto. Inténtalo nuevamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('dashboard.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('dashboard.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        try {
            if ($request->hasFile('image')) {
            if($product->image && Storage::disk('public')->exists('products/'.$product->image)) {
                    Storage::disk('public')->delete('products/'.$product->image);
                }
                $path = $request->file('image')->store('products', 'public');
                $validated['image'] = basename($path);
            }

            $product->update($validated);

            return redirect()
                ->route('dashboard.products.index')
                ->with('success', "Producto actualizado correctamente!");
        } catch (\Throwable $e) {
            Log::error('Error al actualizar el producto.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el producto. Inténtalo nuevamente.');
        }
        
        
    }

    public function toggleFeatured(Product $product){
        try {
            $product->update([
                'is_featured' => !$product->is_featured,
            ]);

            return back()->with(
                'success',
                $product->is_featured
                    ? 'Producto destacado correctamente.'
                    : 'Producto quitado de destacados.'
            );
        } catch (\Throwable $e) {
            Log::error('Error al cambiar el estado destacado del producto.',[
                'product_id' => $product->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with(
                'error',
                'No se pudo actualizar el estado del producto. Inténtalo nuevamente.'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()
                ->route('dashboard.products.index')
                ->with('success', "Producto eliminado correctamente!");
        } catch (\Throwable $e) {
            Log::error('Error al eliminar el producto.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        
            return redirect()->back()->with('error', 'No se pudo eliminar el producto. Inténtalo nuevamente.');
        }
        
    }
}
