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
        Schema::create('commission_periods', function (Blueprint $table) {
            $table->id();

            $table->date('start_date')->unique();
            $table->date('end_date');

            $table->string('status')->default('open');

            $table->timestamp('closed_at')->nullable();

            $table->foreignId('close_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_periods');
    }
};
