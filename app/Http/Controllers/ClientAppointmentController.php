<?php

namespace App\Http\Controllers;

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
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'nullable|exists:users,id',
        ]);

        $date = Carbon::parse($request->date);
        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration_min;

        $startDay = $date->copy()->setTime(9, 0);
        $endDay = $date->copy()->setTime(20, 0);

        $interval = 40;
        $slots = [];

        $appointmentsQuery = Appointment::whereDate('scheduled_at', $date)->where('status', '!=', 'cancelled');

        if ($request->filled('barber_id')) {
            $appointmentsQuery->where('barber_id', $request->barber_id);
        }

        $appointments = $appointmentsQuery->get();

        for ($time = $startDay->copy(); $time->lt($endDay); $time->addMinutes($interval)) {
            $slotStart = $time->copy();
            $slotEnd = $slotStart->copy()->addMinutes($duration);

            if ($slotEnd->gt($endDay)) {
                continue;
            }

            $available = true;

            foreach ($appointments as $appointment) {
                $apptStart = Carbon::parse($appointment->scheduled_at);
                $apptEnd = $apptStart->copy()->addMinutes($appointment->service->duration_min);

                if ($slotStart < $apptEnd && $slotEnd > $apptStart) {
                    $available = false;
                    break;
                }
            }

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
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'nullable|exists:users,id',
            'scheduled_at' => 'required|date',
        ]);

        $clientId = auth()->id();
        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration_min;

        $start = Carbon::parse($request->scheduled_at);
        $end = $start->copy()->addMinutes($duration);

        // No permitir turnos en el pasado  
        if ($start->lte(Carbon::now())) {
            return back()->withErrors(['scheduled_at' => 'No podés reservar turnos en el pasado.']);
        }

        // Si el usuario no elige barbero, asignar uno disponible
        if ($request->filled('barber_id')) {
            $barberId = $request->barber_id;
        } else {
            $barbers = User::role('barber')
                ->with([
                    'appointmentsAsBarber' => function ($q) use ($start) {
                        $q->where('status', '!=', 'cancelled')
                            ->whereDate('scheduled_at', $start->toDateString())
                            ->with('service');
                    }
                ])
                ->get();

            $barberId = null;

            foreach ($barbers->shuffle() as $barber) {

                $hasConflict = false;

                foreach ($barber->appointmentsAsBarber as $appt) {

                    $apptStart = Carbon::parse($appt->scheduled_at);
                    $apptEnd = $apptStart->copy()->addMinutes($appt->service->duration_min);

                    if ($start < $apptEnd && $end > $apptStart) {
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
                return back()->withErrors(['scheduled_at' => 'No hay barberos disponibles en ese horario.']);
            }
        }

        // Verificar conflictos de horario
        $conflicts = Appointment::where('barber_id', $barberId)
            ->where('status', '!=', 'cancelled')
            ->whereDate('scheduled_at', $start->toDateString())->get();

        foreach ($conflicts as $appt) {
            $apptStart = Carbon::parse($appt->scheduled_at);
            $apptEnd = $apptStart->copy()->addMinutes($appt->service->duration_min);

            if ($start < $apptEnd && $end > $apptStart) {
                return back()->withErrors(['scheduled_at' => 'Este horario ya no está disponible']);
            }
        }

        $barber = User::findOrFail($barberId);

        Appointment::create([
            'client_id' => $clientId,
            'barber_id' => $barberId,
            'service_id' => $service->id,
            'scheduled_at' => $start->format('Y-m-d H:i:s'),
            'status' => 'pending',
        ]);

        return redirect()->route('landing.appointments.create')->with('success', 'Tu turno fue reservado con éxito. Te atenderá ' . $barber->name . '.');

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
