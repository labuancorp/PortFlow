# Testing Pilotage & Towage Billing Integration

## Issue Identified:
1. ✅ **Timestamps missing** - FIXED (added Date column to invoice table)
2. ⚠️ **Pilotage/Towage fees not showing** - Need to test

## How to Test:

### **Step 1: Create a Test Port Call with Maritime Services**

Run this in **Tinker** (`php artisan tinker`):

```php
// 1. Get or create a port call
$portCall = \App\Models\PortCall::first();

// If no port call exists, create one
if (!$portCall) {
    $vessel = \App\Models\Vessel::first();
    $org = \App\Models\Organization::first();
    
    $portCall = \App\Models\PortCall::create([
        'vessel_id' => $vessel->id,
        'agent_id' => $org->id,
        'status' => 'alongside',
        'eta' => now()->subDays(2),
        'etd' => now()->addDays(1),
        'ata' => now()->subDays(2),
        'atb' => now()->subDays(2),
        'atd' => now(), // Departed
        'reference_no' => 'PC-TEST-001'
    ]);
}

// 2. Create a Pilotage Request
$pilot = \App\Models\Pilot::first();

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

// 3. Calculate the fee
$pilotage->calculateFee();

echo "Pilotage Fee: RM " . number_format($pilotage->calculated_fee, 2) . "\n";

// 4. Create a Towage Request
$tugboat = \App\Models\Tugboat::first();

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

// 5. Calculate the fee
$towage->calculateFee();

echo "Towage Fee: RM " . number_format($towage->calculated_fee, 2) . "\n";

// 6. Generate Invoice
$billingService = new \App\Services\BillingService();
$invoice = $billingService->generateInvoice($portCall);

echo "Invoice Total: RM " . number_format($invoice->total_amount, 2) . "\n";
echo "Invoice Items Count: " . $invoice->invoiceItems()->count() . "\n";

// 7. Show all invoice items
foreach ($invoice->invoiceItems as $item) {
    echo "- " . $item->description . ": RM " . number_format($item->total_price, 2) . "\n";
}
```

### **Expected Output:**
```
Pilotage Fee: RM 1,000.00
Towage Fee: RM 9,000.00
Invoice Total: RM 14,350.00
Invoice Items Count: 5

- Dockage Fees (100m x 48 hrs @ RM 1.50/m/hr): RM 7,200.00
- Wharfage Fee (Fixed): RM 500.00
- Line Handling Services: RM 250.00
- Pilotage Service - Inbound (Captain Ahmad, 2 hrs): RM 1,000.00
- Towage Service - Berthing (Labuan Warrior, 2 tug(s), 3 hrs): RM 9,000.00
```

---

## Quick Test (Simplified):

### **Option A: Use Existing Data**
If you already have port calls with completed pilotage/towage:

1. Go to `/billing`
2. Find a port call in "Pending Billing"
3. Click "Generate Invoice"
4. Click "View" on the generated invoice
5. **Check:** Invoice items should include pilotage and towage fees

### **Option B: Create Test Data via UI**
1. Go to `/maritime/pilotage-towage`
2. Create a pilotage request for an existing port call
3. Assign a pilot
4. Click "Start"
5. Click "Complete" (fee will be calculated)
6. Repeat for towage
7. Go to `/billing`
8. Generate invoice for that port call
9. **Verify:** Pilotage and towage fees are in the invoice

---

## Troubleshooting:

### **If fees still don't appear:**

**Check 1: Are services completed?**
```php
$portCall = \App\Models\PortCall::find(1);
$completedPilotage = $portCall->pilotageRequests()->where('status', 'completed')->count();
$completedTowage = $portCall->towageRequests()->where('status', 'completed')->count();

echo "Completed Pilotage: {$completedPilotage}\n";
echo "Completed Towage: {$completedTowage}\n";
```

**Check 2: Are fees calculated?**
```php
$pilotage = \App\Models\PilotageRequest::where('status', 'completed')->first();
if ($pilotage) {
    echo "Pilotage Fee: RM " . number_format($pilotage->calculated_fee, 2) . "\n";
} else {
    echo "No completed pilotage requests found\n";
}
```

**Check 3: Regenerate invoice**
```php
$portCall = \App\Models\PortCall::find(1);
$invoice = $portCall->invoice;
if ($invoice) {
    $invoice->delete(); // Delete old invoice
}

$billingService = new \App\Services\BillingService();
$newInvoice = $billingService->generateInvoice($portCall);

echo "New invoice items:\n";
foreach ($newInvoice->invoiceItems as $item) {
    echo "- " . $item->description . "\n";
}
```

---

## Changes Made:

### **1. Billing View (index.blade.php)**
✅ Added "Date" column to invoice table  
✅ Shows issued date and due date  
✅ Better invoice management

### **2. BillingService.php**
✅ Added eager loading for pilotage and towage relationships  
✅ Ensures maritime services are loaded when generating invoices  

---

## Next Steps:

1. **Test the integration** using one of the methods above
2. **Verify** pilotage and towage fees appear in invoices
3. **Report back** if fees still don't show (we'll debug further)

---

**Status:** ✅ Timestamps added, ⚠️ Fees integration ready for testing
