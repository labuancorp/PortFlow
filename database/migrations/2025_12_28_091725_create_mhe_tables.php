<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Equipment Registry
        Schema::create('mhe_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Forklift 01
            $table->string('asset_code')->unique(); // e.g. FL-001
            $table->string('type'); // e.g. Forklift, Crane, Truck
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->integer('year')->nullable();
            
            $table->string('status')->default('available'); 
            // available, in-use, maintenance, breakdown
            
            $table->decimal('current_hour_meter', 10, 2)->default(0);
            $table->date('next_pm_due_date')->nullable();
            $table->decimal('next_pm_due_hours', 10, 2)->nullable();
            
            $table->string('location')->nullable(); // Zone ID or text
            $table->timestamps();
        });

        // 2. Operator Licenses
        Schema::create('mhe_operator_licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_type'); // Matches 'type' in equipment or specific code
            $table->string('license_number');
            $table->date('expiry_date');
            $table->string('status')->default('active'); // active, expired, suspended
            $table->timestamps();
        });

        // 3. Maintenance Logs
        Schema::create('mhe_maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('mhe_equipment')->onDelete('cascade');
            $table->string('type'); // PM, Corrective, Breakdown
            $table->text('description')->nullable();
            $table->decimal('parts_cost', 10, 2)->default(0);
            $table->decimal('labor_cost', 10, 2)->default(0);
            $table->timestamp('service_date')->useCurrent();
            $table->string('technician_name')->nullable();
            $table->decimal('meter_reading', 10, 2)->nullable();
            $table->timestamps();
        });

        // 4. Bookings / Jobs
        Schema::create('mhe_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('mhe_equipment');
            $table->foreignId('operator_id')->nullable()->constrained('users');
            $table->string('job_type'); // Vessel Ops, Yard, etc.
            $table->string('reference_id')->nullable(); // External ID
            
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            
            $table->string('status')->default('scheduled'); 
            // scheduled, active, completed, cancelled
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mhe_bookings');
        Schema::dropIfExists('mhe_maintenance_logs');
        Schema::dropIfExists('mhe_operator_licenses');
        Schema::dropIfExists('mhe_equipment');
    }
};
