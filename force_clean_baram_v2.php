<?php

use App\Models\Organization;
use App\Models\PortCall;
use App\Models\Invoice;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Cache;

$baram = Organization::where('name', 'like', '%Baram%')->first();
$voyages = PortCall::where('agent_id', $baram->id)->get();

echo "Removing " . $voyages->count() . " voyages for Baram.\n";

foreach ($voyages as $voyage) {
    echo "Processing Voyage ID: " . $voyage->id . "\n";
    
    // 1. Delete Invoices
    Invoice::where('port_call_id', $voyage->id)->delete();

    // 2. Delete Service Requests (The missing link!)
    if (class_exists(ServiceRequest::class)) {
         ServiceRequest::where('port_call_id', $voyage->id)->delete();
         echo " - Service Requests deleted.\n";
    }

    // 3. Delete Port Call
    try {
        $voyage->delete();
        echo " - Voyage deleted successfully.\n";
    } catch (\Exception $e) {
        echo " - FAILED to delete voyage: " . $e->getMessage() . "\n";
    }
}

Cache::increment('schedule_version');
