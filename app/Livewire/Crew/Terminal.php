<?php

namespace App\Livewire\Crew;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\CrewTransfer;
use App\Models\CrewMember;

class Terminal extends Component
{
    public $selectedPortCallId = null;
    public $showScanner = false;
    
    // Scanner Input
    public $passportInput = '';

    public function mount()
    {
        // Auto-select the alongside vessel if only one exists for better demo flow
        $activeCall = PortCall::where('status', 'alongside')->first();
        if ($activeCall) {
            $this->selectedPortCallId = $activeCall->id;
        }
    }

    public function scanPassport()
    {
        // Simple Simulation: Find or Create Crew Member from Passport Input
        // In real life, this comes from an MRZ scanner
        
        if (empty($this->passportInput)) return;

        $passport = $this->passportInput;
        
        $crew = CrewMember::firstOrCreate(
            ['passport_number' => $passport],
            [
                'name' => 'Crew-' . rand(100,999), // Mock Data
                'nationality' => 'Unknown',
                'date_of_birth' => '1990-01-01'
            ]
        );

        // Auto-Link to current vessel transfer list if not exists
        if ($this->selectedPortCallId) {
            $transfer = CrewTransfer::firstOrCreate(
                [
                    'port_call_id' => $this->selectedPortCallId,
                    'crew_member_id' => $crew->id
                ],
                [
                    'direction' => 'sign_off', // Default assumption for demo
                    'status' => 'pending'
                ]
            );

            // Simulate Security Check Process
            if ($transfer->status === 'pending') {
                $transfer->update([
                    'status' => 'security_cleared', 
                    'scanned_at' => now()
                ]);
                $this->dispatch('notify', message: 'Security Check Cleared: ' . $crew->name);
            } elseif ($transfer->status === 'security_cleared') {
                $transfer->update([
                    'status' => 'immigration_cleared', 
                    'scanned_at' => now()
                ]);
                 $this->dispatch('notify', message: 'Immigration Cleared: ' . $crew->name);
            }
        }

        $this->passportInput = '';
    }

    public function processTransfer($id, $action)
    {
        $transfer = CrewTransfer::find($id);
        if ($action === 'flag') {
            $transfer->update(['status' => 'flagged']);
        } elseif ($action === 'clear') {
            $transfer->update(['status' => 'completed', 'scanned_at' => now()]);
        }
    }

    public function render()
    {
        $portCalls = PortCall::whereIn('status', ['alongside', 'anchored'])->get();
        
        $transfers = [];
        if ($this->selectedPortCallId) {
            $transfers = CrewTransfer::where('port_call_id', $this->selectedPortCallId)
                ->with('crewMember')
                ->latest('updated_at')
                ->get();
        }

        return view('livewire.crew.terminal', [
            'portCalls' => $portCalls,
            'transfers' => $transfers
        ])->layout('components.layouts.client'); // Use Client layout for "Kiosk" feel
    }
}
