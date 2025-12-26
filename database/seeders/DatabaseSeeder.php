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
        $auth = Organization::updateOrCreate(
            ['code' => 'ASB'],
            [
                'name' => 'Asian Supply Base Authority',
                'type' => 'authority',
                'billing_address' => 'Labuan, Malaysia'
            ]
        );

        $agent1 = Organization::updateOrCreate(
            ['code' => 'BARAM'],
            [
                'name' => 'Baram Shipyard Agents',
                'type' => 'agent',
                'billing_address' => 'Miri, Sarawak'
            ]
        );

        $client1 = Organization::updateOrCreate(
            ['code' => 'PCSB'],
            [
                'name' => 'Petronas Carigali',
                'type' => 'client',
            ]
        );

        $client2 = Organization::updateOrCreate(
            ['code' => 'SHELL-HQ'], // Using SHELL-HQ to prevent conflicts with potential Shell agents
            [
                'name' => 'Shell Petroleum',
                'type' => 'client',
            ]
        );

        // 2. Create Vessels
        $v1 = Vessel::updateOrCreate(
            ['imo_number' => '9123456'],
            [
                'organization_id' => $client1->id,
                'name' => 'MV Nautica Gamble',
                'flag_country' => 'Malaysia',
                'loa_meters' => 60.5,
                'draft_meters' => 5.2,
                'vessel_type' => 'OSV'
            ]
        );

        $v2 = Vessel::updateOrCreate(
            ['imo_number' => '8888888'],
            [
                'organization_id' => $client2->id,
                'name' => 'Barge Alpha One',
                'flag_country' => 'Singapore',
                'loa_meters' => 85.0,
                'draft_meters' => 4.5,
                'vessel_type' => 'Barge'
            ]
        );

        $v3 = Vessel::updateOrCreate(
            ['imo_number' => '7777777'],
            [
                'organization_id' => $client1->id,
                'name' => 'OSV Explorer',
                'flag_country' => 'Malaysia',
                'loa_meters' => 55.0,
                'draft_meters' => 5.0,
                'vessel_type' => 'OSV'
            ]
        );

        // 3. Create Berths
        $b1 = Berth::updateOrCreate(['code' => 'MW1'], [
            'name' => 'Main Wharf 1', 
            'max_loa' => 100, 
            'max_draft' => 10, 
            'status' => 'active',
            'latitude' => 5.2630,
            'longitude' => 115.2430,
            'color' => 'green'
        ]);
        
        $b2 = Berth::updateOrCreate(['code' => 'MW2'], [
            'name' => 'Main Wharf 2', 
            'max_loa' => 100, 
            'max_draft' => 10, 
            'status' => 'active',
            'latitude' => 5.2635,
            'longitude' => 115.2435,
            'color' => 'yellow'
        ]);
        
        $b3 = Berth::updateOrCreate(['code' => 'MW3'], [
            'name' => 'Main Wharf 3', 
            'max_loa' => 120, 
            'max_draft' => 12, 
            'status' => 'active',
            'latitude' => 5.2640,
            'longitude' => 115.2440,
            'color' => 'blue'
        ]);
        
        $b4 = Berth::updateOrCreate(['code' => 'AJ1'], [
            'name' => 'Alpha Jetty', 
            'max_loa' => 80, 
            'max_draft' => 8, 
            'status' => 'active',
            'latitude' => 5.2610,
            'longitude' => 115.2410,
            'color' => 'green'
        ]);

        // 4. Create Port Calls
        $today = Carbon::today();

        PortCall::updateOrCreate(
            ['reference_no' => 'PC-2025-001'],
            [
                'vessel_id' => $v1->id,
                'agent_id' => $agent1->id,
                'status' => 'alongside',
                'eta' => $today->copy()->addHours(6),
                'etd' => $today->copy()->addHours(20),
                'ata' => $today->copy()->addHours(5)->addMinutes(45),
                'atb' => $today->copy()->addHours(6),
                'assigned_berth_id' => $b1->id,
            ]
        );

        // User for login
        if (!\App\Models\User::where('email', 'admin@asb.com')->exists()) {
            \App\Models\User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@asb.com',
                'role' => 'admin',
                'organization_id' => $auth->id,
                'password' => bcrypt('password'),
            ]);
        }

        if (!\App\Models\User::where('email', 'agent@baram.com')->exists()) {
            \App\Models\User::factory()->create([
                'name' => 'Agent Baram',
                'email' => 'agent@baram.com',
                'role' => 'agent',
                'organization_id' => $agent1->id,
                'password' => bcrypt('password'),
            ]);
        }
    }
}
