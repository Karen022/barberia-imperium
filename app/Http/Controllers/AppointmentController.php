<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        //$this->middleware('auth');

    }
    public function index(Request $request)
    {
        $query = Appointment::with(['client', 'barber', 'service'])
            ->orderBy('scheduled_at');

        // Los barberos solo pueden ver sus propios turnos
        if (auth()->user()->hasRole('barber')) {
            $query->where('barber_id', auth()->id());
        }

        // El filtro por barbero queda disponible para el admin
        if ($request->filled('barber_id') && auth()->user()->hasRole('admin')) {
            $query->where('barber_id', $request->barber_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $appointments = $query->get();

        return view('dashboard.appointments.index', compact('appointments'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = User::role('client')->get();
        $barbers = User::role(['barber', 'admin'])->get();
        $services = Service::all();
        return view('dashboard.appointments.create', compact('barbers', 'clients', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'barber_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
        ]);

        try {
            //  Obtener duración del servicio
            $service = Service::findOrFail($validated['service_id']);
            $start = Carbon::createFromFormat('Y-m-d\TH:i', $validated['scheduled_at']);
            $end = (clone $start)->addMinutes($service->duration_min ?? 30);

            //  Comprobar solapamiento con otros turnos del mismo barbero
            $conflict = Appointment::where('barber_id', $validated['barber_id'])
                ->where('status', '!=', 'cancelled')
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('scheduled_at', [$start, $end->subSecond()])
                        ->orWhereRaw("ADDTIME(scheduled_at, SEC_TO_TIME((SELECT COALESCE(duration_min,0) FROM services WHERE services.id = appointments.service_id) * 60)) > ? AND scheduled_at < ?", [
                            $start->format('Y-m-d H:i:s'),
                            $end->format('Y-m-d H:i:s')
                        ]);
                })->exists();

            if ($conflict) {
                return back()
                    ->withErrors([
                        'scheduled_at' => 'El barbero ya tiene un turno en ese horario.'
                    ])->withInput();
            }

            //  Crear turno (datetime estándar)
            Appointment::create([
                'client_id' => $validated['client_id'],
                'barber_id' => $validated['barber_id'],
                'service_id' => $validated['service_id'],
                'scheduled_at' => $start->format('Y-m-d H:i:s'),
                'status' => 'pending',
            ]);
            return redirect()
                ->route('dashboard.appointments.index')
                ->with('success', 'Agendamiento creado');
        } catch (\Throwable $e) {
            Log::error('Error al registrar el turno.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo registrar el turno. Inténtalo nuevamente.');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        return view('dashboard.appointments.edit', [
            'appointment' => $appointment,
            'clients' => User::role('client')->get(),
            'barbers' => User::role('barber')->get(),
            'services' => Service::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'barber_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'scheduled_at' => 'required|date_format:Y-m-d\TH:i',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        try {
            // Obtener duración del servicio
            $service = Service::findOrFail($validated['service_id']);

            $start = Carbon::createFromFormat(
                'Y-m-d\TH:i',
                $validated['scheduled_at']
            );

            $end = (clone $start)->addMinutes($service->duration_min ?? 30);

            // Comprobar solapamiento con otros turnos del mismo barbero
            $conflict = Appointment::where('barber_id', $validated['barber_id'])
                ->where('id', '!=', $appointment->id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween(
                        'scheduled_at',
                        [$start, $end->copy()->subSecond()]
                    )
                        ->orWhereRaw(
                            "ADDTIME(
                        scheduled_at,
                        SEC_TO_TIME(
                            (
                                SELECT COALESCE(duration_min, 0)
                                FROM services
                                WHERE services.id = appointments.service_id
                            ) * 60
                        )
                    ) > ?
                    AND scheduled_at < ?",
                            [
                                $start->format('Y-m-d H:i:s'),
                                $end->format('Y-m-d H:i:s')
                            ]
                        );
                })
                ->exists();

            if ($conflict) {
                return back()
                    ->withErrors([
                        'scheduled_at' => 'El barbero ya tiene un turno agendado en ese horario.'
                    ])
                    ->withInput();
            }

            $appointment->update([
                'client_id' => $validated['client_id'],
                'barber_id' => $validated['barber_id'],
                'service_id' => $validated['service_id'],
                'scheduled_at' => $start->format('Y-m-d H:i:s'),
                'status' => $validated['status'],
            ]);

            return redirect()
                ->route('dashboard.appointments.index')
                ->with('success', 'Turno actualizado correctamente.');

        } catch (\Throwable $e) {

            Log::error('Error al actualizar el turno.', [
                'appointment_id' => $appointment->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el turno. Inténtalo nuevamente.');
        }
    }

    public function updateStatus(Appointment $appointment, string $status)
    {
        if (!in_array($status, ['completed', 'cancelled'])) {
            abort(403);
        }


        if (
            !auth()->user()->hasRole('admin') &&
            auth()->id() !== $appointment->barber_id
        ) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back();
        }

        try {
            DB::transaction(function () use ($appointment, $status) {

                if ($status == 'completed') {

                    $sale = Sale::create([
                        'user_id' => auth()->id(),
                        'client_id' => $appointment->client_id,
                        'total' => $appointment->service->price,
                        'payment_method' => null,
                    ]);

                    $sale->services()->attach($appointment->service_id, [
                        'price' => $appointment->service->price,
                        'performed_by' => $appointment->barber_id,
                    ]);
                }

                $appointment->update([
                    'status' => $status,
                ]);
            });

            return back()->with(
                'success',
                'status' === 'completed'
                ? 'Turno completado y servicio registrado correctamente.'
                : 'Turno cancelado correctamente.'
            );
        } catch (\Throwable $e) {

            Log::error('Error al actualizar el estaado del turno.', [
                'appointment_id' => $appointment->id,
                'status' => $status,
                'user_id' => auth()->id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with(
                'error',
                'No se pudo actualizar el estado del turno. Inténtalo nuevamente.'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('dashboard.appointments.index')->with('success', 'Agendamiento eliminado.');
    }
}
