<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PortAsset;
use App\Models\AssetBooking;
use App\Models\Organization;
use Carbon\Carbon;

class PortAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚜 Creating Port Assets & Bookings...');

        $today = Carbon::today();

        // Get organizations for bookings
        $baram = Organization::where('code', 'BARAM')->first();
        $oceanic = Organization::where('code', 'OCEANIC')->first();

        // Create Assets
        $crane250 = PortAsset::updateOrCreate(
            ['identifier' => 'CRN-001-250T'],
            [
                'name' => 'Crawler Crane 250T',
                'type' => 'crane',
                'rate_per_hour' => 1250.00,
                'rate_per_day' => 8500.00,
                'status' => 'available',
                'description' => 'Heavy duty crawler crane for offshore tubular loading.',
                'last_maintenance_date' => $today->copy()->subMonths(3),
                'next_maintenance_date' => $today->copy()->addMonths(3),
                'safety_cert_expiry' => $today->copy()->addMonths(9),
            ]
        );

        $crane50 = PortAsset::updateOrCreate(
            ['identifier' => 'CRN-M02-50T'],
            [
                'name' => 'Mobile Crane 50T',
                'type' => 'crane',
                'rate_per_hour' => 450.00,
                'rate_per_day' => 3200.00,
                'status' => 'occupied', // Currently in use
                'description' => 'Versatile mobile crane for yard operations.',
                'last_maintenance_date' => $today->copy()->subMonths(1),
                'next_maintenance_date' => $today->copy()->addMonths(5),
                'safety_cert_expiry' => $today->copy()->addMonths(11),
            ]
        );

        $forklift = PortAsset::updateOrCreate(
            ['identifier' => 'FL-H01-15T'],
            [
                'name' => 'Heavy Forklift 15T',
                'type' => 'forklift',
                'rate_per_hour' => 250.00,
                'rate_per_day' => 1800.00,
                'status' => 'occupied', // Currently in use
                'description' => 'High capacity forklift for pipe handling.',
                'last_maintenance_date' => $today->copy()->subWeeks(2),
                'next_maintenance_date' => $today->copy()->addMonths(6),
                'safety_cert_expiry' => $today->copy()->subDays(5), // EXPIRED! Interlock Test
            ]
        );

        $warehouse = PortAsset::updateOrCreate(
            ['identifier' => 'W1-A1'],
            [
                'name' => 'Chemical Warehouse Bay A1',
                'type' => 'warehouse_bay',
                'rate_per_hour' => 50.00,
                'rate_per_day' => 400.00,
                'status' => 'available',
                'description' => 'Temperature controlled chemical storage bay.',
            ]
        );

        $reachStacker = PortAsset::updateOrCreate(
            ['identifier' => 'RS-001-45T'],
            [
                'name' => 'Reach Stacker 45T',
                'type' => 'reach_stacker',
                'rate_per_hour' => 350.00,
                'rate_per_day' => 2500.00,
                'status' => 'available',
                'description' => 'Container reach stacker for yard operations.',
                'last_maintenance_date' => $today->copy()->subMonths(5),
                'next_maintenance_date' => $today->copy()->addWeeks(1), // Due Soon
                'safety_cert_expiry' => $today->copy()->addMonths(1),
            ]
        );

        // Add Maintenance Logs
        \App\Models\AssetMaintenanceLog::firstOrCreate(
            ['port_asset_id' => $crane250->id, 'performed_at' => $today->copy()->subMonths(3)],
            [
                'type' => 'routine',
                'description' => 'Quarterly hydraulic system inspection and fluid top-up.',
                'next_service_due' => $today->copy()->addMonths(3),
                'cost' => 1200.00,
                'technician_name' => 'HeavyMach Services Sdn Bhd',
                'status' => 'completed'
            ]
        );

        \App\Models\AssetMaintenanceLog::firstOrCreate(
            ['port_asset_id' => $forklift->id, 'performed_at' => $today->copy()->subWeeks(2)],
            [
                'type' => 'repair',
                'description' => 'Replaced worn rear tires and adjusted braking system.',
                'next_service_due' => $today->copy()->addMonths(6),
                'cost' => 4500.00,
                'technician_name' => 'Port Workshop Team',
                'status' => 'completed'
            ]
        );

        // Create Active Bookings (for live billing demo)
        if ($baram && $oceanic) {
            // Active booking 1 - Mobile Crane (started 6 hours ago)
            AssetBooking::updateOrCreate(
                ['reference_no' => 'AB-' . $today->format('Ymd') . '-001'],
                [
                    'port_asset_id' => $crane50->id,
                    'organization_id' => $baram->id,
                    'start_time' => $today->copy()->subHours(6),
                    'end_time' => null, // Still ongoing
                    'status' => 'active',
                    'notes' => 'Pipe loading operations for MV Nautica Gamble. Contact: John Tan (+60-12-345-6789)',
                    'check_out_time' => $today->copy()->subHours(6), // Checked out when started
                    'check_out_notes' => 'Asset in good condition. Fuel level 100%.',
                ]
            );

            // Active booking 2 - Heavy Forklift (started 3 hours ago)
            AssetBooking::updateOrCreate(
                ['reference_no' => 'AB-' . $today->format('Ymd') . '-002'],
                [
                    'port_asset_id' => $forklift->id,
                    'organization_id' => $oceanic->id,
                    'start_time' => $today->copy()->subHours(3),
                    'end_time' => null, // Still ongoing
                    'status' => 'active',
                    'notes' => 'Cargo handling for Barge Alpha One. Contact: Maria Santos (+65-9876-5432)'
                ]
            );

            // Completed booking (for history)
            AssetBooking::updateOrCreate(
                ['reference_no' => 'AB-' . $today->copy()->subDays(2)->format('Ymd') . '-001'],
                [
                    'port_asset_id' => $crane250->id,
                    'organization_id' => $baram->id,
                    'start_time' => $today->copy()->subDays(2),
                    'end_time' => $today->copy()->subDays(2)->addHours(8),
                    'status' => 'completed',
                    'notes' => 'Heavy lift operations - completed. Contact: John Tan (+60-12-345-6789)'
                ]
            );

            // Requested booking (pending approval)
            AssetBooking::updateOrCreate(
                ['reference_no' => 'AB-' . $today->copy()->addHours(4)->format('Ymd') . '-003'],
                [
                    'port_asset_id' => $reachStacker->id,
                    'organization_id' => $baram->id,
                    'start_time' => $today->copy()->addHours(4),
                    'end_time' => $today->copy()->addHours(12),
                    'status' => 'requested',
                    'notes' => 'Container repositioning - scheduled. Contact: John Tan (+60-12-345-6789)'
                ]
            );
        }

        $this->command->info('   ✓ Created ' . PortAsset::count() . ' assets');
        $this->command->info('   ✓ Created ' . AssetBooking::count() . ' bookings (including active rentals)');
    }
}
