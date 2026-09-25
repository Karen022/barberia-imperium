<?php


namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $services = Service::orderBy('name')->get();
        return view('dashboard.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'duration_min' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        try {
            if($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public' );
            $validated['image']  = basename($path);
        }
        $service = Service::create($validated);
        return redirect()
            ->route('dashboard.services.index')
            ->with('success', "Servicio de  '{$service->name}' creado exitosamente!");
        } catch (\Throwable $e) {
            Log::error('Error al registrar servicio.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo registrar el servicio. Inténtalo nuevamente');
        };

        
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('dashboard.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('dashboard.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'duration_min' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        try {
            if ($request->hasFile('image')) {
            if($service->image && Storage::disk('public')->exists('services/'.$service->image)) {
                Storage::disk('public')->delete('services/'.$service->image);
            }
            $path = $request->file('image')->store('services', 'public');
            $validated['image'] = basename($path);
            }

            $service->update($validated);

            return redirect()
                ->route('dashboard.services.index')
                ->with('success', "Servicio de '{$service->name}' actualizado exitosamente!");
        } catch (\Throwable $e) {
            Log::error('Error al actualizar el servicio.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el servicio. Inténtalo nuevamente.');
        }    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            $service->delete();

            return redirect()
                ->route('dashboard.services.index')
                ->with('success', 'Servicio eliminado');
        } catch (\Throwable $e) {
            Log::error('Error al eliminar el servicio.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'No se pudo eliminar el servicio. Inténtalo nuevamente.');
        }
        
    }
}
