<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berths', function (Blueprint $table) {
            if (!Schema::hasColumn('berths', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable();
            }
            if (!Schema::hasColumn('berths', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable();
            }
            if (!Schema::hasColumn('berths', 'boundary_coordinates')) {
                $table->json('boundary_coordinates')->nullable(); 
            }
        });
    }

    public function down(): void
    {
        Schema::table('berths', function (Blueprint $table) {
            // Be careful dropping columns that might have existed before
            // $table->dropColumn(['latitude', 'longitude', 'boundary_coordinates']);
        });
    }
};
