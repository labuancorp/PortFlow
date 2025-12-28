<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pilotage_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('port_call_id')->constrained()->onDelete('cascade');
            $table->foreignId('pilot_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('service_type', ['inbound', 'outbound', 'shifting'])->default('inbound');
            $table->enum('status', ['requested', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('requested');
            $table->timestamp('requested_time');
            $table->timestamp('scheduled_time')->nullable();
            $table->timestamp('actual_start')->nullable();
            $table->timestamp('actual_end')->nullable();
            $table->string('boarding_point')->nullable(); // e.g., "Pilot Station Alpha"
            $table->string('weather_condition')->nullable();
            $table->decimal('calculated_fee', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pilotage_requests');
    }
};
