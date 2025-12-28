<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pilots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('license_number')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['available', 'on_duty', 'off_duty', 'on_leave'])->default('available');
            $table->json('certifications')->nullable(); // Types of vessels certified for
            $table->date('license_expiry');
            $table->decimal('rate_per_hour', 10, 2)->default(500.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pilots');
    }
};
