<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bunker_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('port_call_id')->constrained()->onDelete('cascade');
            $table->enum('fuel_type', ['diesel', 'mgo', 'hfo', 'lng', 'freshwater', 'provisions'])->default('diesel');
            $table->decimal('quantity_requested', 10, 2); // Liters or Tons
            $table->string('unit')->default('liters'); // liters, tons, cubic_meters
            $table->enum('status', ['requested', 'approved', 'scheduled', 'in_progress', 'completed', 'cancelled'])->default('requested');
            $table->timestamp('requested_time');
            $table->timestamp('scheduled_delivery')->nullable();
            $table->timestamp('actual_delivery_start')->nullable();
            $table->timestamp('actual_delivery_end')->nullable();
            $table->decimal('actual_quantity_delivered', 10, 2)->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->string('delivery_method')->nullable(); // Barge, Truck, Pipeline
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bunker_requests');
    }
};
