<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CommissionService
{
    /**
     * Obtiene los servicios realizados durante un período.
     */
    public function getServicesForPeriod(
        Carbon $startDate,
        Carbon $endDate
    ) {

        $start = $startDate->copy()->startOfDay();
        $end = $endDate->copy()->endOfDay();

        return DB::table('sale_service as ss')
            ->join('sales as s', 's.id', '=', 'ss.sale_id')
            ->leftJoin('appointments as a', 'a.id', '=', 's.appointment_id')
            ->join('users as u', 'u.id', '=', 'ss.performed_by')
            ->join('services as srv', 'srv.id', '=', 'ss.service_id')

            ->where('s.status', 'completed')
            ->whereNotNull('ss.performed_by')
            ->whereRaw(
            'COALESCE(a.scheduled_at, s.created_at) BETWEEN ? AND ?',
            [$start, $end]
            )

            ->select([
                'ss.id as source_id',
                'ss.sale_id',
                'ss.service_id',
                'ss.price',
                'ss.performed_by as barber_id',
                'u.name as barber_name',
                'srv.name as service_name',
            ])

            ->selectRaw(
                'COALESCE(a.scheduled_at, s.created_at) as performed_at'
            )

            ->orderBy('u.name')
            ->orderBy('s.created_at')
            ->get();
    }

    /** 
     * Cierra la semana y genera liquidaciones de los barberos
     */
    public function closeWeek(Carbon $date, int $closedBy)
    {
        return DB::transaction(function () use ($date, $closedBy) {

            // 1. Determinar el lunes y sábado de la semana.
             $startDate = $date->copy()->startOfWeek(Carbon::MONDAY);
             $endDate = $startDate->copy()->addDays(5);

            // $saturdayStart = $endDate->copy()->startOfDay();
            // $saturdayEnd = $endDate->copy()->endOfDay();
            // $now = Carbon::now();

            // // Solo se permite cerrar la semana el sabado correspondient
            // if (!$now->isSaturday() || !$now->isSameDay($endDate)) {
            //     throw new RuntimeException(
            //         'El cierre de comisiones solo esta permitido el sábado de esa semana.'
            //     );
            // }

            // // Obtener el ultimo turno del sabado
            // $saturdayAppointments = Appointment::with('services')
            //     ->whereBetween('scheduled_at', [
            //         $saturdayStart,
            //         $saturdayEnd,
            // ])
            // ->where('status', '!=', 'cancelled')
            // ->get();

            // if ($saturdayAppointments->isEmpty()) {
            //     throw new RuntimeException(
            //         'No existen turnos registrados para este sábado.'
            //     );
            // }

            // $lastAppointmentEnd = $saturdayAppointments
            //     ->map(function ($appointment) {

            //         if ($appointment->services->isEmpty()) {
            //             throw new RuntimeException(
            //                 'Existe el turno del sábado sin servicios asociados'
            //             );
            //         }

            //          if ($appointment->services->contains(
            //             fn ($service) => $service->pivot->duration_min === null
            //          )) {
            //             throw new RuntimeException(
            //                 'Existe un turno el sabado sin duracion registrada en sus servicios.'
            //             );
            //          }

            //         $duration = $appointment->services->sum(
            //             fn ($service) => $service->pivot->duration_min
            //         );

            //         return $appointment->scheduled_at->copy()
            //             ->addMinutes($duration);
            //     })
            //     ->max();

            // if ($now->lt($lastAppointmentEnd)) {
            //     throw new RuntimeException(
            //         'Todavía no ha finalizado el último turno programado del sábado.'
            //     );
            // }


            $now = Carbon::now();

            // Las restricciones de horario se mantienen fuera del entorno local.
            if (!app()->isLocal()) {

                $saturdayStart = $endDate->copy()->startOfDay();
                $saturdayEnd = $endDate->copy()->endOfDay();

                // Solo se permite cerrar la semana el sábado correspondiente.
                if (!$now->isSaturday() || !$now->isSameDay($endDate)) {
                    throw new RuntimeException(
                        'El cierre de comisiones solo esta permitido el sábado de esa semana.'
                    );
                }

                // Obtener el último turno del sábado.
                $saturdayAppointments = Appointment::with('services')
                    ->whereBetween('scheduled_at', [
                        $saturdayStart,
                        $saturdayEnd,
                    ])
                    ->where('status', '!=', 'cancelled')
                    ->get();

                if ($saturdayAppointments->isEmpty()) {
                    throw new RuntimeException(
                        'No existen turnos registrados para este sábado.'
                    );
                }

                $lastAppointmentEnd = $saturdayAppointments
                    ->map(function ($appointment) {

                        if ($appointment->services->isEmpty()) {
                            throw new RuntimeException(
                                'Existe el turno del sábado sin servicios asociados.'
                            );
                        }

                        if (
                            $appointment->services->contains(
                                fn($service) => $service->pivot->duration_min === null
                            )
                        ) {
                            throw new RuntimeException(
                                'Existe un turno el sábado sin duración registrada en sus servicios.'
                            );
                        }

                        $duration = $appointment->services->sum(
                            fn($service) => $service->pivot->duration_min
                        );

                        return $appointment->scheduled_at->copy()
                            ->addMinutes($duration);
                    })
                    ->max();

                if ($now->lt($lastAppointmentEnd)) {
                    throw new RuntimeException(
                        'Todavía no ha finalizado el último turno programado del sábado.'
                    );
                }
            }



            // 2. Comprobar si el período ya fue cerrado.
            $existingPeriod = DB::table('commission_periods')
                ->whereDate('start_date', $startDate->toDateString())
                ->lockForUpdate()
                ->first();

            if ($existingPeriod) {
                throw new RuntimeException(
                    'Esta semana ya tiene un período de comisiones registrado.'
                );
            }

            // 3.Comprobar que no queden turnos pendientes de atención.
            $pendingAppointments = DB::table('appointments')
                ->where('status', 'pending')
                ->whereBetween('scheduled_at', [
                    $startDate->copy()->startOfDay(),
                    $endDate->copy()->endOfDay(),
                ])
                ->exists();

            if ($pendingAppointments) {
                throw new RuntimeException(
                    'No se puede cerrar la semana porque todavía existen turnos pendientes que finalizar.'
                );
            }

            // 4. Obtener los servicios realizados durante la semana.
            $services = $this->getServicesForPeriod(
                $startDate,
                $endDate
            );

            if ($services->isEmpty()) {
                throw new RuntimeException(
                    'No existen servicios realizados para cerrar esta semana.'
                );
            }

            // 5. Comprobar que ningún servicio haya sido liquidado anteriormente.
            $sourceIds = $services->pluck('source_id');

            $alreadyLiquidated = DB::table('commission_items')
                ->where('source_type', 'service')
                ->whereIn('source_id', $sourceIds)
                ->exists();

            if ($alreadyLiquidated) {
                throw new RuntimeException(
                    'Uno o más servicios ya fueron incluidos en otra liquidación.'
                );
            }

            // 6. Crear el período semanal.
            $periodId = DB::table('commission_periods')->insertGetId([
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'status' => 'open',
                'closed_at' => null,
                'closed_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 7. Agrupar los servicios por barbero.
            $servicesByBarber = $services->groupBy('barber_id');

            foreach ($servicesByBarber as $barberId => $barberServices) {
                $servicesTotal = $barberServices->sum('price');

                $barberPercentage = 60;
                $shopPercentage = 40;

                $barberAmount = round(
                    $servicesTotal * ($barberPercentage / 100),
                    2
                );

                $shopAmount = $servicesTotal - $barberAmount;

                // 8. Crear la liquidación del barbero.
                $commissionId = DB::table('barber_commissions')->insertGetId([
                    'commission_period_id' => $periodId,
                    'barber_id' => $barberId,
                    'services_total' => $servicesTotal,
                    'barber_percentage' => $barberPercentage,
                    'shop_percentage' => $shopPercentage,
                    'barber_amount' => $barberAmount,
                    'shop_amount' => $shopAmount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 9. Registrar cada servicio como un concepto de comisión.
                foreach ($barberServices as $service) {
                    DB::table('commission_items')->insert([
                        'barber_commission_id' => $commissionId,
                        'source_type' => 'service',
                        'source_id' => $service->source_id,
                        'sales_id' => $service->sale_id,
                        'description' => $service->service_name,
                        'quantity' => 1,
                        'unit_price' => $service->price,
                        'subtotal' => $service->price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 10. Marcar el período como cerrado.
            DB::table('commission_periods')->where('id', $periodId)->update([
                'status' => 'closed',
                'closed_at' => now(),
                'closed_by' => $closedBy,
                'updated_at' => now(),
            ]);

            // 11. Devolver el período creado.
            return DB::table('commission_periods')
                ->where('id', $periodId)
                ->first();

        });
    }

    public function isDateInClosedPeriod(Carbon $date)
    {
        return DB::table('commission_periods')
            ->where('status', 'closed')
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereDate('end_date', '>=', $date->toDateString())
            ->exists();
    }
}
