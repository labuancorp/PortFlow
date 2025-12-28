<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add MHE-specific fields to port_assets table
        Schema::table('port_assets', function (Blueprint $table) {
            $table->string('asset_code')->nullable()->after('identifier');
            $table->string('model')->nullable()->after('type');
            $table->string('manufacturer')->nullable()->after('model');
            $table->integer('year')->nullable()->after('manufacturer');
            $table->string('location')->nullable()->after('description');
            $table->decimal('next_pm_due_hours', 10, 2)->nullable()->after('next_maintenance_date');
        });

        // Migrate data from mhe_equipment to port_assets
        $mheEquipment = DB::table('mhe_equipment')->get();
        
        foreach ($mheEquipment as $mhe) {
            DB::table('port_assets')->insert([
                'name' => $mhe->name,
                'type' => strtolower($mhe->type), // forklift, crane, etc.
                'identifier' => $mhe->asset_code,
                'asset_code' => $mhe->asset_code,
                'model' => $mhe->model,
                'manufacturer' => $mhe->manufacturer,
                'year' => $mhe->year,
                'status' => $mhe->status,
                'location' => $mhe->location,
                'current_engine_hours' => $mhe->current_hour_meter,
                'next_pm_due_hours' => $mhe->next_pm_due_hours,
                'next_maintenance_date' => $mhe->next_pm_due_date,
                'billing_mode' => 'telemetry', // MHE uses hour-based billing
                'rate_per_hour' => 0, // Will need to be set manually
                'rate_per_day' => 0,
                'description' => "Migrated from MHE Fleet: {$mhe->type} - {$mhe->model}",
                'created_at' => $mhe->created_at,
                'updated_at' => $mhe->updated_at,
            ]);
        }

        // Migrate MHE maintenance logs to asset_maintenance_logs
        $mheLogs = DB::table('mhe_maintenance_logs')->get();
        
        foreach ($mheLogs as $log) {
            // Find the corresponding port_asset by asset_code
            $mheEquipment = DB::table('mhe_equipment')->where('id', $log->equipment_id)->first();
            if ($mheEquipment) {
                $portAsset = DB::table('port_assets')->where('asset_code', $mheEquipment->asset_code)->first();
                
                if ($portAsset) {
                    DB::table('asset_maintenance_logs')->insert([
                        'port_asset_id' => $portAsset->id,
                        'type' => $log->type ?? 'PM',
                        'description' => $log->description ?? $log->notes,
                        'technician_name' => $log->technician_name ?? 'Unknown',
                        'performed_at' => $log->service_date ?? $log->created_at,
                        'cost' => ($log->parts_cost ?? 0) + ($log->labor_cost ?? 0),
                        'created_at' => $log->created_at,
                        'updated_at' => $log->updated_at,
                    ]);
                }
            }
        }

        // Migrate MHE bookings to asset_bookings
        $mheBookings = DB::table('mhe_bookings')->get();
        
        foreach ($mheBookings as $booking) {
            $mheEquipment = DB::table('mhe_equipment')->where('id', $booking->equipment_id)->first();
            if ($mheEquipment) {
                $portAsset = DB::table('port_assets')->where('asset_code', $mheEquipment->asset_code)->first();
                
                if ($portAsset) {
                    DB::table('asset_bookings')->insert([
                        'port_asset_id' => $portAsset->id,
                        'organization_id' => $booking->operator_id ?? null,
                        'reference_no' => 'MHE-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                        'start_time' => $booking->start_time,
                        'end_time' => $booking->end_time,
                        'status' => $booking->status === 'completed' ? 'completed' : 'active',
                        'notes' => $booking->job_type ?? null,
                        'created_at' => $booking->created_at,
                        'updated_at' => $booking->updated_at,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove migrated MHE data (optional - be careful!)
        DB::table('port_assets')->where('billing_mode', 'telemetry')->delete();
        
        // Remove added columns
        Schema::table('port_assets', function (Blueprint $table) {
            $table->dropColumn([
                'asset_code',
                'model',
                'manufacturer',
                'year',
                'location',
                'next_pm_due_hours',
            ]);
        });
    }
};
