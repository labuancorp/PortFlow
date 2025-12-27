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
        Schema::create('work_permits', function (Blueprint $table) {
            $table->id();
            $table->string('control_no')->unique();
            $table->enum('type', ['hot_work', 'working_at_height', 'confined_space', 'cold_work', 'electrical']);
            $table->string('location'); // e.g., 'Berth 1', 'Workshop'
            $table->string('applicant_name');
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->dateTime('valid_from');
            $table->dateTime('valid_to');
            $table->enum('status', ['requested', 'approved', 'active', 'closed', 'rejected'])->default('requested');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_permits');
    }
};
