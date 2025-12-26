<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->index('name');
            $table->index('imo_number');
        });

        Schema::table('port_calls', function (Blueprint $table) {
            $table->index('status');
            $table->index('eta');
            $table->index('etd');
            $table->index(['eta', 'etd']); // Composite index for range queries
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['imo_number']);
        });

        Schema::table('port_calls', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['eta']);
            $table->dropIndex(['etd']);
            $table->dropIndex(['eta', 'etd']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });
    }
};
