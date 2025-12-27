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
        Schema::create('port_assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // crane, forklift, warehouse_bay, etc.
            $table->string('identifier')->unique(); // ID or License Plate
            $table->decimal('rate_per_hour', 10, 2)->nullable();
            $table->decimal('rate_per_day', 10, 2)->nullable();
            $table->enum('status', ['available', 'maintenance', 'occupied', 'standby'])->default('available');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('port_assets');
    }
};
