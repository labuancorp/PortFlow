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
        Schema::table('warehouse_zones', function (Blueprint $table) {
            $table->decimal('total_area_sqm', 12, 2)->nullable()->after('capacity_limit_m3');
            $table->json('map_coordinates')->nullable()->after('total_area_sqm'); // JSON for polygon
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_zones', function (Blueprint $table) {
            $table->dropColumn(['total_area_sqm', 'map_coordinates']);
        });
    }
};
