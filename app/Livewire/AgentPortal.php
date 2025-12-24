<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class AgentPortal extends Component
{
    public $agentId;
    public $activeTab = 'live'; // live, scheduled, history

    public function mount()
    {
        // For Demo purposes: Use authenticated user's org
        // UNLESS the user is an Admin/Authority, then show the Agent view for demo
        $user = Auth::user();
        if ($user && $user->organization_id && $user->role !== 'admin') {
            $this->agentId = $user->organization_id;
        } else {
            // Fallback for Admins or Guests: Show the first agent's view
            $agent = Organization::where('type', 'agent')->first();
            $this->agentId = $agent ? $agent->id : null;
        }
    }

    public function render()
    {
        if (!$this->agentId) {
            return view('livewire.agent-portal', ['portCalls' => []]);
        }

        $query = PortCall::where('agent_id', $this->agentId)
            ->with(['vessel', 'berth', 'invoice']);

        if ($this->activeTab === 'live') {
            $query->whereIn('status', ['anchored', 'alongside', 'approaching']);
        } elseif ($this->activeTab === 'scheduled') {
            $query->whereIn('status', ['requested', 'approved']);
        } elseif ($this->activeTab === 'history') {
            $query->whereIn('status', ['completed', 'cancelled']);
        }

        return view('livewire.agent-portal', [
            'portCalls' => $query->orderBy('eta', 'desc')->get(),
            'agent' => Organization::find($this->agentId)
        ])->layout('components.layouts.client');
    }
}
