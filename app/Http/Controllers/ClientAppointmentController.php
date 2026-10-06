<?php

namespace App\Http\Controllers;

use App\Models\BarberUnavailability;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('landing.appointments', [
            'services' => Service::all(),
            'barbers' => User::role('barber')->get(),
        ]);
    }

    public function availableSlots(Request $request)
    {
        $now = Carbon::now();

        $request->validate([
            'date' => 'required|date',
            'service_ids' => 'nullable|array|min:1',
            'service_ids.*' => 'integer|exists:services,id',
            'service_id' => 'nullable|exists:services,id',
            'barber_id' => 'nullable|exists:users,id',
        ]);

        $date = Carbon::parse($request->date);

        // Servicios seleccionados

        if ($request->filled('service_ids')) {
            $services = Service::whereIn('id', $request->service_ids)->get();
        } elseif ($request->filled('service_id')) {
            $services = Service::where('id', $request->service_id)->get();
        } else {
            return response()->json([]);
        }

        $duration = $services->sum('duration_min');

        $startDay = $date->copy()->setTime(9, 0);
        $endDay = $date->copy()->setTime(20, 0);

        $interval = 30;
        $slots = [];

        // Turnos existentes
        $appointmentsQuery = Appointment::with('services')
            ->whereDate('scheduled_at', $date)
            ->where('status', '!=', 'cancelled');

        if ($request->filled('barber_id')) {
            $appointmentsQuery->where('barber_id', $request->barber_id);
        }

        $appointments = $appointmentsQuery->get();

        // Indisponibilidades del barbero

        $unavailabilities = collect();

        if ($request->filled('barber_id')) {
            $unavailabilities = BarberUnavailability::where(
                'barber_id',
                $request->barber_id
            )
                ->where('start_time', '<', $endDay)
                ->where('end_time', '>', $startDay)
                ->get();
        }

        // Generar horarios
        for (
            $time = $startDay->copy();
            $time->lt($endDay);
            $time->addMinutes($interval)
        ) {
            $slotStart = $time->copy();
            $slotEnd = $slotStart->copy()->addMinutes($duration);

            if ($slotEnd->gt($endDay)) {
                continue;
            }

            $available = true;

            // Comprobar turnos existentes

            foreach ($appointments as $appointment) {
                $apptStart = Carbon::parse($appointment->scheduled_at);

                $apptDuration = $appointment->services->sum(
                    'duration_min'
                );

                $apptEnd = $apptStart->copy()->addMinutes($apptDuration);

                if ($slotStart < $apptEnd && $slotEnd > $apptStart) {
                    $available = false;
                    break;
                }
            }

            // Comprobar indisponibilidades del barbero

            if ($available) {
                foreach ($unavailabilities as $unavailability) {

                    if (
                        $slotStart < $unavailability->end_time &&
                        $slotEnd > $unavailability->start_time
                    ) {
                        $available = false;
                        break;
                    }
                }
            }

            // No mostrar horarios pasados

            if ($date->isToday() && $slotStart->lte($now)) {
                $available = false;
            }

            $slots[] = [
                'time' => $slotStart->format('H:i'),
                'available' => $available,
            ];
        }

        return response()->json($slots);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['integer', 'exists:services,id'],
            'barber_id' => ['nullable', 'exists:users,id'],
            'scheduled_at' => ['required', 'date'],
        ]);

        $clientId = auth()->id();

        $services = Service::whereIn('id', $validated['service_ids'])->get();

        $totalDuration = $services->sum('duration_min');

        $start = Carbon::parse($validated['scheduled_at']);
        $end = $start->copy()->addMinutes($totalDuration);

        if ($start->lte(Carbon::now())) {
            return back()
                ->withErrors([
                    'scheduled_at' => 'No podés reservar turnos en el pasado.'
                ])
                ->withInput();
        }

       
        // Buscar barber
        if ($request->filled('barber_id')) {

            $barberId = $request->barber_id;

        } else {

            $barbers = User::role('barber')->get();

            $barberId = null;

            foreach ($barbers->shuffle() as $barber) {

                // Verificar horarios bloqueados

                $hasUnavailability = BarberUnavailability::where(
                    'barber_id',
                    $barber->id
                )
                    ->where('start_time', '<', $end)
                    ->where('end_time', '>', $start)
                    ->exists();

                if ($hasUnavailability) {
                    continue;
                }

                // Verificar otros turnos

                $appointments = Appointment::with('services')
                    ->where('barber_id', $barber->id)
                    ->where('status', '!=', 'cancelled')
                    ->whereDate('scheduled_at', $start->toDateString())
                    ->get();

                $hasConflict = false;

                foreach ($appointments as $appointment) {

                    $appointmentStart = Carbon::parse($appointment->scheduled_at);

                    $appointmentDuration = $appointment->services
                        ->sum('duration_min');

                    $appointmentEnd = $appointmentStart
                        ->copy()
                        ->addMinutes($appointmentDuration);

                    if (
                        $start < $appointmentEnd &&
                        $end > $appointmentStart
                    ) {
                        $hasConflict = true;
                        break;
                    }
                }

                if (!$hasConflict) {
                    $barberId = $barber->id;
                    break;
                }
            }

            if (!$barberId) {
                return back()
                    ->withErrors([
                        'scheduled_at' => 'No hay barberos disponibles en ese horario.'
                    ])
                    ->withInput();
            }
        }

        // Verificar disponibilidad del barbero elegido

        $hasUnavailability = BarberUnavailability::where(
            'barber_id',
            $barberId
        )
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($hasUnavailability) {
            return back()
                ->withErrors([
                    'scheduled_at' => 'El barbero no está disponible durante todo ese horario.'
                ])
                ->withInput();
        }

        //  Verificar conflictos con otros turnos

        $appointments = Appointment::with('services')
            ->where('barber_id', $barberId)
            ->where('status', '!=', 'cancelled')
            ->whereDate('scheduled_at', $start->toDateString())
            ->get();

        foreach ($appointments as $appointment) {

            $appointmentStart = Carbon::parse($appointment->scheduled_at);

            $appointmentDuration = $appointment->services
                ->sum('duration_min');

            $appointmentEnd = $appointmentStart
                ->copy()
                ->addMinutes($appointmentDuration);

            if (
                $start < $appointmentEnd &&
                $end > $appointmentStart
            ) {
                return back()
                    ->withErrors([
                        'scheduled_at' => 'Este horario ya no está disponible.'
                    ])
                    ->withInput();
            }
        }

        // Crear turno

        $barber = User::findOrFail($barberId);

        $appointment = Appointment::create([
            'client_id' => $clientId,
            'barber_id' => $barberId,
            'scheduled_at' => $start->format('Y-m-d H:i:s'),
            'status' => 'pending',
        ]);

        // Guardar servicios del turno
        $appointment->services()->attach(
            $services->mapWithKeys(function ($service) {
                return [
                    $service->id => [
                        'price' => $service->price,
                    ],
                ];
            })->toArray()
        );

        return redirect()
            ->route('landing.appointments.create')
            ->with(
                'success',
                'Tu turno fue reservado con éxito. Te atenderá ' .
                $barber->name .
                '.'
            );
    }


    public function myAppointments()
    {
        $appointments = Appointment::with(['barber', 'service'])
            ->where('client_id', auth()->id())
            ->orderBy('scheduled_at')
            ->get();

        return view('landing.my-appointments', compact('appointments'));
    }

    public function cancel(Appointment $appointment)
    {
        // Seguridad: solo el dueño
        if ($appointment->client_id !== auth()->id()) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back()->withErrors(['cancel' => 'Este turno ya no puede cancelarse.']);
        }
        if ($appointment->scheduled_at->lte(Carbon::now()->addHours(2))) {
            return back()->withErrors(['cancel' => 'Solo peodes cancelar hasta 2 horas antes del turno.']);
        }

        // No cancelar turnos pasados
        if ($appointment->scheduled_at->isPast()) {
            return back()->withErrors([
                'cancel' => 'No podés cancelar turnos pasados.'
            ]);
        }

        $appointment->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Turno cancelado correctamente.');
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
    public function destroy(string $id)
    {
        //
    }
}
