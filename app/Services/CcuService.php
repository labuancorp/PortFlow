<?php

namespace App\Services;

use App\Models\CcuContainer;
use Carbon\Carbon;

class CcuService
{
    const FREE_DAYS = 14;
    const DEMURRAGE_RATE = 50.00; // per day

    /**
     * Calculate demurrage details for a container.
     */
    public function calculateDemurrage(CcuContainer $container): array
    {
        if (!$container->gate_in_date || $container->status !== 'in_yard') {
            return ['days' => 0, 'cost' => 0, 'status' => 'clean'];
        }

        $daysInYard = Carbon::now()->diffInDays($container->gate_in_date);
        
        if ($daysInYard <= self::FREE_DAYS) {
            return [
                'days' => $daysInYard,
                'over_days' => 0,
                'cost' => 0, 
                'status' => 'free_period',
                'remaining' => self::FREE_DAYS - $daysInYard
            ];
        }

        $overDays = $daysInYard - self::FREE_DAYS;
        $cost = $overDays * self::DEMURRAGE_RATE;

        return [
            'days' => $daysInYard,
            'over_days' => $overDays,
            'cost' => $cost,
            'status' => 'demurrage'
        ];
    }

    /**
     * Check if Sling Certificate is valid.
     */
    public function checkSlingCert(CcuContainer $container): array
    {
        if (!$container->sling_cert_expiry) {
            return ['valid' => true, 'status' => 'na']; // Not applicable for standard ISO
        }

        if ($container->sling_cert_expiry->isPast()) {
            return ['valid' => false, 'status' => 'expired', 'msg' => 'Expired ' . $container->sling_cert_expiry->diffForHumans()];
        }

        if ($container->sling_cert_expiry->diffInDays(now()) < 30) {
             return ['valid' => true, 'status' => 'expiring_soon', 'msg' => 'Expires in ' . $container->sling_cert_expiry->diffInDays(now()) . ' days'];
        }

        return ['valid' => true, 'status' => 'valid'];
    }
}
