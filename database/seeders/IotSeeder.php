<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IotSensor;

class IotSeeder extends Seeder
{
    public function run(): void
    {
        $sensors = [
            [
                'name' => 'Tide Gauge Alpha',
                'code' => 'TIDE-01',
                'type' => 'tide',
                'value' => 2.4,
                'unit' => 'm',
                'threshold_warning' => 3.5,
                'threshold_critical' => 4.5
            ],
            [
                'name' => 'Wind Sensor North',
                'code' => 'WIND-N',
                'type' => 'wind',
                'value' => 12.5,
                'unit' => 'knots',
                'threshold_warning' => 20,
                'threshold_critical' => 35
            ],
            [
                'name' => 'Swell Buoy 1',
                'code' => 'SWELL-01',
                'type' => 'swell',
                'value' => 0.8,
                'unit' => 'm',
                'threshold_warning' => 2.0,
                'threshold_critical' => 3.0
            ],
            [
                'name' => 'Main Channel Visibility',
                'code' => 'VIS-01',
                'type' => 'visibility',
                'value' => 10,
                'unit' => 'km',
                'threshold_warning' => 2.0, // Low visibility warning
                'threshold_critical' => 0.5 // Critical (fog)
            ]
        ];

        foreach ($sensors as $sensor) {
            IotSensor::updateOrCreate(
                ['code' => $sensor['code']],
                $sensor
            );
        }
    }
}
