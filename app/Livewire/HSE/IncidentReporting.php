<?php

namespace App\Livewire\HSE;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\SafetyIncident;
use App\Services\AuditService;

class IncidentReporting extends Component
{
    use WithPagination, WithFileUploads;

    public $showReportModal = false;
    public $showInvestigationModal = false;
    public $selectedIncident = null;

    // Form fields
    public $type = 'hazard_observation';
    public $severity = 'low';
    public $location = '';
    public $description = '';
    public $image = null;

    // Investigation fields
    public $status = 'open';
    public $corrective_action = '';

    protected $rules = [
        'type' => 'required|in:incident,near_miss,hazard_observation',
        'severity' => 'required|in:low,medium,high,critical',
        'location' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|max:1024', // 1MB Max
    ];

    public function openReportModal()
    {
        $this->reset(['type', 'severity', 'location', 'description', 'image']);
        $this->showReportModal = true;
    }

    public function submitReport()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('safety-incidents', 'public');
        }

        $incident = SafetyIncident::create([
            'reporter_id' => auth()->id(),
            'organization_id' => auth()->user()->organization_id,
            'type' => $this->type,
            'severity' => $this->severity,
            'location' => $this->location,
            'description' => $this->description,
            'image_path' => $imagePath,
            'reported_at' => now(),
            'status' => 'open'
        ]);

        AuditService::log('Create', 'HSE', $incident->id, "Reported {$this->type} at {$this->location}");

        $this->showReportModal = false;
        $this->dispatch('notify', message: 'Safety report submitted successfully. Thank you for staying vigilant.', type: 'success');
    }

    public function openInvestigation($id)
    {
        $this->selectedIncident = SafetyIncident::find($id);
        $this->status = $this->selectedIncident->status;
        $this->corrective_action = $this->selectedIncident->corrective_action;
        $this->showInvestigationModal = true;
    }

    public function saveInvestigation()
    {
        if (!in_array(auth()->user()->role, ['admin', 'hse'])) {
             $this->dispatch('notify', message: 'Unauthorized action.', type: 'error');
             return;
        }

        $this->selectedIncident->update([
            'status' => $this->status,
            'corrective_action' => $this->corrective_action
        ]);

        AuditService::log('Update', 'HSE', $this->selectedIncident->id, "Updated investigation status to {$this->status}");

        $this->showInvestigationModal = false;
        $this->dispatch('notify', message: 'Investigation and corrective actions updated.', type: 'success');
    }

    public function render()
    {
        $query = SafetyIncident::with(['reporter', 'organization']);

        // Agents only see their organization's reports
        if (auth()->user()->role === 'agent') {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        return view('livewire.h-s-e.incident-reporting', [
            'incidents' => $query->latest()->paginate(10)
        ]);
    }
}
