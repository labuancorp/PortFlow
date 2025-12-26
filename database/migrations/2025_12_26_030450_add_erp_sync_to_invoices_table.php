<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('erp_status')->default('pending'); // pending, synced, failed
            $table->timestamp('erp_synced_at')->nullable();
            $table->string('erp_reference_id')->nullable(); // SAP/Oracle ID
            $table->text('erp_logs')->nullable(); // To store response or errors
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['erp_status', 'erp_synced_at', 'erp_reference_id', 'erp_logs']);
        });
    }
};
