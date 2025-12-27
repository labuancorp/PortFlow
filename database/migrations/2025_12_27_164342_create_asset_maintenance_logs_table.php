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
        Schema::create('asset_maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('port_asset_id')->constrained('port_assets')->onDelete('cascade');
            $table->string('type'); // Routine, Repair, Inspection
            $table->text('description');
            $table->dateTime('performed_at');
            $table->dateTime('next_service_due')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('technician_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenance_logs');
    }
};
