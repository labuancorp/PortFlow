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
        // 1. Add Maintenance Fields to PortAssets
        if (Schema::hasTable('port_assets')) {
            Schema::table('port_assets', function (Blueprint $table) {
                if (!Schema::hasColumn('port_assets', 'last_maintenance_date')) {
                    $table->date('last_maintenance_date')->nullable();
                }
                if (!Schema::hasColumn('port_assets', 'next_maintenance_date')) {
                    $table->date('next_maintenance_date')->nullable();
                }
                if (!Schema::hasColumn('port_assets', 'safety_cert_expiry')) {
                    $table->date('safety_cert_expiry')->nullable();
                }
            });
        }

        // 2. Enhance AssetMaintenanceLogs table (It already exists)
        if (Schema::hasTable('asset_maintenance_logs')) {
             Schema::table('asset_maintenance_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('asset_maintenance_logs', 'status')) {
                    $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('completed');
                }
                if (!Schema::hasColumn('asset_maintenance_logs', 'attachments')) {
                    $table->json('attachments')->nullable();
                }
                // Map existing columns if needed or just add aliases in model
            });
        }

        // 3. Add Handover Fields to AssetBookings
        if (Schema::hasTable('asset_bookings')) {
            Schema::table('asset_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('asset_bookings', 'check_out_time')) {
                    $table->timestamp('check_out_time')->nullable();
                }
                if (!Schema::hasColumn('asset_bookings', 'check_in_time')) {
                    $table->timestamp('check_in_time')->nullable();
                }
                if (!Schema::hasColumn('asset_bookings', 'check_out_notes')) {
                    $table->text('check_out_notes')->nullable();
                }
                if (!Schema::hasColumn('asset_bookings', 'check_in_notes')) {
                    $table->text('check_in_notes')->nullable();
                }
                if (!Schema::hasColumn('asset_bookings', 'check_out_media')) {
                    $table->json('check_out_media')->nullable();
                }
                if (!Schema::hasColumn('asset_bookings', 'check_in_media')) {
                    $table->json('check_in_media')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_bookings', function (Blueprint $table) {
            $table->dropColumn(['check_out_time', 'check_in_time', 'check_out_notes', 'check_in_notes', 'check_out_media', 'check_in_media']);
        });

        Schema::table('asset_maintenance_logs', function (Blueprint $table) {
            $table->dropColumn(['status', 'attachments']);
        });

        Schema::table('port_assets', function (Blueprint $table) {
            $table->dropColumn(['last_maintenance_date', 'next_maintenance_date', 'safety_cert_expiry']);
        });
    }
};
