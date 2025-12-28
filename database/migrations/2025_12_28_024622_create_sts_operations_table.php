<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sts_operations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->foreignId('source_vessel_id')->constrained('vessels')->onDelete('cascade'); // Vessel transferring FROM
            $table->foreignId('receiving_vessel_id')->constrained('vessels')->onDelete('cascade'); // Vessel transferring TO
            $table->foreignId('agent_id')->constrained('organizations')->onDelete('cascade');
            $table->enum('cargo_type', ['crude_oil', 'lng', 'lpg', 'chemicals', 'fuel', 'other'])->default('crude_oil');
            $table->decimal('quantity', 10, 2); // Tons or cubic meters
            $table->string('unit')->default('tons');
            $table->enum('status', ['requested', 'approved', 'in_progress', 'completed', 'cancelled'])->default('requested');
            $table->timestamp('requested_time');
            $table->timestamp('scheduled_start')->nullable();
            $table->timestamp('actual_start')->nullable();
            $table->timestamp('actual_end')->nullable();
            $table->string('location')->nullable(); // Coordinates or zone
            $table->decimal('safety_zone_radius', 10, 2)->default(500); // Meters
            $table->string('weather_condition')->nullable();
            $table->decimal('wave_height', 5, 2)->nullable(); // Meters
            $table->decimal('wind_speed', 5, 2)->nullable(); // Knots
            $table->boolean('permit_approved')->default(false);
            $table->string('approved_by')->nullable();
            $table->timestamp('permit_approved_at')->nullable();
            $table->decimal('calculated_fee', 10, 2)->nullable();
            $table->text('safety_notes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sts_operations');
    }
};
