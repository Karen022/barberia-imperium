<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointment_service', function (Blueprint $table) {
            $table->unsignedSmallInteger('duration_min')->nullable()->after('price');

        });
        // Completar duración de los turnos existentes
        DB::table('appointment_service')
            ->orderBy('appointment_id')
            ->orderBy('service_id')
            ->chunk(100, function ($appointments) {
                foreach ($appointments as $appointment) {
                    $duration = DB::table('services')
                        ->where('id', $appointment->service_id)
                        ->value('duration_min');

                    if ($duration !== null) {
                        DB::table('appointment_service')->where('appointment_id', $appointment->appointment_id)
                            ->where('service_id', $appointment->service_id)
                            ->update([
                                'duration_min' => $duration,
                            ]);
                    }
                }
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_service', function (Blueprint $table) {
            $table->dropColumn('duration_min');
        });
    }
};
