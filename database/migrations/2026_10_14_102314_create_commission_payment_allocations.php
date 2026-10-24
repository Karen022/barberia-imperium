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
        Schema::create('commission_payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commission_payment_id')->constrained('commission_payments')->restrictOnDelete();
            $table->foreignId('barber_commission_id')->constrained('barber_commissions')->restrictOnDelete();
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            $table->unique(
                ['commission_payment_id', 'barber_commission_id'],
                'commission_payment_allocation_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_payment_allocations');
    }
};
