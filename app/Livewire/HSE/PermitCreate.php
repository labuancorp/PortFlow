<?php

namespace App\Livewire\HSE;

use Livewire\Component;
use App\Models\WorkPermit;
use App\Services\AuditService;
use Illuminate\Support\Str;

class PermitCreate extends Component
{
    public $type = 'hot_work';
    public $location;
    public $applicant_name;
    public $valid_from;
    public $valid_to;
    public $description;

    public function mount()
    {
        $this->valid_from = now()->format('Y-m-d\TH:i');
        $this->valid_to = now()->addHours(8)->format('Y-m-d\TH:i');
    }

    protected $rules = [
        'type' => 'required|in:hot_work,working_at_height,confined_space,cold_work,electrical',
        'location' => 'required|string|max:255',
        'applicant_name' => 'required|string|max:255',
        'valid_from' => 'required|date',
        'valid_to' => 'required|date|after:valid_from',
        'description' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        // Clash Detection
        $clash = WorkPermit::where('location', $this->location)
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'closed')
            ->where(function ($query) {
                $query->whereBetween('valid_from', [$this->valid_from, $this->valid_to])
                      ->orWhereBetween('valid_to', [$this->valid_from, $this->valid_to])
                      ->orWhere(function ($q) {
                          $q->where('valid_from', '<', $this->valid_from)
                            ->where('valid_to', '>', $this->valid_to);
                      });
            })
            ->exists();

        if ($clash) {
            $this->addError('location', 'High Risk Conflict: A permit already exists for this location during the specified time.');
            return;
        }

        $controlNo = 'PTW-' . date('Y') . '-' . strtoupper(Str::random(5));

        $permit = WorkPermit::create([
            'control_no' => $controlNo,
            'type' => $this->type,
            'location' => $this->location,
            'applicant_name' => $this->applicant_name,
            'organization_id' => auth()->user()->organization_id,
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to,
            'status' => 'requested',
            'description' => $this->description,
        ]);

        AuditService::log('Create', 'HSE', $permit->id, "Requested Permit $controlNo");
        
        $this->dispatch('notify', message: 'Permit request submitted successfully.');
        $this->redirect(route('hse.permits.dashboard'));
    }

    public function render()
    {
        return view('livewire.h-s-e.permit-create');
    }
}
