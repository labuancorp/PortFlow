<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Vessel;
use App\Models\Berth;
use App\Models\PortCall;
use App\Models\User;
use App\Models\CargoManifest;
use App\Models\CargoItem;
use App\Models\Warehouse;
use App\Models\WarehouseZone;
use App\Models\WorkPermit;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ServiceRequest;
use App\Models\CrewMember;
use App\Models\CrewTransfer;
use App\Models\IotSensor;
use App\Models\AuditLog;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting Comprehensive PortFlow Demo Data Seeding...');

        // 1. CORE DATA (Orgs & Users)
        $this->seedCoreData();

        // 2. REFERENCE DATA (DG, Maritime, Tank Farm)
        $this->command->info('📚 Seeding Reference Data...');
        $this->call(DgSeeder::class);
        $this->call(MaritimeServicesSeeder::class);
        $this->call(TankFarmSeeder::class);
        $this->call(AnchorageSeeder::class);
        $this->call(InventoryItemSeeder::class);

        // 3. PHYSICAL ASSETS (Berths, MHE, CCU)
        $this->command->info('🏗️ Seeding Asset Data...');
        $this->seedBerths();
        $this->call(MheSeeder::class);
        $this->call(CcuSeeder::class);
        $this->call(PortAssetSeeder::class);

        // 4. OPERATIONAL DATA (Port Calls, Cargo, Warehouse)
        $this->command->info('⚓ Seeding Operational Data...');
        $this->seedPortCalls();
        $this->call(WarehouseSeeder::class);
        $this->call(WaitingVesselsSeeder::class);
        $this->call(GateEntrySeeder::class);

        // 5. SAFETY & SERVICES (PTW, Services)
        $this->command->info('🛡️ Seeding Safety & Services...');
        $this->seedWorkPermits();
        $this->seedServiceRequests();

        // 6. FINANCIAL DATA (Billing)
        $this->command->info('💳 Seeding Financial Data...');
        $this->seedBilling();

        // 7. CREW & IOT
        $this->command->info('📡 Seeding Crew & Monitoring...');
        $this->seedCrew();
        $this->call(IotSeeder::class);

        // 8. LOGS
        $this->seedAuditLogs();

        $this->command->info('✅ Demo Data Seeding Complete!');
    }

    private function seedCoreData()
    {
        $this->command->info('📋 Creating Organizations & Users...');
        
        $auth = Organization::updateOrCreate(['code' => 'ASB'], [
            'name' => 'Asian Supply Base Authority',
            'type' => 'authority',
            'billing_address' => 'Jalan Merdeka, Labuan FT, Malaysia'
        ]);

        $agent1 = Organization::updateOrCreate(['code' => 'BARAM'], [
            'name' => 'Baram Shipyard Agents',
            'type' => 'agent',
            'billing_address' => 'Lot 123, Miri Port, Sarawak',
            'warehouse_subscribed' => true,
            'enabled_modules' => ['yard_management', 'resource_booking']
        ]);

        $agent2 = Organization::updateOrCreate(['code' => 'OCEANIC'], [
            'name' => 'Oceanic Maritime Services',
            'type' => 'agent',
            'billing_address' => 'Marina Bay, Singapore',
            'enabled_modules' => ['resource_booking']
        ]);

        $client1 = Organization::updateOrCreate(['code' => 'PCSB'], [
            'name' => 'Petronas Carigali Sdn Bhd',
            'type' => 'client',
            'billing_address' => 'KLCC, Kuala Lumpur'
        ]);

        $adminUser = User::updateOrCreate(['email' => 'admin@asb.com'], [
            'name' => 'Sarah Ahmad',
            'role' => 'admin',
            'organization_id' => $auth->id,
            'password' => bcrypt('password'),
        ]);

        User::updateOrCreate(['email' => 'agent@baram.com'], [
            'name' => 'John Tan',
            'role' => 'agent',
            'organization_id' => $agent1->id,
            'password' => bcrypt('password'),
        ]);

        User::updateOrCreate(['email' => 'hse@asb.com'], [
            'name' => 'Ahmad Razak',
            'role' => 'hse',
            'organization_id' => $auth->id,
            'password' => bcrypt('password'),
        ]);
        
        // Vessel Data
        $vessels = [
            ['imo' => '9123456', 'name' => 'MV Nautica Gamble', 'org' => $agent1, 'type' => 'OSV', 'loa' => 60.5, 'draft' => 5.2, 'flag' => 'Malaysia'],
            ['imo' => '9234567', 'name' => 'Barge Alpha One', 'org' => $client1, 'type' => 'Barge', 'loa' => 85.0, 'draft' => 4.5, 'flag' => 'Singapore'],
            ['imo' => '9345678', 'name' => 'OSV Explorer', 'org' => $agent1, 'type' => 'OSV', 'loa' => 55.0, 'draft' => 5.0, 'flag' => 'Malaysia'],
        ];

        foreach ($vessels as $v) {
            Vessel::updateOrCreate(['imo_number' => $v['imo']], [
                'organization_id' => $v['org']->id,
                'name' => $v['name'],
                'flag_country' => $v['flag'],
                'loa_meters' => $v['loa'],
                'draft_meters' => $v['draft'],
                'vessel_type' => $v['type']
            ]);
        }
    }

    private function seedBerths()
    {
        $berths = [
            ['code' => 'MW1', 'name' => 'Main Wharf 1', 'max_loa' => 100, 'max_draft' => 10, 'lat' => 5.2630, 'lng' => 115.2430, 'color' => 'green'],
            ['code' => 'MW2', 'name' => 'Main Wharf 2', 'max_loa' => 100, 'max_draft' => 10, 'lat' => 5.2635, 'lng' => 115.2435, 'color' => 'yellow'],
            ['code' => 'MW3', 'name' => 'Main Wharf 3', 'max_loa' => 120, 'max_draft' => 12, 'lat' => 5.2640, 'lng' => 115.2440, 'color' => 'blue'],
        ];

        foreach ($berths as $b) {
            Berth::updateOrCreate(['code' => $b['code']], [
                'name' => $b['name'],
                'max_loa' => $b['max_loa'],
                'max_draft' => $b['max_draft'],
                'status' => 'active',
                'latitude' => $b['lat'],
                'longitude' => $b['lng'],
                'color' => $b['color']
            ]);
        }
    }

    private function seedPortCalls()
    {
        $berths = Berth::all();
        $agent = Organization::where('type', 'agent')->first();
        $today = Carbon::today();

        // Get specific vessels by IMO
        $nautica = Vessel::where('imo_number', '9123456')->first(); // MV Nautica Gamble
        $barge = Vessel::where('imo_number', '9234567')->first();   // Barge Alpha One
        $explorer = Vessel::where('imo_number', '9345678')->first(); // OSV Explorer

        if ($berths->count() > 0) {
            // 1. MV Nautica Gamble - Alongside with active billing (3 hours)
            if ($nautica && $berths->count() > 0) {
                PortCall::updateOrCreate(['reference_no' => 'PC-DEMO-001'], [
                    'vessel_id' => $nautica->id,
                    'agent_id' => $agent->id,
                    'assigned_berth_id' => $berths->first()->id,
                    'status' => 'alongside',
                    'eta' => $today->copy()->subHours(4),
                    'etd' => $today->copy()->addHours(20),
                    'ata' => $today->copy()->subHours(3)->addMinutes(15),
                    'atb' => $today->copy()->subHours(3)->addMinutes(45), // Line secured 3 hours ago
                ]);
            }

            // 2. Barge Alpha One - Alongside with active billing (8 hours - more charges)
            if ($barge && $berths->count() > 1) {
                PortCall::updateOrCreate(['reference_no' => 'PC-DEMO-002'], [
                    'vessel_id' => $barge->id,
                    'agent_id' => $agent->id,
                    'assigned_berth_id' => $berths->get(1)->id,
                    'status' => 'alongside',
                    'eta' => $today->copy()->subHours(10),
                    'etd' => $today->copy()->addHours(14),
                    'ata' => $today->copy()->subHours(9),
                    'atb' => $today->copy()->subHours(8), // Line secured 8 hours ago
                ]);
            }

            // 3. OSV Explorer - Requested (pending approval)
            if ($explorer) {
                PortCall::updateOrCreate(['reference_no' => 'PC-DEMO-003'], [
                    'vessel_id' => $explorer->id,
                    'agent_id' => $agent->id,
                    'assigned_berth_id' => null,
                    'status' => 'requested',
                    'eta' => $today->copy()->addHours(6),
                    'etd' => $today->copy()->addHours(30),
                ]);
            }
        }
    }

    private function seedWorkPermits()
    {
        $admin = User::where('role', 'admin')->first();
        $org = Organization::first();

        WorkPermit::updateOrCreate(['control_no' => 'PTW-DEMO-001'], [
            'type' => 'hot_work',
            'location' => 'Main Wharf 1',
            'applicant_name' => 'John Tan',
            'organization_id' => $org->id,
            'approved_by' => $admin->id,
            'valid_from' => now()->subHours(1),
            'valid_to' => now()->addHours(5),
            'status' => 'approved',
            'description' => 'Demo Hot Work Permit'
        ]);
    }

    private function seedServiceRequests()
    {
        $pc = PortCall::first();
        if ($pc) {
            ServiceRequest::updateOrCreate(['port_call_id' => $pc->id, 'service_type' => 'water'], [
                'quantity' => 50,
                'unit' => 'MT',
                'status' => 'delivered',
                'requested_at' => now()->subHours(2)
            ]);
        }
    }

    private function seedBilling()
    {
        $pc = PortCall::where('reference_no', 'PC-DEMO-003')->first(); // Use the requested vessel instead
        $org = Organization::first();
        if ($pc && $org) {
            // Create a completed/paid invoice for a different port call (not the live ones)
            Invoice::updateOrCreate(['invoice_no' => 'INV-DEMO-001'], [
                'port_call_id' => $pc->id,
                'organization_id' => $org->id,
                'total_amount' => 5000.00,
                'status' => 'draft', // Changed from 'paid' to allow live billing
                'issued_date' => now()->subDays(1),
                'due_date' => now()->addDays(30),
            ]);
        }
    }

    private function seedCrew()
    {
        $pc = PortCall::first();
        $crew = CrewMember::updateOrCreate(['passport_number' => 'PASS-DEMO-001'], [
            'name' => 'Demo Crew 1',
            'nationality' => 'Malaysian',
            'date_of_birth' => '1990-01-01'
        ]);

        if ($pc) {
            CrewTransfer::updateOrCreate(['port_call_id' => $pc->id, 'crew_member_id' => $crew->id], [
                'direction' => 'sign_on',
                'status' => 'completed',
                'scanned_at' => now()->subHour()
            ]);
        }
    }

    private function seedAuditLogs()
    {
        $user = User::first();
        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'module' => 'System',
                'action' => 'demo_seed',
                'details' => 'Comprehensive demo data seeded',
                'created_at' => now()
            ]);
        }
    }
}
