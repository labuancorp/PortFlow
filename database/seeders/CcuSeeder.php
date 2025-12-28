<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CcuContainer;

class CcuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Standard Dry Containers (Good condition)
        CcuContainer::create([
            'container_number' => 'MSDU1234567', 'type' => 'Dry', 'size' => '20ft', 'owner' => 'Maersk', 'status' => 'in_yard', 'location_yard_zone' => 'A1', 'gate_in_date' => now()->subDays(5)
        ]);
        CcuContainer::create([
            'container_number' => 'MSDU7654321', 'type' => 'Dry', 'size' => '40ft', 'owner' => 'MSC', 'status' => 'in_yard', 'location_yard_zone' => 'A2', 'gate_in_date' => now()->subDays(2)
        ]);

        // 2. Reefer (Requires temp check)
        CcuContainer::create([
            'container_number' => 'RFDU5555555', 'type' => 'Reefer', 'size' => '20ft', 'owner' => 'CoolTrans', 'status' => 'in_yard', 'location_yard_zone' => 'R1', 'gate_in_date' => now()->subDays(1)
        ]);

        // 3. CCU Basket (Offshore, Sling Cert)
        CcuContainer::create([
            'container_number' => 'CCUB9998887', 'type' => 'Basket', 'size' => '10ft', 'owner' => 'ASB Internal', 'status' => 'in_yard', 'location_yard_zone' => 'O1', 'gate_in_date' => now()->subDays(10),
            'sling_cert_expiry' => now()->addMonths(5) // Valid
        ]);
        
        // 4. CCU Skip (Expired Cert)
        CcuContainer::create([
            'container_number' => 'CCUS1112223', 'type' => 'Skip', 'size' => '10ft', 'owner' => 'ASB Internal', 'status' => 'in_yard', 'location_yard_zone' => 'O1', 'gate_in_date' => now()->subDays(12),
            'sling_cert_expiry' => now()->subDays(10) // Expired!
        ]);

        // 5. Long Dwell (Demurrage Risk)
        CcuContainer::create([
            'container_number' => 'OLDD0000001', 'type' => 'Dry', 'size' => '20ft', 'owner' => 'Hapag', 'status' => 'in_yard', 'location_yard_zone' => 'B5', 'gate_in_date' => now()->subDays(20) // 20 days > 14 free
        ]);
    }
}
