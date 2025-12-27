<?php

use App\Models\User;
use App\Models\Organization;
use App\Models\PortCall;

// Find John Tan
$john = User::where('name', 'like', '%John Tan%')->first();

if (!$john) {
    echo "John Tan not found.\n";
} else {
    echo "John Tan found. ID: {$john->id}, Role: {$john->role}, Org ID: {$john->organization_id}\n";
    
    // Check Organization
    $org = Organization::find($john->organization_id);
    if ($org) {
        echo "Organization: {$org->name} (ID: {$org->id})\n";
    } else {
        echo "Organization not found for ID {$john->organization_id}\n";
    }

    // Check Port Calls for this Org
    $calls = PortCall::where('agent_id', $john->organization_id)->get();
    echo "Found " . $calls->count() . " Port Calls for this Agent.\n";

    foreach ($calls as $call) {
        echo " - Call ID: {$call->id}, Vessel: " . ($call->vessel->name ?? 'Unknown') . "\n";
    }
}

// Check for duplicate Barams
$barams = Organization::where('name', 'like', '%Baram%')->get();
echo "\nAll 'Baram' Organizations:\n";
foreach ($barams as $b) {
    echo " - ID: {$b->id}, Name: {$b->name}\n";
}
