<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('port_features', function (Blueprint $table) {
            $table->id();
            $table->string('feature_type', 50); // 'berth', 'anchorage', 'warehouse', 'crane'
            $table->string('name');
            $table->json('properties')->nullable();
            $table->json('geometry')->nullable(); // Store GeoJSON geometry
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('port_features');
    }
};
