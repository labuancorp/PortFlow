<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Asset Registry
        Schema::create('ccu_containers', function (Blueprint $table) {
            $table->id();
            $table->string('container_number')->unique(); // alphanumeric
            $table->string('type'); // Dry, Reefer, Open Top, Basket, Skip
            $table->string('size'); // 10ft, 20ft, 40ft
            $table->string('owner')->nullable(); // Client
            
            $table->string('status')->default('in_yard'); 
            // in_yard, on_vessel, gate_out, maintenance
            
            $table->string('location_yard_zone')->nullable();
            $table->unsignedBigInteger('current_vessel_id')->nullable(); 
            
            $table->date('last_inspection_date')->nullable();
            $table->date('sling_cert_expiry')->nullable(); // Crucial for offshore
            
            $table->timestamp('gate_in_date')->nullable();
            
            $table->timestamps();
        });

        // 2. Movements / History
        Schema::create('ccu_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained('ccu_containers')->onDelete('cascade');
            $table->string('movement_type'); // GATE_IN, GATE_OUT, LOAD, DISCHARGE
            
            $table->string('location_from')->nullable();
            $table->string('location_to')->nullable();
            
            $table->string('vessel_name')->nullable(); // Snapshot
            $table->string('truck_plate')->nullable(); // Snapshot
            
            $table->timestamp('occurred_at')->useCurrent();
            $table->string('handled_by')->nullable();
            $table->timestamps();
        });

        // 3. Inspections
        Schema::create('ccu_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained('ccu_containers')->onDelete('cascade');
            $table->string('inspection_type'); // Inbound, Outbound, Periodic
            $table->string('condition_status'); // Good, Damaged
            $table->text('notes')->nullable();
            $table->boolean('passed')->default(true);
            $table->string('inspector_name')->nullable();
            $table->decimal('temperature_c', 5, 2)->nullable(); // For Reefers
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ccu_inspections');
        Schema::dropIfExists('ccu_movements');
        Schema::dropIfExists('ccu_containers');
    }
};
