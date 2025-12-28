<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('towage_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('port_call_id')->constrained()->onDelete('cascade');
            $table->foreignId('tugboat_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('service_type', ['berthing', 'unberthing', 'shifting', 'escort'])->default('berthing');
            $table->integer('tugboats_required')->default(1);
            $table->enum('status', ['requested', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('requested');
            $table->timestamp('requested_time');
            $table->timestamp('scheduled_time')->nullable();
            $table->timestamp('actual_start')->nullable();
            $table->timestamp('actual_end')->nullable();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->string('weather_condition')->nullable();
            $table->decimal('calculated_fee', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('towage_requests');
    }
};
