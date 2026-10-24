<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commission_items', function (Blueprint $table) {
            $table->unique(
                ['source_type', 'source_id'],
                'commission_item_source_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_items', function (Blueprint $table) {
            $table->dropUnique('commission_item_source_unique');
        });
    }
};
