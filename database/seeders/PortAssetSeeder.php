<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\PortAsset::create([
            'name' => 'Crawler Crane 250T',
            'type' => 'crane',
            'identifier' => 'CRN-001-250T',
            'rate_per_hour' => 1250.00,
            'rate_per_day' => 8500.00,
            'status' => 'available',
            'description' => 'Heavy duty crawler crane for offshore tubular loading.',
        ]);

        \App\Models\PortAsset::create([
            'name' => 'Mobile Crane 50T',
            'type' => 'crane',
            'identifier' => 'CRN-M02-50T',
            'rate_per_hour' => 450.00,
            'rate_per_day' => 3200.00,
            'status' => 'available',
            'description' => 'Versatile mobile crane for yard operations.',
        ]);

        \App\Models\PortAsset::create([
            'name' => 'Heavy Forklift 15T',
            'type' => 'forklift',
            'identifier' => 'FL-H01-15T',
            'rate_per_hour' => 250.00,
            'rate_per_day' => 1800.00,
            'status' => 'available',
            'description' => 'High capacity forklift for pipe handling.',
        ]);

        \App\Models\PortAsset::create([
            'name' => 'Chemical Warehouse Bay A1',
            'type' => 'warehouse_bay',
            'identifier' => 'W1-A1',
            'rate_per_hour' => 50.00,
            'rate_per_day' => 400.00,
            'status' => 'available',
            'description' => 'Temperature controlled chemical storage bay.',
        ]);
    }
}
