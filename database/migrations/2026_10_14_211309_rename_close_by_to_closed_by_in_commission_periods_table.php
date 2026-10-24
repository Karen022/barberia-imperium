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
        Schema::table('commission_periods', function (Blueprint $table) {
            $table->renameColumn('close_by', 'closed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_periods', function (Blueprint $table) {
            $table->renameColumn('closed_by', 'close_by');
        });
    }
};
