<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iot_sensors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type'); // tide, wind, swell, visibility
            $table->decimal('value', 8, 2)->default(0);
            $table->string('unit'); // m, knots, km
            $table->string('status')->default('active'); // active, maintenance, offline
            $table->timestamp('last_reading_at')->nullable();
            $table->decimal('threshold_warning', 8, 2)->nullable();
            $table->decimal('threshold_critical', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iot_sensors');
    }
};
