<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vessel_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained()->cascadeOnDelete();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('speed', 5, 2)->default(0); // Knots
            $table->decimal('heading', 5, 2)->default(0); // Degrees
            $table->string('status')->nullable(); // e.g. "Underway", "Moored"
            $table->timestamp('recorded_at');
            $table->timestamps();
            
            // Index for performance
            $table->index(['vessel_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vessel_positions');
    }
};
