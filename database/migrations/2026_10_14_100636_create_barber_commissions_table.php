<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barber_commissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('commission_period_id')->constrained('commission_periods')->restrictOnDelete();

            $table->foreignId('barber_id')->constrained('users')->restrictOnDelete();

            $table->decimal('services_total', 12, 2)->default(0);
            $table->decimal('barber_percentage', 5, 2)->default(60);
            $table->decimal('shop_percentage', 5, 2)->default(40);

            $table->decimal('barber_amount', 12, 2)->default(0);
            $table->decimal('shop_amount', 12, 2)->default(0);
            $table->timestamps();
            // Un barbero solo puede tener una liquidación por semana
            $table->unique(
                ['commission_period_id', 'barber_id'],
                'barber_commission_period_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barber_commissions');
    }
};
