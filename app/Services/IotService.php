<?php

namespace App\Services;

use App\Models\IotSensor;
use Carbon\Carbon;

class IotService
{
    /**
     * Simulate fetching live data from IoT Gateway
     * In production, this would connect to MQTT broker or external API.
     */
    public function syncReadings()
    {
        $sensors = IotSensor::where('status', 'active')->get();

        foreach ($sensors as $sensor) {
            $newValue = $this->simulateValue($sensor);
            
            $sensor->update([
                'value' => $newValue,
                'last_reading_at' => Carbon::now()
            ]);
        }
    }

    /**
     * Get overall port safety status based on sensor data
     */
    public function getPortSafetyStatus()
    {
        $criticalCount = IotSensor::where('status', 'active')
            ->whereRaw('value >= threshold_critical')
            ->count();
            
        $warningCount = IotSensor::where('status', 'active')
            ->whereRaw('value >= threshold_warning AND value < threshold_critical')
            ->count();

        if ($criticalCount > 0) {
            return [
                'status' => 'critical',
                'color' => 'red',
                'message' => 'Operations Suspended: unsafe conditions',
                'icon' => 'warning'
            ];
        }

        if ($warningCount > 0) {
            return [
                'status' => 'warning',
                'color' => 'yellow',
                'message' => 'Caution: Elevated risk conditions',
                'icon' => 'alert'
            ];
        }

        return [
            'status' => 'normal',
            'color' => 'green',
            'message' => 'Conditions optimal for operations',
            'icon' => 'check-circle'
        ];
    }

    private function simulateValue($sensor)
    {
        // Add random fluctuation to current value
        $variation = match($sensor->type) {
            'wind' => rand(-20, 20) / 10, // +/- 2 knots
            'tide' => rand(-10, 10) / 100, // +/- 0.1 m
            'swell' => rand(-5, 5) / 10,   // +/- 0.5 m
            'visibility' => rand(-1, 1),   // +/- 1 km
            default => 0
        };

        $newValue = $sensor->value + $variation;

        // Ensure logical bounds
        return match($sensor->type) {
            'wind' => max(0, min(50, $newValue)),
            'tide' => max(0, min(5, $newValue)),
            'swell' => max(0, min(10, $newValue)),
            'visibility' => max(0, min(20, $newValue)),
            default => $newValue
        };
    }
}
