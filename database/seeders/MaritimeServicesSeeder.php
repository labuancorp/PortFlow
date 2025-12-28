<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pilot;
use App\Models\Tugboat;
use App\Models\FuelInventory;

class MaritimeServicesSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Pilots
        $pilots = [
            [
                'name' => 'Captain Ahmad bin Hassan',
                'license_number' => 'MPL-2023-001',
                'phone' => '+60 12-345 6789',
                'email' => 'ahmad.pilot@asb.com',
                'status' => 'available',
                'certifications' => ['Tanker', 'OSV', 'Supply Vessel'],
                'license_expiry' => now()->addYears(2),
                'rate_per_hour' => 500.00,
                'notes' => '15 years experience, certified for all vessel types'
            ],
            [
                'name' => 'Captain John Lee',
                'license_number' => 'MPL-2023-002',
                'phone' => '+60 12-987 6543',
                'email' => 'john.pilot@asb.com',
                'status' => 'available',
                'certifications' => ['Container', 'Bulk Carrier', 'General Cargo'],
                'license_expiry' => now()->addYears(1)->addMonths(6),
                'rate_per_hour' => 550.00,
                'notes' => 'Specialist in large container vessels'
            ],
            [
                'name' => 'Captain Siti Nurhaliza',
                'license_number' => 'MPL-2023-003',
                'phone' => '+60 13-456 7890',
                'email' => 'siti.pilot@asb.com',
                'status' => 'available',
                'certifications' => ['OSV', 'Supply Vessel', 'Tug'],
                'license_expiry' => now()->addYears(3),
                'rate_per_hour' => 480.00,
                'notes' => 'Offshore specialist, 10 years experience'
            ],
        ];

        foreach ($pilots as $pilot) {
            Pilot::create($pilot);
        }

        // Seed Tugboats
        $tugboats = [
            [
                'name' => 'Labuan Warrior',
                'registration_number' => 'TUG-LAB-001',
                'bollard_pull_tons' => 45,
                'status' => 'available',
                'rate_per_hour' => 1500.00,
                'certificate_expiry' => now()->addYears(1),
                'captain_name' => 'Captain Razak',
                'captain_phone' => '+60 14-111 2222',
                'notes' => 'Heavy-duty tugboat, suitable for large vessels'
            ],
            [
                'name' => 'Labuan Guardian',
                'registration_number' => 'TUG-LAB-002',
                'bollard_pull_tons' => 35,
                'status' => 'available',
                'rate_per_hour' => 1200.00,
                'certificate_expiry' => now()->addMonths(8),
                'captain_name' => 'Captain Lim',
                'captain_phone' => '+60 14-333 4444',
                'notes' => 'Medium-duty tugboat for general operations'
            ],
            [
                'name' => 'Labuan Sentinel',
                'registration_number' => 'TUG-LAB-003',
                'bollard_pull_tons' => 50,
                'status' => 'available',
                'rate_per_hour' => 1800.00,
                'certificate_expiry' => now()->addYears(2),
                'captain_name' => 'Captain Wong',
                'captain_phone' => '+60 14-555 6666',
                'notes' => 'Most powerful tug, for heavy vessels and rough weather'
            ],
            [
                'name' => 'Labuan Swift',
                'registration_number' => 'TUG-LAB-004',
                'bollard_pull_tons' => 25,
                'status' => 'available',
                'rate_per_hour' => 900.00,
                'certificate_expiry' => now()->addMonths(10),
                'captain_name' => 'Captain Ibrahim',
                'captain_phone' => '+60 14-777 8888',
                'notes' => 'Fast response tug for small to medium vessels'
            ],
        ];

        foreach ($tugboats as $tugboat) {
            Tugboat::create($tugboat);
        }

        // Seed Fuel Inventory
        $fuelTypes = [
            [
                'fuel_type' => 'diesel',
                'current_stock' => 50000.00,
                'minimum_threshold' => 10000.00,
                'maximum_capacity' => 100000.00,
                'unit' => 'liters',
                'current_price_per_unit' => 3.50,
                'storage_location' => 'Tank A1',
                'last_restocked_at' => now()->subDays(5),
            ],
            [
                'fuel_type' => 'mgo',
                'current_stock' => 30000.00,
                'minimum_threshold' => 8000.00,
                'maximum_capacity' => 75000.00,
                'unit' => 'liters',
                'current_price_per_unit' => 4.20,
                'storage_location' => 'Tank A2',
                'last_restocked_at' => now()->subDays(3),
            ],
            [
                'fuel_type' => 'hfo',
                'current_stock' => 40000.00,
                'minimum_threshold' => 12000.00,
                'maximum_capacity' => 120000.00,
                'unit' => 'liters',
                'current_price_per_unit' => 2.80,
                'storage_location' => 'Tank B1',
                'last_restocked_at' => now()->subWeek(),
            ],
            [
                'fuel_type' => 'lng',
                'current_stock' => 15000.00,
                'minimum_threshold' => 5000.00,
                'maximum_capacity' => 50000.00,
                'unit' => 'cubic_meters',
                'current_price_per_unit' => 12.00,
                'storage_location' => 'LNG Terminal',
                'last_restocked_at' => now()->subDays(2),
            ],
            [
                'fuel_type' => 'freshwater',
                'current_stock' => 80000.00,
                'minimum_threshold' => 20000.00,
                'maximum_capacity' => 200000.00,
                'unit' => 'liters',
                'current_price_per_unit' => 0.50,
                'storage_location' => 'Water Tank 1',
                'last_restocked_at' => now()->subDays(1),
            ],
        ];

        foreach ($fuelTypes as $fuel) {
            FuelInventory::create($fuel);
        }

        $this->command->info('Maritime Services seeded successfully!');
        $this->command->info('- 3 Pilots created');
        $this->command->info('- 4 Tugboats created');
        $this->command->info('- 5 Fuel inventory items created');
    }
}
