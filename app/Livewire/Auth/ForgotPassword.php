<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Str;

class ForgotPassword extends Component
{
    public $email = '';
    public $message = '';
    public $type = 'success';

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    public function resetPassword()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();
        
        // In a real app, we would send an email. 
        // For this high-tech demo, we simulate the secure token generation.
        $token = Str::random(60);
        
        AuditService::log('Password Reset Request', 'Auth', $user->id, "Requested reset for {$this->email}");

        $this->message = "A secure reset protocol has been initiated. If this account exists, you will receive instructions at your registered endpoint.";
        $this->type = 'success';
        
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('components.layouts.client');
    }
}
