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
    /**
     * Seed comprehensive demo data for ASB Management presentation
     * Covers ALL menus: Dashboard, Vessels, Berths, Port Calls, Cargo, Warehouse, HSE, Billing, Crew, IoT, Analytics
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting PortFlow Demo Data Seeding...');

        // ============================================
        // 1. ORGANIZATIONS (Agents, Clients, Authority)
        // ============================================
        $this->command->info('📋 Creating Organizations...');
        
        $auth = Organization::updateOrCreate(
            ['code' => 'ASB'],
            [
                'name' => 'Asian Supply Base Authority',
                'type' => 'authority',
                'billing_address' => 'Jalan Merdeka, Labuan FT, Malaysia'
            ]
        );

        $agent1 = Organization::updateOrCreate(
            ['code' => 'BARAM'],
            [
                'name' => 'Baram Shipyard Agents',
                'type' => 'agent',
                'billing_address' => 'Lot 123, Miri Port, Sarawak',
                'warehouse_subscribed' => true,
                'enabled_modules' => ['yard_management', 'resource_booking']
            ]
        );

        $agent2 = Organization::updateOrCreate(
            ['code' => 'OCEANIC'],
            [
                'name' => 'Oceanic Maritime Services',
                'type' => 'agent',
                'billing_address' => 'Marina Bay, Singapore',
                'enabled_modules' => ['resource_booking']
            ]
        );

        $client1 = Organization::updateOrCreate(
            ['code' => 'PCSB'],
            [
                'name' => 'Petronas Carigali Sdn Bhd',
                'type' => 'client',
                'billing_address' => 'KLCC, Kuala Lumpur'
            ]
        );

        $client2 = Organization::updateOrCreate(
            ['code' => 'SHELL-MY'],
            [
                'name' => 'Shell Malaysia Exploration',
                'type' => 'client',
                'billing_address' => 'Menara Shell, Kuala Lumpur'
            ]
        );

        $client3 = Organization::updateOrCreate(
            ['code' => 'MURPHY'],
            [
                'name' => 'Murphy Oil Corporation',
                'type' => 'client',
                'billing_address' => 'Labuan Offshore'
            ]
        );

        // ============================================
        // 2. USERS (Admin, Agents, HSE Officer)
        // ============================================
        $this->command->info('👥 Creating Users...');

        $adminUser = User::updateOrCreate(
            ['email' => 'admin@asb.com'],
            [
                'name' => 'Sarah Ahmad',
                'role' => 'admin',
                'organization_id' => $auth->id,
                'password' => bcrypt('password'),
            ]
        );

        $agentUser1 = User::updateOrCreate(
            ['email' => 'agent@baram.com'],
            [
                'name' => 'John Tan',
                'role' => 'agent',
                'organization_id' => $agent1->id,
                'password' => bcrypt('password'),
            ]
        );

        $agentUser2 = User::updateOrCreate(
            ['email' => 'agent@oceanic.com'],
            [
                'name' => 'Maria Santos',
                'role' => 'agent',
                'organization_id' => $agent2->id,
                'password' => bcrypt('password'),
            ]
        );

        $hseOfficer = User::updateOrCreate(
            ['email' => 'hse@asb.com'],
            [
                'name' => 'Ahmad Razak',
                'role' => 'hse',
                'organization_id' => $auth->id,
                'password' => bcrypt('password'),
            ]
        );

        // ============================================
        // 3. VESSELS (OSVs, Barges, Tugs)
        // ============================================
        $this->command->info('🚢 Creating Vessels...');

        $vessels = [
            ['imo' => '9123456', 'name' => 'MV Nautica Gamble', 'client' => $agent1, 'type' => 'OSV', 'loa' => 60.5, 'draft' => 5.2, 'flag' => 'Malaysia'],
            ['imo' => '9234567', 'name' => 'Barge Alpha One', 'client' => $client2, 'type' => 'Barge', 'loa' => 85.0, 'draft' => 4.5, 'flag' => 'Singapore'],
            ['imo' => '9345678', 'name' => 'OSV Explorer', 'client' => $agent1, 'type' => 'OSV', 'loa' => 55.0, 'draft' => 5.0, 'flag' => 'Malaysia'],
            ['imo' => '9456789', 'name' => 'Pacific Carrier', 'client' => $client3, 'type' => 'Supply Vessel', 'loa' => 70.0, 'draft' => 6.0, 'flag' => 'Panama'],
            ['imo' => '9567890', 'name' => 'Sea Dragon', 'client' => $agent1, 'type' => 'OSV', 'loa' => 65.0, 'draft' => 5.5, 'flag' => 'Malaysia'],
            ['imo' => '9678901', 'name' => 'Ocean Pioneer', 'client' => $client1, 'type' => 'Anchor Handling Tug', 'loa' => 75.0, 'draft' => 6.5, 'flag' => 'Singapore'],
        ];

        $vesselModels = [];
        foreach ($vessels as $v) {
            $vesselModels[$v['imo']] = Vessel::updateOrCreate(
                ['imo_number' => $v['imo']],
                [
                    'organization_id' => $v['client']->id,
                    'name' => $v['name'],
                    'flag_country' => $v['flag'],
                    'loa_meters' => $v['loa'],
                    'draft_meters' => $v['draft'],
                    'vessel_type' => $v['type']
                ]
            );
        }

        // ============================================
        // 4. BERTHS / WHARFS
        // ============================================
        $this->command->info('🏗️ Creating Berths...');

        $berths = [
            ['code' => 'MW1', 'name' => 'Main Wharf 1', 'max_loa' => 100, 'max_draft' => 10, 'lat' => 5.2630, 'lng' => 115.2430, 'color' => 'green'],
            ['code' => 'MW2', 'name' => 'Main Wharf 2', 'max_loa' => 100, 'max_draft' => 10, 'lat' => 5.2635, 'lng' => 115.2435, 'color' => 'yellow'],
            ['code' => 'MW3', 'name' => 'Main Wharf 3', 'max_loa' => 120, 'max_draft' => 12, 'lat' => 5.2640, 'lng' => 115.2440, 'color' => 'blue'],
            ['code' => 'AJ1', 'name' => 'Alpha Jetty', 'max_loa' => 80, 'max_draft' => 8, 'lat' => 5.2610, 'lng' => 115.2410, 'color' => 'green'],
            ['code' => 'BJ1', 'name' => 'Bravo Jetty', 'max_loa' => 90, 'max_draft' => 9, 'lat' => 5.2615, 'lng' => 115.2415, 'color' => 'orange'],
        ];

        $berthModels = [];
        foreach ($berths as $b) {
            $berthModels[$b['code']] = Berth::updateOrCreate(
                ['code' => $b['code']],
                [
                    'name' => $b['name'],
                    'max_loa' => $b['max_loa'],
                    'max_draft' => $b['max_draft'],
                    'status' => 'active',
                    'latitude' => $b['lat'],
                    'longitude' => $b['lng'],
                    'color' => $b['color']
                ]
            );
        }

        // ============================================
        // 5. PORT CALLS (Various statuses and dates)
        // ============================================
        $this->command->info('⚓ Creating Port Calls...');

        $today = Carbon::today();
        
        $portCalls = [
            // Currently Alongside
            [
                'ref' => 'PC-2025-001',
                'vessel' => $vesselModels['9123456'],
                'agent' => $agent1,
                'berth' => $berthModels['MW1'],
                'status' => 'alongside',
                'eta' => $today->copy()->subHours(2),
                'etd' => $today->copy()->addHours(18),
                'ata' => $today->copy()->subHours(2)->addMinutes(15),
                'atb' => $today->copy()->subHours(1)->addMinutes(30),
            ],
            [
                'ref' => 'PC-2025-002',
                'vessel' => $vesselModels['9234567'],
                'agent' => $agent2,
                'berth' => $berthModels['MW2'],
                'status' => 'alongside',
                'eta' => $today->copy()->subDays(1),
                'etd' => $today->copy()->addHours(6),
                'ata' => $today->copy()->subDays(1)->addMinutes(30),
                'atb' => $today->copy()->subDays(1)->addHours(1),
            ],
            // Scheduled/Upcoming
            [
                'ref' => 'PC-2025-003',
                'vessel' => $vesselModels['9345678'],
                'agent' => $agent1,
                'berth' => $berthModels['MW3'],
                'status' => 'approved',
                'eta' => $today->copy()->addHours(8),
                'etd' => $today->copy()->addHours(24),
                'ata' => null,
                'atb' => null,
            ],
            [
                'ref' => 'PC-2025-004',
                'vessel' => $vesselModels['9456789'],
                'agent' => $agent2,
                'berth' => $berthModels['AJ1'],
                'status' => 'approved',
                'eta' => $today->copy()->addDays(1),
                'etd' => $today->copy()->addDays(2),
                'ata' => null,
                'atb' => null,
            ],
            // Completed
            [
                'ref' => 'PC-2024-099',
                'vessel' => $vesselModels['9567890'],
                'agent' => $agent1,
                'berth' => $berthModels['MW1'],
                'status' => 'completed',
                'eta' => $today->copy()->subDays(5),
                'etd' => $today->copy()->subDays(4),
                'ata' => $today->copy()->subDays(5)->addMinutes(20),
                'atb' => $today->copy()->subDays(5)->addHours(1),
                'atd' => $today->copy()->subDays(4)->addMinutes(15),
            ],
            [
                'ref' => 'PC-2024-098',
                'vessel' => $vesselModels['9678901'],
                'agent' => $agent2,
                'berth' => $berthModels['BJ1'],
                'status' => 'completed',
                'eta' => $today->copy()->subDays(7),
                'etd' => $today->copy()->subDays(6),
                'ata' => $today->copy()->subDays(7)->addMinutes(45),
                'atb' => $today->copy()->subDays(7)->addHours(1)->addMinutes(30),
                'atd' => $today->copy()->subDays(6)->addHours(2),
            ],
        ];

        $portCallModels = [];
        foreach ($portCalls as $pc) {
            $portCallModels[$pc['ref']] = PortCall::updateOrCreate(
                ['reference_no' => $pc['ref']],
                [
                    'vessel_id' => $pc['vessel']->id,
                    'agent_id' => $pc['agent']->id,
                    'assigned_berth_id' => $pc['berth']->id,
                    'status' => $pc['status'],
                    'eta' => $pc['eta'],
                    'etd' => $pc['etd'],
                    'ata' => $pc['ata'],
                    'atb' => $pc['atb'],
                    'atd' => $pc['atd'] ?? null,
                ]
            );
        }

        // ============================================
        // 6. WAREHOUSES & ZONES
        // ============================================
        $this->command->info('📦 Creating Warehouses & Zones...');

        $warehouse1 = Warehouse::updateOrCreate(
            ['name' => 'Main Yard A'],
            [
                'type' => 'open_yard',
                'total_capacity_m3' => 5000
            ]
        );

        $warehouse2 = Warehouse::updateOrCreate(
            ['name' => 'Covered Storage B'],
            [
                'type' => 'covered',
                'total_capacity_m3' => 3000
            ]
        );

        $zones = [
            ['warehouse' => $warehouse1, 'code' => 'A1', 'name' => 'Zone A1 - General Cargo', 'capacity' => 1000, 'dg' => false],
            ['warehouse' => $warehouse1, 'code' => 'A2', 'name' => 'Zone A2 - Pipes & Tubulars', 'capacity' => 1500, 'dg' => false],
            ['warehouse' => $warehouse1, 'code' => 'A3', 'name' => 'Zone A3 - DG Storage', 'capacity' => 800, 'dg' => true],
            ['warehouse' => $warehouse2, 'code' => 'B1', 'name' => 'Zone B1 - Electronics', 'capacity' => 1000, 'dg' => false],
            ['warehouse' => $warehouse2, 'code' => 'B2', 'name' => 'Zone B2 - Chemicals (DG)', 'capacity' => 600, 'dg' => true],
        ];

        $zoneModels = [];
        foreach ($zones as $z) {
            $zoneModels[$z['code']] = WarehouseZone::updateOrCreate(
                ['code' => $z['code']],
                [
                    'warehouse_id' => $z['warehouse']->id,
                    'name' => $z['name'],
                    'capacity_limit_m3' => $z['capacity'],
                    'is_dg_allowed' => $z['dg']
                ]
            );
        }

        // ============================================
        // 7. CARGO MANIFESTS & ITEMS
        // ============================================
        $this->command->info('📋 Creating Cargo Manifests & Items...');

        $manifests = [
            [
                'ref' => 'MF-BARAM-001',
                'vessel' => $vesselModels['9123456'], // MV Nautica Gamble
                'agent' => $agent1,
                'type' => 'inbound',
                'status' => 'discharged',
                'eta' => $today->copy()->subHours(2),
                'yard_req' => true,
                'zone_pref' => 'open_yard',
                'items' => [
                    ['desc' => 'Drill Pipes (20x 6m)', 'weight' => 5000, 'volume' => 120, 'dg' => null, 'zone' => 'A2', 'status' => 'gated_in'],
                    ['desc' => 'Hydraulic Pumps (5 units)', 'weight' => 800, 'volume' => 15, 'dg' => null, 'zone' => 'B1', 'status' => 'gated_in'],
                ]
            ],
            [
                'ref' => 'MF-BARAM-REQ',
                'vessel' => $vesselModels['9345678'], // OSV Explorer
                'agent' => $agent1,
                'type' => 'inbound',
                'status' => 'submitted',
                'eta' => $today->copy()->addDays(2),
                'yard_req' => true,
                'zone_pref' => 'cold_store',
                'items' => [
                    ['desc' => 'Sensitive Electronics (Crated)', 'weight' => 1200, 'volume' => 45, 'dg' => null, 'zone' => null, 'status' => 'requested'],
                ]
            ],
            [
                'ref' => 'MF-OCEAN-001',
                'vessel' => $vesselModels['9234567'],
                'agent' => $agent2, // Oceanic
                'type' => 'inbound',
                'status' => 'discharged',
                'eta' => $today->copy()->subDays(1),
                'yard_req' => true,
                'zone_pref' => 'dg_zone',
                'items' => [
                    ['desc' => 'Diesel Fuel Drums (Class 3 DG)', 'weight' => 2000, 'volume' => 80, 'dg' => '3', 'zone' => 'A3', 'status' => 'gated_in'],
                ]
            ],
            [
                'ref' => 'MF-BARAM-DG',
                'vessel' => $vesselModels['9567890'], // Sea Dragon
                'agent' => $agent1,
                'type' => 'inbound',
                'status' => 'discharged',
                'eta' => $today->copy()->subDays(5),
                'yard_req' => true,
                'zone_pref' => 'dg_zone',
                'items' => [
                    ['desc' => 'Explosive Bolts (Class 1)', 'weight' => 500, 'volume' => 10, 'dg' => '1.4', 'zone' => 'B2', 'status' => 'gated_in'],
                ]
            ],
            [
                'ref' => 'MF-2024-050',
                'vessel' => $vesselModels['9123456'],
                'agent' => $agent1,
                'type' => 'inbound',
                'status' => 'discharged',
                'eta' => $today->copy()->subDays(95), // OLD CARGO - triggers aging alert
                'yard_req' => false,
                'zone_pref' => null,
                'items' => [
                    ['desc' => 'Abandoned Containers (Aging)', 'weight' => 3000, 'volume' => 150, 'dg' => null, 'zone' => 'A1', 'status' => 'gated_in'],
                ]
            ],
        ];

        foreach ($manifests as $m) {
            $manifest = CargoManifest::updateOrCreate(
                ['reference_no' => $m['ref']],
                [
                    'vessel_id' => $m['vessel']->id,
                    'agent_id' => $m['agent']->id,
                    'type' => $m['type'],
                    'status' => $m['status'],
                    'eta_etd' => $m['eta'],
                    'yard_storage_requested' => $m['yard_req'] ?? false,
                    'preferred_zone_type' => $m['zone_pref'] ?? null,
                ]
            );

            foreach ($m['items'] as $item) {
                CargoItem::updateOrCreate(
                    ['tracking_number' => 'TRK-' . $m['ref'] . '-' . substr(md5($item['desc']), 0, 6)],
                    [
                        'cargo_manifest_id' => $manifest->id,
                        'description' => $item['desc'],
                        'weight_kg' => $item['weight'],
                        'volume_m3' => $item['volume'],
                        'dg_class' => $item['dg'],
                        'status' => $item['status'],
                        'current_location' => $zoneModels[$item['zone']]->name ?? 'In Transit',
                        'warehouse_zone_id' => $zoneModels[$item['zone']]->id ?? null
                    ]
                );
            }
        }

        // ============================================
        // 8. WORK PERMITS (HSE Safety)
        // ============================================
        $this->command->info('🛡️ Creating Work Permits (HSE)...');

        $permits = [
            [
                'control' => 'PTW-2025-001',
                'type' => 'hot_work',
                'location' => 'Main Wharf 1 - Deck Welding',
                'applicant' => 'John Tan (Baram)',
                'org' => $agent1,
                'approved_by' => $hseOfficer->id,
                'valid_from' => $today->copy()->addHours(2),
                'valid_to' => $today->copy()->addHours(6),
                'status' => 'approved',
                'description' => 'Welding repair on vessel mooring bollard'
            ],
            [
                'control' => 'PTW-2025-002',
                'type' => 'confined_space',
                'location' => 'Zone A3 - Tank Inspection',
                'applicant' => 'Maria Santos (Oceanic)',
                'org' => $agent2,
                'approved_by' => $hseOfficer->id,
                'valid_from' => $today->copy()->addHours(4),
                'valid_to' => $today->copy()->addHours(8),
                'status' => 'approved',
                'description' => 'Inspection of chemical storage tank'
            ],
            [
                'control' => 'PTW-2025-003',
                'type' => 'working_at_height',
                'location' => 'Main Wharf 2 - Crane Maintenance',
                'applicant' => 'Ahmad Razak (ASB)',
                'org' => $auth,
                'approved_by' => $hseOfficer->id,
                'valid_from' => $today->copy()->addDays(1),
                'valid_to' => $today->copy()->addDays(1)->addHours(4),
                'status' => 'requested',
                'description' => 'Gantry crane hydraulic system check',
                'org' => $auth
            ],
            [
                'control' => 'PTW-BARAM-005',
                'type' => 'hot_work',
                'location' => 'Lower Yard - Valve Modification',
                'applicant' => 'John Tan (Baram)',
                'org' => $agent1,
                'approved_by' => null,
                'valid_from' => $today->copy()->addHours(1),
                'valid_to' => $today->copy()->addHours(5),
                'status' => 'requested',
                'description' => 'Modification of high-pressure steam valve. Requires hot work permit due to nearby fuel lines. Gas testing scheduled for 10:00 AM.'
            ],
            [
                'control' => 'PTW-2024-099',
                'type' => 'hot_work',
                'location' => 'Bravo Jetty - Fender Replacement',
                'applicant' => 'John Tan (Baram)',
                'org' => $agent1,
                'approved_by' => $hseOfficer->id,
                'valid_from' => $today->copy()->subDays(3),
                'valid_to' => $today->copy()->subDays(3)->addHours(6),
                'status' => 'closed',
                'description' => 'Welding new fender brackets'
            ],
        ];

        foreach ($permits as $p) {
            WorkPermit::updateOrCreate(
                ['control_no' => $p['control']],
                [
                    'type' => $p['type'],
                    'location' => $p['location'],
                    'applicant_name' => $p['applicant'],
                    'organization_id' => $p['org']->id ?? null,
                    'approved_by' => $p['approved_by'],
                    'valid_from' => $p['valid_from'],
                    'valid_to' => $p['valid_to'],
                    'status' => $p['status'],
                    'description' => $p['description']
                ]
            );
        }

        // ============================================
        // 9. SERVICE REQUESTS (Water, Fuel, Waste)
        // ============================================
        $this->command->info('⚙️ Creating Service Requests...');

        $services = [
            ['port_call' => 'PC-2025-001', 'type' => 'water', 'qty' => 50, 'unit' => 'MT', 'status' => 'delivered', 'requested' => $today->copy()->subHours(1)],
            ['port_call' => 'PC-2025-001', 'type' => 'fuel', 'qty' => 30, 'unit' => 'MT', 'status' => 'pending', 'requested' => $today->copy()->subMinutes(30)],
            ['port_call' => 'PC-2025-002', 'type' => 'crane', 'qty' => 5, 'unit' => 'M3', 'status' => 'delivered', 'requested' => $today->copy()->subDays(1)->addHours(2)],
            ['port_call' => 'PC-2025-002', 'type' => 'water', 'qty' => 40, 'unit' => 'MT', 'status' => 'delivered', 'requested' => $today->copy()->subDays(1)->addHours(3)],
            ['port_call' => 'PC-2025-003', 'type' => 'fuel', 'qty' => 25, 'unit' => 'MT', 'status' => 'pending', 'requested' => $today->copy()->addHours(9)],
        ];

        foreach ($services as $s) {
            ServiceRequest::updateOrCreate(
                [
                    'port_call_id' => $portCallModels[$s['port_call']]->id,
                    'service_type' => $s['type'],
                    'requested_at' => $s['requested']
                ],
                [
                    'quantity' => $s['qty'],
                    'unit' => $s['unit'],
                    'status' => $s['status']
                ]
            );
        }

        // ============================================
        // 10. INVOICES & BILLING
        // ============================================
        $this->command->info('💳 Creating Invoices...');

        $invoices = [
            [
                'port_call' => 'PC-2024-099',
                'org' => $client2,
                'invoice_no' => 'INV-2024-099',
                'total' => 15750.00,
                'status' => 'paid',
                'issued' => $today->copy()->subDays(3),
                'due' => $today->copy()->subDays(3)->addDays(30),
                'erp_status' => 'synced',
                'items' => [
                    ['desc' => 'Berth Occupancy (24 hours)', 'qty' => 24, 'rate' => 250.00],
                    ['desc' => 'Freshwater Supply', 'qty' => 45, 'rate' => 80.00],
                    ['desc' => 'Waste Disposal', 'qty' => 6, 'rate' => 150.00],
                    ['desc' => 'Mooring Services', 'qty' => 1, 'rate' => 2000.00],
                ]
            ],
            [
                'port_call' => 'PC-2024-098',
                'org' => $client1,
                'invoice_no' => 'INV-2024-098',
                'total' => 22400.00,
                'status' => 'issued',
                'issued' => $today->copy()->subDays(5),
                'due' => $today->copy()->addDays(25),
                'erp_status' => 'pending',
                'items' => [
                    ['desc' => 'Berth Occupancy (36 hours)', 'qty' => 36, 'rate' => 250.00],
                    ['desc' => 'Bunker Fuel Supply', 'qty' => 50, 'rate' => 180.00],
                    ['desc' => 'Cargo Handling', 'qty' => 120, 'rate' => 45.00],
                    ['desc' => 'Warehouse Storage (7 days)', 'qty' => 7, 'rate' => 200.00],
                ]
            ],
        ];

        foreach ($invoices as $inv) {
            $invoice = Invoice::updateOrCreate(
                ['invoice_no' => $inv['invoice_no']],
                [
                    'port_call_id' => $portCallModels[$inv['port_call']]->id,
                    'organization_id' => $inv['org']->id,
                    'total_amount' => $inv['total'],
                    'status' => $inv['status'],
                    'issued_date' => $inv['issued'],
                    'due_date' => $inv['due'],
                    'erp_status' => $inv['erp_status'],
                    'erp_synced_at' => $inv['erp_status'] === 'synced' ? $inv['issued']->addHours(2) : null,
                ]
            );

            // Delete existing items and recreate
            $invoice->invoiceItems()->delete();
            
            foreach ($inv['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['desc'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['rate'],
                    'total_price' => $item['qty'] * $item['rate']
                ]);
            }
        }

        // ============================================
        // 11. CREW MEMBERS & TRANSFERS
        // ============================================
        $this->command->info('👨‍✈️ Creating Crew Members & Transfers...');

        $crewMembers = [
            ['name' => 'Captain James Lee', 'passport' => 'M12345678', 'nationality' => 'Malaysian', 'dob' => '1975-03-15'],
            ['name' => 'Chief Engineer Wong', 'passport' => 'M23456789', 'nationality' => 'Malaysian', 'dob' => '1980-07-22'],
            ['name' => 'AB Seaman Raju', 'passport' => 'I34567890', 'nationality' => 'Indian', 'dob' => '1988-11-05'],
            ['name' => 'Cook Maria Cruz', 'passport' => 'P45678901', 'nationality' => 'Filipino', 'dob' => '1990-02-14'],
            ['name' => 'Deck Officer Ali', 'passport' => 'M56789012', 'nationality' => 'Malaysian', 'dob' => '1985-09-30'],
        ];

        $crewModels = [];
        foreach ($crewMembers as $crew) {
            $crewModels[] = CrewMember::updateOrCreate(
                ['passport_number' => $crew['passport']],
                [
                    'name' => $crew['name'],
                    'nationality' => $crew['nationality'],
                    'date_of_birth' => $crew['dob']
                ]
            );
        }

        // Crew Transfers
        CrewTransfer::updateOrCreate(
            [
                'port_call_id' => $portCallModels['PC-2025-001']->id,
                'crew_member_id' => $crewModels[0]->id,
                'direction' => 'sign_on'
            ],
            [
                'status' => 'completed',
                'scanned_at' => $today->copy()->subHours(2)
            ]
        );

        CrewTransfer::updateOrCreate(
            [
                'port_call_id' => $portCallModels['PC-2025-001']->id,
                'crew_member_id' => $crewModels[2]->id,
                'direction' => 'sign_off'
            ],
            [
                'status' => 'completed',
                'scanned_at' => $today->copy()->subHours(1)->addMinutes(30)
            ]
        );

        // ============================================
        // 12. IOT SENSORS (Smart Port)
        // ============================================
        $this->command->info('📡 Creating IoT Sensors...');

        $sensors = [
            ['code' => 'TIDE-01', 'name' => 'Main Channel Tide Gauge', 'type' => 'tide_level', 'value' => 2.3, 'unit' => 'meters', 'warn' => 3.5, 'crit' => 4.0],
            ['code' => 'WIND-01', 'name' => 'Port Wind Speed Monitor', 'type' => 'wind_speed', 'value' => 12.5, 'unit' => 'knots', 'warn' => 25.0, 'crit' => 35.0],
            ['code' => 'FUEL-01', 'name' => 'Bunker Fuel Flow Meter', 'type' => 'flow_meter', 'value' => 15.2, 'unit' => 'MT/hr', 'warn' => 50.0, 'crit' => 60.0],
            ['code' => 'WATER-01', 'name' => 'Freshwater Flow Meter', 'type' => 'flow_meter', 'value' => 8.7, 'unit' => 'MT/hr', 'warn' => 30.0, 'crit' => 40.0],
            ['code' => 'TEMP-DG01', 'name' => 'DG Zone Temperature', 'type' => 'temperature', 'value' => 32.0, 'unit' => '°C', 'warn' => 40.0, 'crit' => 45.0],
        ];

        foreach ($sensors as $s) {
            IotSensor::updateOrCreate(
                ['code' => $s['code']],
                [
                    'name' => $s['name'],
                    'type' => $s['type'],
                    'value' => $s['value'],
                    'unit' => $s['unit'],
                    'status' => 'active',
                    'last_reading_at' => now()->subMinutes(rand(1, 15)),
                    'threshold_warning' => $s['warn'],
                    'threshold_critical' => $s['crit']
                ]
            );
        }

        // ============================================
        // 13. AUDIT LOGS
        // ============================================
        $this->command->info('📝 Creating Audit Logs...');

        $auditLogs = [
            ['user' => $adminUser, 'action' => 'created', 'model' => 'PortCall', 'model_id' => $portCallModels['PC-2025-001']->id, 'desc' => 'Created port call PC-2025-001', 'time' => $today->copy()->subHours(3)],
            ['user' => $agentUser1, 'action' => 'updated', 'model' => 'CargoManifest', 'model_id' => 1, 'desc' => 'Updated manifest status to in_yard', 'time' => $today->copy()->subHours(2)],
            ['user' => $hseOfficer, 'action' => 'approved', 'model' => 'WorkPermit', 'model_id' => 1, 'desc' => 'Approved hot work permit PTW-2025-001', 'time' => $today->copy()->subHours(1)],
            ['user' => $adminUser, 'action' => 'created', 'model' => 'Invoice', 'model_id' => 1, 'desc' => 'Generated invoice INV-2024-099', 'time' => $today->copy()->subDays(3)],
            ['user' => $agentUser2, 'action' => 'requested', 'model' => 'ServiceRequest', 'model_id' => 1, 'desc' => 'Requested freshwater service', 'time' => $today->copy()->subMinutes(45)],
        ];

        foreach ($auditLogs as $log) {
            AuditLog::updateOrCreate(
                [
                    'user_id' => $log['user']->id,
                    'module' => $log['model'],
                    'record_id' => $log['model_id'],
                    'action' => $log['action'],
                ],
                [
                    'details' => $log['desc'],
                    'created_at' => $log['time']
                ]
            );
        }

        // ============================================
        // 14. Gate Entries (Phase 1)
        // ============================================
        $this->call(GateEntrySeeder::class);

        // ============================================
        // 15. Port Assets (Rental Equipment)
        // ============================================
        $this->call(PortAssetSeeder::class);

        // ============================================
        // COMPLETION
        // ============================================
        $this->command->info('');
        $this->command->info('✅ Demo Data Seeding Complete!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   • Organizations: ' . Organization::count());
        $this->command->info('   • Users: ' . User::count());
        $this->command->info('   • Vessels: ' . Vessel::count());
        $this->command->info('   • Berths: ' . Berth::count());
        $this->command->info('   • Port Calls: ' . PortCall::count());
        $this->command->info('   • Cargo Manifests: ' . CargoManifest::count());
        $this->command->info('   • Cargo Items: ' . CargoItem::count());
        $this->command->info('   • Warehouse Zones: ' . WarehouseZone::count());
        $this->command->info('   • Gate Entries: ' . \App\Models\GateEntry::count());
        $this->command->info('   • Work Permits: ' . WorkPermit::count());
        $this->command->info('   • Service Requests: ' . ServiceRequest::count());
        $this->command->info('   • Invoices: ' . Invoice::count());
        $this->command->info('   • Crew Members: ' . CrewMember::count());
        $this->command->info('   • IoT Sensors: ' . IotSensor::count());
        $this->command->info('   • Audit Logs: ' . AuditLog::count());
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('   Admin: admin@asb.com / password');
        $this->command->info('   Agent 1: agent@baram.com / password');
        $this->command->info('   Agent 2: agent@oceanic.com / password');
        $this->command->info('   HSE Officer: hse@asb.com / password');
        $this->command->info('');
        $this->command->info('🎯 Ready for ASB Management Demo!');
    }
}
