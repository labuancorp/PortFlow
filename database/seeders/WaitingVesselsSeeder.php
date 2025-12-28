<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vessel;
use App\Models\Organization;
use App\Models\PortCall;

class WaitingVesselsSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have an agent
        $agent = Organization::where('type', 'agent')->first() ?? Organization::create([
            'name' => 'Test Agent',
            'type' => 'agent',
            'code' => 'TAG',
            'billing_address' => 'Labuan'
        ]);

        // Create waiting vessels
        $vessels = [
            ['name' => 'MV Pacific Star', 'type' => 'container', 'loa' => 150, 'draft' => 8.5],
            ['name' => 'MT Ocean Pride', 'type' => 'tanker', 'loa' => 180, 'draft' => 10.2],
            ['name' => 'Kota Kinabalu Express', 'type' => 'passenger', 'loa' => 45, 'draft' => 3.5],
        ];

        foreach ($vessels as $data) {
            $vessel = Vessel::create([
                'name' => $data['name'],
                'vessel_type' => $data['type'], // correct
                'imo_number' => 'IMO' . rand(1000000, 9999999),
                'flag_country' => 'Malaysia', // correct
                'loa_meters' => $data['loa'], // correct
                'draft_meters' => $data['draft'], // correct
                'organization_id' => $agent->id, // correct
            ]);

            PortCall::create([
                'vessel_id' => $vessel->id,
                'agent_id' => $agent->id, // Added
                'reference_no' => 'PC-' . strtoupper(uniqid()), // Changed from port_call_id
                'eta' => now()->addDays(rand(1, 3)),
                'status' => 'anchored',
                'assigned_berth_id' => null,
                // 'cargo_type' => 'General', // Not in schema likely
                // 'purpose_of_call' => 'Cargo Ops' // Not in schema likely
            ]);
            
            $this->command->info("Created waiting vessel: {$vessel->name}");
        }
    }
}
