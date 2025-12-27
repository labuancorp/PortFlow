<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AssetBooking;

class FixForkliftBooking extends Command
{
    protected $signature = 'fix:forklift';
    protected $description = 'Fix Heavy Forklift booking status from approved to active';

    public function handle()
    {
        $booking = AssetBooking::where('status', 'approved')->first();

        if (!$booking) {
            $this->error('No approved bookings found!');
            return 1;
        }

        $this->info("Found booking: {$booking->reference_no}");
        $this->info("Current status: {$booking->status}");

        $booking->update(['status' => 'active']);
        
        $this->info("✅ Booking status updated to 'active'!");
        $this->info("The booking should now appear in 'Active Deployments' sidebar.");

        return 0;
    }
}
