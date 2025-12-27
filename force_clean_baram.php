<?php

use App\Models\Organization;
use App\Models\PortCall;
use App\Models\Invoice;
use Illuminate\Support\Facades\Cache;

$baram = Organization::where('name', 'like', '%Baram%')->first();

if (!$baram) {
    echo "Baram organization not found.\n";
    exit;
}

echo "Targeting Agent: " . $baram->name . " (ID: " . $baram->id . ")\n";

$voyages = PortCall::where('agent_id', $baram->id)->get();
echo "Found " . $voyages->count() . " voyages to remove.\n";

foreach ($voyages as $voyage) {
    echo " - Processing Voyage ID: " . $voyage->id . "\n";
    
    // Delete Invoices
    $deletedInvoices = Invoice::where('port_call_id', $voyage->id)->delete();
    if ($deletedInvoices > 0) {
        echo "   * Deleted $deletedInvoices linked invoices.\n";
    }

    // Delete Voyage
    try {
        $voyage->delete();
        echo "   * Deleted Voyage.\n";
    } catch (\Exception $e) {
        echo "   ! Error deleting voyage: " . $e->getMessage() . "\n";
    }
}

Cache::increment('schedule_version');
echo "Cache Version Incremented.\n";
echo "Cleanup Complete.\n";
