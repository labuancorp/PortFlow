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
        Schema::table('cargo_items', function (Blueprint $table) {
            $table->foreignId('warehouse_zone_id')->nullable()->constrained('warehouse_zones')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cargo_items', function (Blueprint $table) {
            //
        });
    }
};
