<?php

namespace App\Livewire\Gate;

use Livewire\Component;

use Livewire\Attributes\Layout;
use App\Models\GateEntry;

class Scanner extends Component
{
    public $searchUuid;
    public $scannedEntry = null;
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'info'; // info, success, warning, danger

    #[Layout('components.layouts.app')] 
    public function render()
    {
        return view('livewire.gate.scanner', [
            'recentEntries' => GateEntry::where('status', 'checked_in')
                                        ->latest('gate_in_at')
                                        ->take(5)
                                        ->get(),
            'pendingEntries' => GateEntry::where('status', 'pending')
                                         ->latest()
                                         ->get()
        ]);
    }

    public function scan($uuid = null)
    {
        $target = $uuid ?? $this->searchUuid;
        
        $entry = GateEntry::where('uuid', $target)->first();

        if (!$entry) {
            $this->notify('Invalid Pass ID', 'danger');
            return;
        }

        $this->scannedEntry = $entry;
        
        if ($entry->has_dangerous_goods) {
            $this->notify('⚠️ DANGEROUS GOODS DETECTED', 'danger');
        } else {
            $this->notify('Valid Gate Pass Found', 'info');
        }
    }

    public function processCheckIn()
    {
        if (!$this->scannedEntry) return;

        $this->scannedEntry->update([
            'status' => 'checked_in',
            'scanned_at' => now(),
            'gate_in_at' => now(),
        ]);

        $this->notify('Vehicle Checked In Successfully', 'success');
        $this->scannedEntry = null; // Reset for next scan
        $this->searchUuid = '';
    }

    private function notify($message, $type = 'info')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
    }
    
    public function closeAlert()
    {
        $this->showAlert = false;
    }
}
