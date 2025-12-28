<?php

// Find the port call with invoice INV-UAKULKLD
$invoice = \App\Models\Invoice::where('invoice_no', 'INV-UAKULKLD')->first();

if (!$invoice) {
    echo "Invoice not found!\n";
    exit;
}

$portCall = $invoice->portCall;

echo "=== Port Call Analysis ===\n";
echo "Port Call ID: {$portCall->id}\n";
echo "Vessel: {$portCall->vessel->name}\n";
echo "Status: {$portCall->status}\n\n";

echo "=== Maritime Services ===\n";
$pilotageCount = $portCall->pilotageRequests()->count();
$completedPilotage = $portCall->pilotageRequests()->where('status', 'completed')->count();
echo "Pilotage Requests: {$pilotageCount} (Completed: {$completedPilotage})\n";

$towageCount = $portCall->towageRequests()->count();
$completedTowage = $portCall->towageRequests()->where('status', 'completed')->count();
echo "Towage Requests: {$towageCount} (Completed: {$completedTowage})\n\n";

if ($completedPilotage == 0 && $completedTowage == 0) {
    echo "❌ NO COMPLETED MARITIME SERVICES FOUND\n";
    echo "This is why pilotage/towage fees are not in the invoice.\n\n";
    
    echo "=== Creating Test Maritime Services ===\n";
    
    // Get first available pilot and tugboat
    $pilot = \App\Models\Pilot::where('status', 'available')->first();
    $tugboat = \App\Models\Tugboat::where('status', 'available')->first();
    
    if (!$pilot) {
        echo "❌ No pilots available. Run: php artisan db:seed --class=MaritimeServicesSeeder\n";
        exit;
    }
    
    if (!$tugboat) {
        echo "❌ No tugboats available. Run: php artisan db:seed --class=MaritimeServicesSeeder\n";
        exit;
    }
    
    // Create pilotage request
    $pilotage = \App\Models\PilotageRequest::create([
        'port_call_id' => $portCall->id,
        'pilot_id' => $pilot->id,
        'service_type' => 'inbound',
        'status' => 'completed',
        'requested_time' => now()->subDays(2),
        'scheduled_time' => now()->subDays(2),
        'actual_start' => now()->subDays(2),
        'actual_end' => now()->subDays(2)->addHours(2),
        'boarding_point' => 'Pilot Station Alpha',
        'weather_condition' => 'calm',
    ]);
    
    $pilotage->calculateFee();
    echo "✅ Created Pilotage Service - Fee: RM " . number_format($pilotage->calculated_fee, 2) . "\n";
    
    // Create towage request
    $towage = \App\Models\TowageRequest::create([
        'port_call_id' => $portCall->id,
        'tugboat_id' => $tugboat->id,
        'service_type' => 'berthing',
        'tugboats_required' => 2,
        'status' => 'completed',
        'requested_time' => now()->subDays(2),
        'scheduled_time' => now()->subDays(2),
        'actual_start' => now()->subDays(2),
        'actual_end' => now()->subDays(2)->addHours(3),
        'from_location' => 'Anchorage A',
        'to_location' => 'Berth 1',
        'weather_condition' => 'calm',
    ]);
    
    $towage->calculateFee();
    echo "✅ Created Towage Service - Fee: RM " . number_format($towage->calculated_fee, 2) . "\n\n";
    
    echo "=== Regenerating Invoice ===\n";
    
    // Delete old invoice items
    $invoice->invoiceItems()->delete();
    
    // Regenerate invoice
    $billingService = new \App\Services\BillingService();
    $newInvoice = $billingService->generateInvoice($portCall);
    
    echo "✅ Invoice regenerated!\n";
    echo "New Total: RM " . number_format($newInvoice->total_amount, 2) . "\n\n";
    
    echo "=== Invoice Items ===\n";
    foreach ($newInvoice->invoiceItems as $item) {
        echo "- {$item->description}: RM " . number_format($item->total_price, 2) . "\n";
    }
    
    echo "\n✅ DONE! Refresh the billing page to see the updated invoice.\n";
} else {
    echo "✅ Maritime services found!\n";
    echo "Regenerating invoice to ensure fees are included...\n\n";
    
    // Delete old invoice items
    $invoice->invoiceItems()->delete();
    
    // Regenerate invoice
    $billingService = new \App\Services\BillingService();
    $newInvoice = $billingService->generateInvoice($portCall);
    
    echo "=== Updated Invoice Items ===\n";
    foreach ($newInvoice->invoiceItems as $item) {
        echo "- {$item->description}: RM " . number_format($item->total_price, 2) . "\n";
    }
    
    echo "\nNew Total: RM " . number_format($newInvoice->total_amount, 2) . "\n";
}
