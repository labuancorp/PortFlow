<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AssetBooking;
use App\Models\PortAsset;

class CheckForkliftBooking extends Command
{
    protected $signature = 'check:forklift';
    protected $description = 'Check Heavy Forklift booking status';

    public function handle()
    {
        $this->info('=== HEAVY FORKLIFT 15T DIAGNOSTIC ===');
        $this->newLine();

        // Find the asset
        $asset = PortAsset::where('name', 'LIKE', '%Heavy Forklift%')->first();

        if (!$asset) {
            $this->error('Asset not found!');
            return 1;
        }

        $this->info("Asset Found:");
        $this->line("  ID: {$asset->id}");
        $this->line("  Name: {$asset->name}");
        $this->line("  Status: {$asset->status}");
        $this->newLine();

        // Find all bookings for this asset
        $bookings = AssetBooking::where('port_asset_id', $asset->id)->get();

        $this->info("Total Bookings: {$bookings->count()}");
        $this->newLine();

        foreach ($bookings as $booking) {
            $this->line("Booking #{$booking->id}:");
            $this->line("  Reference: {$booking->reference_no}");
            $this->line("  Status: {$booking->status}");
            $this->line("  Organization ID: {$booking->organization_id}");
            $this->line("  Start Time: {$booking->start_time}");
            $this->line("  End Time: " . ($booking->end_time ?? 'NULL'));
            $this->newLine();
        }

        // Check what the render method would return
        $this->info('=== WHAT INVENTORY PAGE SEES ===');
        $pendingBookings = AssetBooking::where('status', 'requested')->get();
        $this->line("Pending Bookings Count: {$pendingBookings->count()}");

        $activeBookings = AssetBooking::where('status', 'active')->get();
        $this->line("Active Bookings Count: {$activeBookings->count()}");
        
        $approvedBookings = AssetBooking::where('status', 'approved')->get();
        $this->line("Approved Bookings Count: {$approvedBookings->count()}");

        return 0;
    }
}
