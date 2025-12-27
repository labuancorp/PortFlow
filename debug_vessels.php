<?php

use App\Models\Vessel;
use App\Models\PortCall;
use App\Models\Organization;

$targetVessels = ['MV Nautica Gamble', 'OSV Explorer'];

foreach ($targetVessels as $name) {
    echo "Checking $name...\n";
    $vessel = Vessel::where('name', $name)->first();
    
    if (!$vessel) {
        echo " - Not found in DB.\n";
        continue;
    }
    
    $owner = Organization::find($vessel->organization_id);
    echo " - Vessel ID: {$vessel->id}\n";
    echo " - Owner: {$owner->name} (ID: {$vessel->organization_id})\n";
    
    $calls = PortCall::where('vessel_id', $vessel->id)->get();
    echo " - Found " . $calls->count() . " voyages.\n";
    
    foreach ($calls as $call) {
        $agent = Organization::find($call->agent_id);
        echo "   * Voyage ID: {$call->id} | Agent: {$agent->name} (ID: {$call->agent_id})\n";
    }
}

// Check logged-in context simulation
$baram = Organization::where('name', 'like', '%Baram%')->first();
echo "\nBaram Org ID: " . ($baram ? $baram->id : 'Not Found') . "\n";
