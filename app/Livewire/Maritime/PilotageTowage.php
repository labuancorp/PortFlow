<?php

namespace App\Livewire\Maritime;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pilot;
use App\Models\Tugboat;
use App\Models\PilotageRequest;
use App\Models\TowageRequest;
use App\Models\PortCall;

class PilotageTowage extends Component
{
    use WithPagination;

    public $activeTab = 'pilotage'; // pilotage, towage, pilots, tugboats
    public $showModal = false;
    public $modalType = ''; // pilotage_request, towage_request, pilot, tugboat
    
    // Pilotage Request Form
    public $pilotageId;
    public $pilotage_port_call_id;
    public $pilotage_pilot_id;
    public $pilotage_service_type = 'inbound';
    public $pilotage_scheduled_time;
    public $pilotage_boarding_point;
    public $pilotage_weather_condition;
    public $pilotage_notes;

    // Towage Request Form
    public $towageId;
    public $towage_port_call_id;
    public $towage_tugboat_id;
    public $towage_service_type = 'berthing';
    public $towage_tugboats_required = 1;
    public $towage_scheduled_time;
    public $towage_from_location;
    public $towage_to_location;
    public $towage_weather_condition;
    public $towage_notes;

    // Pilot Form
    public $pilotId;
    public $pilot_name;
    public $pilot_license_number;
    public $pilot_phone;
    public $pilot_email;
    public $pilot_certifications = [];
    public $pilot_license_expiry;
    public $pilot_rate_per_hour = 500;

    // Tugboat Form
    public $tugboatId;
    public $tugboat_name;
    public $tugboat_registration_number;
    public $tugboat_bollard_pull_tons;
    public $tugboat_rate_per_hour = 1500;
    public $tugboat_certificate_expiry;
    public $tugboat_captain_name;
    public $tugboat_captain_phone;

    protected function pilotageRules()
    {
        return [
            'pilotage_port_call_id' => 'required|exists:port_calls,id',
            'pilotage_pilot_id' => 'nullable|exists:pilots,id',
            'pilotage_service_type' => 'required|in:inbound,outbound,shifting',
            'pilotage_scheduled_time' => 'required|date',
            'pilotage_boarding_point' => 'nullable|string',
            'pilotage_weather_condition' => 'nullable|string',
            'pilotage_notes' => 'nullable|string',
        ];
    }

    protected function towageRules()
    {
        return [
            'towage_port_call_id' => 'required|exists:port_calls,id',
            'towage_tugboat_id' => 'nullable|exists:tugboats,id',
            'towage_service_type' => 'required|in:berthing,unberthing,shifting,escort',
            'towage_tugboats_required' => 'required|integer|min:1|max:4',
            'towage_scheduled_time' => 'required|date',
            'towage_from_location' => 'nullable|string',
            'towage_to_location' => 'nullable|string',
            'towage_weather_condition' => 'nullable|string',
            'towage_notes' => 'nullable|string',
        ];
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // Pilotage Request Methods
    public function openPilotageModal($id = null)
    {
        $this->resetValidation();
        $this->modalType = 'pilotage_request';
        
        if ($id) {
            $request = PilotageRequest::findOrFail($id);
            $this->pilotageId = $request->id;
            $this->pilotage_port_call_id = $request->port_call_id;
            $this->pilotage_pilot_id = $request->pilot_id;
            $this->pilotage_service_type = $request->service_type;
            $this->pilotage_scheduled_time = $request->scheduled_time?->format('Y-m-d\TH:i');
            $this->pilotage_boarding_point = $request->boarding_point;
            $this->pilotage_weather_condition = $request->weather_condition;
            $this->pilotage_notes = $request->notes;
        } else {
            $this->reset(['pilotageId', 'pilotage_port_call_id', 'pilotage_pilot_id', 'pilotage_service_type', 
                          'pilotage_scheduled_time', 'pilotage_boarding_point', 'pilotage_weather_condition', 'pilotage_notes']);
            $this->pilotage_service_type = 'inbound';
        }
        
        $this->showModal = true;
    }

    public function savePilotageRequest()
    {
        $this->validate($this->pilotageRules());

        $data = [
            'port_call_id' => $this->pilotage_port_call_id,
            'pilot_id' => $this->pilotage_pilot_id,
            'service_type' => $this->pilotage_service_type,
            'requested_time' => now(),
            'scheduled_time' => $this->pilotage_scheduled_time,
            'boarding_point' => $this->pilotage_boarding_point,
            'weather_condition' => $this->pilotage_weather_condition,
            'notes' => $this->pilotage_notes,
            'status' => $this->pilotage_pilot_id ? 'assigned' : 'requested',
        ];

        if ($this->pilotageId) {
            PilotageRequest::findOrFail($this->pilotageId)->update($data);
            session()->flash('success', 'Pilotage request updated successfully.');
        } else {
            PilotageRequest::create($data);
            session()->flash('success', 'Pilotage request created successfully.');
        }

        $this->showModal = false;
    }

    public function startPilotage($id)
    {
        $request = PilotageRequest::findOrFail($id);
        $request->update([
            'status' => 'in_progress',
            'actual_start' => now(),
        ]);
        
        if ($request->pilot) {
            $request->pilot->update(['status' => 'on_duty']);
        }
        
        session()->flash('success', 'Pilotage service started.');
    }

    public function completePilotage($id)
    {
        $request = PilotageRequest::findOrFail($id);
        $request->complete();
        session()->flash('success', 'Pilotage service completed. Fee: RM ' . number_format($request->calculated_fee, 2));
    }

    // Towage Request Methods
    public function openTowageModal($id = null)
    {
        $this->resetValidation();
        $this->modalType = 'towage_request';
        
        if ($id) {
            $request = TowageRequest::findOrFail($id);
            $this->towageId = $request->id;
            $this->towage_port_call_id = $request->port_call_id;
            $this->towage_tugboat_id = $request->tugboat_id;
            $this->towage_service_type = $request->service_type;
            $this->towage_tugboats_required = $request->tugboats_required;
            $this->towage_scheduled_time = $request->scheduled_time?->format('Y-m-d\TH:i');
            $this->towage_from_location = $request->from_location;
            $this->towage_to_location = $request->to_location;
            $this->towage_weather_condition = $request->weather_condition;
            $this->towage_notes = $request->notes;
        } else {
            $this->reset(['towageId', 'towage_port_call_id', 'towage_tugboat_id', 'towage_service_type', 
                          'towage_tugboats_required', 'towage_scheduled_time', 'towage_from_location', 
                          'towage_to_location', 'towage_weather_condition', 'towage_notes']);
            $this->towage_service_type = 'berthing';
            $this->towage_tugboats_required = 1;
        }
        
        $this->showModal = true;
    }

    public function saveTowageRequest()
    {
        $this->validate($this->towageRules());

        $data = [
            'port_call_id' => $this->towage_port_call_id,
            'tugboat_id' => $this->towage_tugboat_id,
            'service_type' => $this->towage_service_type,
            'tugboats_required' => $this->towage_tugboats_required,
            'requested_time' => now(),
            'scheduled_time' => $this->towage_scheduled_time,
            'from_location' => $this->towage_from_location,
            'to_location' => $this->towage_to_location,
            'weather_condition' => $this->towage_weather_condition,
            'notes' => $this->towage_notes,
            'status' => $this->towage_tugboat_id ? 'assigned' : 'requested',
        ];

        if ($this->towageId) {
            TowageRequest::findOrFail($this->towageId)->update($data);
            session()->flash('success', 'Towage request updated successfully.');
        } else {
            TowageRequest::create($data);
            session()->flash('success', 'Towage request created successfully.');
        }

        $this->showModal = false;
    }

    public function startTowage($id)
    {
        $request = TowageRequest::findOrFail($id);
        $request->update([
            'status' => 'in_progress',
            'actual_start' => now(),
        ]);
        
        if ($request->tugboat) {
            $request->tugboat->update(['status' => 'in_service']);
        }
        
        session()->flash('success', 'Towage service started.');
    }

    public function completeTowage($id)
    {
        $request = TowageRequest::findOrFail($id);
        $request->complete();
        session()->flash('success', 'Towage service completed. Fee: RM ' . number_format($request->calculated_fee, 2));
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->modalType = '';
    }

    // Pilot CRUD Methods
    public function openPilotModal($id = null)
    {
        $this->resetValidation();
        $this->modalType = 'pilot';
        
        if ($id) {
            $pilot = Pilot::findOrFail($id);
            $this->pilotId = $pilot->id;
            $this->pilot_name = $pilot->name;
            $this->pilot_license_number = $pilot->license_number;
            $this->pilot_phone = $pilot->phone;
            $this->pilot_email = $pilot->email;
            $this->pilot_certifications = $pilot->certifications ?? [];
            $this->pilot_license_expiry = $pilot->license_expiry?->format('Y-m-d');
            $this->pilot_rate_per_hour = $pilot->rate_per_hour;
        } else {
            $this->reset(['pilotId', 'pilot_name', 'pilot_license_number', 'pilot_phone', 'pilot_email', 'pilot_certifications', 'pilot_license_expiry', 'pilot_rate_per_hour']);
            $this->pilot_rate_per_hour = 500;
            $this->pilot_certifications = [];
        }
        
        $this->showModal = true;
    }

    public function savePilot()
    {
        $this->validate([
            'pilot_name' => 'required|string|max:255',
            'pilot_license_number' => 'required|string|max:100|unique:pilots,license_number,' . ($this->pilotId ?? 'NULL'),
            'pilot_phone' => 'nullable|string|max:50',
            'pilot_email' => 'nullable|email|max:255',
            'pilot_license_expiry' => 'required|date',
            'pilot_rate_per_hour' => 'required|numeric|min:0',
        ]);

        $data = [
            'name' => $this->pilot_name,
            'license_number' => $this->pilot_license_number,
            'phone' => $this->pilot_phone,
            'email' => $this->pilot_email,
            'certifications' => $this->pilot_certifications,
            'license_expiry' => $this->pilot_license_expiry,
            'rate_per_hour' => $this->pilot_rate_per_hour,
            'status' => 'available',
        ];

        if ($this->pilotId) {
            Pilot::findOrFail($this->pilotId)->update($data);
            session()->flash('success', 'Pilot updated successfully.');
        } else {
            Pilot::create($data);
            session()->flash('success', 'Pilot added successfully.');
        }

        $this->showModal = false;
    }

    public function deletePilot($id)
    {
        Pilot::findOrFail($id)->delete();
        session()->flash('success', 'Pilot deleted successfully.');
    }

    // Tugboat CRUD Methods
    public function openTugboatModal($id = null)
    {
        $this->resetValidation();
        $this->modalType = 'tugboat';
        
        if ($id) {
            $tugboat = Tugboat::findOrFail($id);
            $this->tugboatId = $tugboat->id;
            $this->tugboat_name = $tugboat->name;
            $this->tugboat_registration_number = $tugboat->registration_number;
            $this->tugboat_bollard_pull_tons = $tugboat->bollard_pull_tons;
            $this->tugboat_rate_per_hour = $tugboat->rate_per_hour;
            $this->tugboat_certificate_expiry = $tugboat->certificate_expiry?->format('Y-m-d');
            $this->tugboat_captain_name = $tugboat->captain_name;
            $this->tugboat_captain_phone = $tugboat->captain_phone;
        } else {
            $this->reset(['tugboatId', 'tugboat_name', 'tugboat_registration_number', 'tugboat_bollard_pull_tons', 'tugboat_rate_per_hour', 'tugboat_certificate_expiry', 'tugboat_captain_name', 'tugboat_captain_phone']);
            $this->tugboat_rate_per_hour = 1500;
        }
        
        $this->showModal = true;
    }

    public function saveTugboat()
    {
        $this->validate([
            'tugboat_name' => 'required|string|max:255',
            'tugboat_registration_number' => 'required|string|max:100|unique:tugboats,registration_number,' . ($this->tugboatId ?? 'NULL'),
            'tugboat_bollard_pull_tons' => 'required|numeric|min:0',
            'tugboat_rate_per_hour' => 'required|numeric|min:0',
            'tugboat_certificate_expiry' => 'nullable|date',
            'tugboat_captain_name' => 'nullable|string|max:255',
            'tugboat_captain_phone' => 'nullable|string|max:50',
        ]);

        $data = [
            'name' => $this->tugboat_name,
            'registration_number' => $this->tugboat_registration_number,
            'bollard_pull_tons' => $this->tugboat_bollard_pull_tons,
            'rate_per_hour' => $this->tugboat_rate_per_hour,
            'certificate_expiry' => $this->tugboat_certificate_expiry,
            'captain_name' => $this->tugboat_captain_name,
            'captain_phone' => $this->tugboat_captain_phone,
            'status' => 'available',
        ];

        if ($this->tugboatId) {
            Tugboat::findOrFail($this->tugboatId)->update($data);
            session()->flash('success', 'Tugboat updated successfully.');
        } else {
            Tugboat::create($data);
            session()->flash('success', 'Tugboat added successfully.');
        }

        $this->showModal = false;
    }

    public function deleteTugboat($id)
    {
        Tugboat::findOrFail($id)->delete();
        session()->flash('success', 'Tugboat deleted successfully.');
    }

    public function render()
    {
        $data = [];

        if ($this->activeTab === 'pilotage') {
            $data['pilotageRequests'] = PilotageRequest::with(['portCall.vessel', 'pilot'])
                ->orderBy('scheduled_time', 'desc')
                ->paginate(15);
        } elseif ($this->activeTab === 'towage') {
            $data['towageRequests'] = TowageRequest::with(['portCall.vessel', 'tugboat'])
                ->orderBy('scheduled_time', 'desc')
                ->paginate(15);
        } elseif ($this->activeTab === 'pilots') {
            $data['pilots'] = Pilot::orderBy('name')->paginate(15);
        } elseif ($this->activeTab === 'tugboats') {
            $data['tugboats'] = Tugboat::orderBy('name')->paginate(15);
        }

        $data['portCalls'] = PortCall::with('vessel')
            ->whereIn('status', ['requested', 'approved', 'anchored', 'alongside'])
            ->orderBy('eta')
            ->get();
        $data['availablePilots'] = Pilot::available()->get();
        $data['availableTugboats'] = Tugboat::available()->get();

        return view('livewire.maritime.pilotage-towage', $data);
    }
}
