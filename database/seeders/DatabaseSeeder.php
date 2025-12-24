<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Vessel;
use App\Models\Berth;
use App\Models\PortCall;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Organizations
        $auth = Organization::create([
            'name' => 'Asian Supply Base Authority',
            'type' => 'authority',
            'code' => 'ASB',
            'billing_address' => 'Labuan, Malaysia'
        ]);

        $agent1 = Organization::create([
            'name' => 'Baram Shipyard Agents',
            'type' => 'agent',
            'code' => 'BARAM',
            'billing_address' => 'Miri, Sarawak'
        ]);

        $client1 = Organization::create([
            'name' => 'Petronas Carigali',
            'type' => 'client',
            'code' => 'PCSB',
        ]);

        $client2 = Organization::create([
            'name' => 'Shell Petroleum',
            'type' => 'client',
            'code' => 'SHELL',
        ]);

        // 2. Create Vessels
        $v1 = Vessel::create([
            'organization_id' => $client1->id,
            'name' => 'MV Nautica Gamble',
            'imo_number' => '9123456',
            'flag_country' => 'Malaysia',
            'loa_meters' => 60.5,
            'draft_meters' => 5.2,
            'vessel_type' => 'OSV'
        ]);

        $v2 = Vessel::create([
            'organization_id' => $client2->id,
            'name' => 'Barge Alpha One',
            'imo_number' => '8888888',
            'flag_country' => 'Singapore',
            'loa_meters' => 85.0,
            'draft_meters' => 4.5,
            'vessel_type' => 'Barge'
        ]);

        $v3 = Vessel::create([
            'organization_id' => $client1->id,
            'name' => 'OSV Explorer',
            'imo_number' => '7777777',
            'flag_country' => 'Malaysia',
            'loa_meters' => 55.0,
            'draft_meters' => 5.0,
            'vessel_type' => 'OSV'
        ]);

        // 3. Create Berths
        $b1 = Berth::create(['name' => 'Main Wharf 1', 'code' => 'MW1', 'max_loa' => 100, 'max_draft' => 10, 'status' => 'active']);
        $b2 = Berth::create(['name' => 'Main Wharf 2', 'code' => 'MW2', 'max_loa' => 100, 'max_draft' => 10, 'status' => 'active']);
        $b3 = Berth::create(['name' => 'Main Wharf 3', 'code' => 'MW3', 'max_loa' => 120, 'max_draft' => 12, 'status' => 'active']);
        $b4 = Berth::create(['name' => 'Alpha Jetty', 'code' => 'AJ1', 'max_loa' => 80, 'max_draft' => 8, 'status' => 'active']);

        // 4. Create Port Calls (Bookings)
        $now = Carbon::now();
        $today = Carbon::today();

        // Booking 1: Started early morning, ends tonight (Main Wharf 1)
        PortCall::create([
            'vessel_id' => $v1->id,
            'agent_id' => $agent1->id,
            'status' => 'alongside',
            'eta' => $today->copy()->addHours(6),  // 6:00 AM
            'etd' => $today->copy()->addHours(20), // 8:00 PM
            'ata' => $today->copy()->addHours(5)->addMinutes(45),
            'atb' => $today->copy()->addHours(6),
            'assigned_berth_id' => $b1->id,
            'reference_no' => 'PC-2025-001'
        ]);

        // Booking 2: Afternoon to evening (Main Wharf 3)
        PortCall::create([
            'vessel_id' => $v2->id,
            'agent_id' => $agent1->id,
            'status' => 'requested',
            'eta' => $today->copy()->addHours(14), // 2:00 PM
            'etd' => $today->copy()->addHours(22), // 10:00 PM
            'assigned_berth_id' => $b3->id,
            'reference_no' => 'PC-2025-002'
        ]);

        // Booking 3: Morning shift (Main Wharf 2)
        PortCall::create([
            'vessel_id' => $v3->id,
            'agent_id' => $agent1->id,
            'status' => 'completed',
            'eta' => $today->copy()->addHours(8),  // 8:00 AM
            'etd' => $today->copy()->addHours(12), // 12:00 PM
            'ata' => $today->copy()->addHours(7)->addMinutes(50),
            'atb' => $today->copy()->addHours(8),
            'atd' => $today->copy()->addHours(11)->addMinutes(55),
            'assigned_berth_id' => $b2->id,
            'reference_no' => 'PC-2025-000'
        ]);
        
        // User for login
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@asb.com',
            'role' => 'admin',
            'organization_id' => $auth->id,
            'password' => bcrypt('password'), // password
        ]);
    }
}
