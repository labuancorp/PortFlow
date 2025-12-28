<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugboats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->integer('bollard_pull_tons'); // Towing capacity
            $table->enum('status', ['available', 'in_service', 'maintenance', 'out_of_service'])->default('available');
            $table->decimal('rate_per_hour', 10, 2)->default(1500.00);
            $table->date('certificate_expiry')->nullable();
            $table->string('captain_name')->nullable();
            $table->string('captain_phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugboats');
    }
};
