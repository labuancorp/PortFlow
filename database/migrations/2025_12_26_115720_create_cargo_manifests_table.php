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
        Schema::create('cargo_manifests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('reference_no')->unique();
            $table->enum('type', ['inbound', 'outbound']);
            $table->enum('status', ['draft', 'submitted', 'approved', 'loaded', 'discharged', 'completed'])->default('draft');
            $table->dateTime('eta_etd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_manifests');
    }
};
