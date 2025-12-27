<?php

use App\Models\AssetBooking;
use App\Models\PortAsset;

echo "=== HEAVY FORKLIFT 15T DIAGNOSTIC ===\n\n";

// Find the asset
$asset = PortAsset::where('name', 'LIKE', '%Heavy Forklift%')->first();

if (!$asset) {
    echo "Asset not found!\n";
    exit;
}

echo "Asset Found:\n";
echo "  ID: {$asset->id}\n";
echo "  Name: {$asset->name}\n";
echo "  Status: {$asset->status}\n\n";

// Find all bookings for this asset
$bookings = AssetBooking::where('port_asset_id', $asset->id)->get();

echo "Total Bookings: {$bookings->count()}\n\n";

foreach ($bookings as $booking) {
    echo "Booking #{$booking->id}:\n";
    echo "  Reference: {$booking->reference_no}\n";
    echo "  Status: {$booking->status}\n";
    echo "  Organization ID: {$booking->organization_id}\n";
    echo "  Start Time: {$booking->start_time}\n";
    echo "  End Time: " . ($booking->end_time ?? 'NULL') . "\n\n";
}

// Check what the render method would return
echo "=== WHAT INVENTORY PAGE SEES ===\n";
$pendingBookings = AssetBooking::with(['organization', 'asset'])
    ->where('status', 'requested')
    ->latest()
    ->get();
echo "Pending Bookings Count: {$pendingBookings->count()}\n";

$activeBookings = AssetBooking::with(['organization', 'asset'])
    ->where('status', 'active')
    ->latest()
    ->get();
echo "Active Bookings Count: {$activeBookings->count()}\n";
