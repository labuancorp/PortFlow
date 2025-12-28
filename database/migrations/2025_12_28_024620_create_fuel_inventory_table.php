<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_inventory', function (Blueprint $table) {
            $table->id();
            $table->enum('fuel_type', ['diesel', 'mgo', 'hfo', 'lng', 'freshwater'])->unique();
            $table->decimal('current_stock', 10, 2)->default(0); // Current quantity
            $table->decimal('minimum_threshold', 10, 2)->default(1000); // Alert level
            $table->decimal('maximum_capacity', 10, 2); // Tank capacity
            $table->string('unit')->default('liters');
            $table->decimal('current_price_per_unit', 10, 2)->nullable();
            $table->string('storage_location')->nullable();
            $table->timestamp('last_restocked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_inventory');
    }
};
