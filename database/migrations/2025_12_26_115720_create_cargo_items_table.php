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
        Schema::create('cargo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_manifest_id')->constrained()->cascadeOnDelete();
            $table->string('tracking_number')->unique();
            $table->string('description');
            $table->decimal('weight_kg', 10, 2);
            $table->decimal('volume_m3', 10, 2)->nullable();
            $table->string('dg_class')->nullable()->comment('IMDG Code Class');
            $table->enum('status', ['pending', 'gated_in', 'at_wharf', 'loaded', 'discharged'])->default('pending');
            $table->string('current_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_items');
    }
};
