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
        Schema::create('commission_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_commission_id')->constrained('barber_commissions')->cascadeOnDelete();
            // Identifica el origen del concepto: service actualmente, product en el futuro.
            $table->string('source_type');
            // ID del registro original, por ejemplo sale_service.id. Se conserva como referencia histórica, sin FK polimórfica.
            $table->unsignedBigInteger('source_id');

            $table->foreignId('sales_id')->constrained('sales')->restrictOnDelete();

            $table->string('description');
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index(['source_type', 'source_id']);
            $table->index('sales_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_items');
    }
};
