<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // 'warning', 'overdue', 'info'
            $table->string('category'); // 'marine', 'yard', 'asset'
            $table->string('title');
            $table->text('message');
            $table->string('reference_type')->nullable(); // 'PortCall', 'CargoItem', 'AssetBooking'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['organization_id', 'is_read']);
            $table->index(['type', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
