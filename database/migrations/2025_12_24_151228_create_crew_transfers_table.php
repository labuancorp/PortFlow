<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crew_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('port_call_id')->constrained()->cascadeOnDelete();
            $table->foreignId('crew_member_id')->constrained()->cascadeOnDelete();
            $table->enum('direction', ['sign_on', 'sign_off', 'transit']);
            $table->enum('status', ['pending', 'security_cleared', 'immigration_cleared', 'completed', 'flagged'])->default('pending');
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crew_transfers');
    }
};
