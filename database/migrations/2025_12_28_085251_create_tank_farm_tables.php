<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Product Management
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Liquid Mud, Brine
            $table->string('code')->unique(); // e.g., OBM, WBM, FW
            $table->string('color_code')->default('#cccccc'); // Hex code for UI
            $table->decimal('specific_gravity', 8, 4)->default(1.0000); // Density relative to water
            $table->string('hazard_class')->nullable(); // e.g., Flammable, Corrosive
            $table->boolean('requires_cleaning')->default(true);
            $table->timestamps();
        });

        Schema::create('product_compatibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_a_id')->constrained('product_types')->onDelete('cascade');
            $table->foreignId('product_b_id')->constrained('product_types')->onDelete('cascade');
            $table->boolean('is_compatible')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['product_a_id', 'product_b_id']);
        });

        // 2. Infrastructure
        Schema::create('tanks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., T-101
            $table->string('zone_id')->nullable(); // Optional link to WarehouseZone logic, but simplistic string here
            $table->decimal('capacity_volume', 10, 2); // Max cubic meters
            $table->decimal('current_volume', 10, 2)->default(0); // Current cubic meters
            $table->foreignId('current_product_id')->nullable()->constrained('product_types')->nullOnDelete();
            $table->string('status')->default('active'); // active, maintenance, cleaning, contaminated
            $table->timestamp('last_cleaned_at')->nullable();
            $table->json('gis_coordinates')->nullable(); // For map visualization (lat, lng)
            $table->timestamps();
        });

        Schema::create('tank_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tank_id')->constrained()->onDelete('cascade');
            $table->decimal('reading_volume', 10, 2);
            $table->decimal('temperature', 5, 2)->nullable();
            $table->string('source')->default('manual'); // manual, sensor
            $table->foreignId('recorded_by')->nullable()->constrained('users');
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });

        // 3. Operations
        Schema::create('weighbridge_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('truck_plate_number');
            $table->decimal('gross_weight', 10, 2)->nullable();
            $table->decimal('tare_weight', 10, 2)->nullable();
            $table->decimal('net_weight', 10, 2)->nullable();
            $table->string('status')->default('open'); // open, closed
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('bulk_movements', function (Blueprint $table) {
            $table->id();
            $table->string('movement_type'); // inbound, outbound, transfer
            $table->foreignId('product_id')->constrained('product_types');
            
            $table->foreignId('source_tank_id')->nullable()->constrained('tanks');
            $table->foreignId('destination_tank_id')->nullable()->constrained('tanks');
            
            $table->unsignedBigInteger('vessel_id')->nullable(); // Polymorphic or loose link to vessels table
            // Note: vessel_id handles ship transfers.
            
            $table->foreignId('weighbridge_ticket_id')->nullable()->constrained('weighbridge_tickets');
            
            $table->decimal('planned_volume', 10, 2);
            $table->decimal('actual_volume', 10, 2)->nullable();
            
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('status')->default('planned'); // planned, in_progress, completed, cancelled
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_movements');
        Schema::dropIfExists('weighbridge_tickets');
        Schema::dropIfExists('tank_readings');
        Schema::dropIfExists('tanks');
        Schema::dropIfExists('product_compatibility');
        Schema::dropIfExists('product_types');
    }
};
