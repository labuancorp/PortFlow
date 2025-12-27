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
        Schema::create('gate_entries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // The encrypted ID in QR
            $table->string('driver_name');
            $table->string('driver_ic');
            $table->string('vehicle_plate');
            $table->text('cargo_description'); // Required
            $table->boolean('has_dangerous_goods')->default(false);
            $table->enum('status', ['pending', 'approved', 'checked_in', 'rejected', 'checked_out'])->default('pending');
            $table->timestamp('scanned_at')->nullable();
            $table->timestamp('gate_in_at')->nullable();
            $table->timestamp('gate_out_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gate_entries');
    }
};
