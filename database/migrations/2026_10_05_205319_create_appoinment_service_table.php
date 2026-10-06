<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_service', function (Blueprint $table) {
            $table->id();

            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->cascadeOnDelete();

            $table->foreignId('service_id')
                ->constrained('services');

            $table->decimal('price', 10, 2);

            $table->timestamps();

            $table->unique(['appointment_id', 'service_id']);
        });

        // Copiar los servicios actuales de los turnos existentes
        DB::table('appointments')
            ->join(
                'services',
                'services.id',
                '=',
                'appointments.service_id'
            )
            ->select(
                'appointments.id as appointment_id',
                'appointments.service_id',
                'services.price'
            )
            ->orderBy('appointments.id')
            ->get()
            ->each(function ($appointment) {
                DB::table('appointment_service')->insert([
                    'appointment_id' => $appointment->appointment_id,
                    'service_id' => $appointment->service_id,
                    'price' => $appointment->price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        // Ahora que los datos están guardados en el pivot, eliminamos el antiguo service_id.
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('service_id')
                ->nullable()
                ->after('barber_id')
                ->constrained('services');
        });

        // Restaurar el servicio original de cada turno
        DB::table('appointment_service')
            ->orderBy('id')
            ->get()
            ->each(function ($appointmentService) {
                DB::table('appointments')
                    ->where('id', $appointmentService->appointment_id)
                    ->update([
                        'service_id' => $appointmentService->service_id,
                    ]);
            });

        Schema::dropIfExists('appointment_service');
    }
};