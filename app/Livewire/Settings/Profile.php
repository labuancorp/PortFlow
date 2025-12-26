<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\GenericRule;
use App\Services\AuditService;

class Profile extends Component
{
    public $name;
    public $email;
    
    // Password Update
    public $current_password;
    public $password;
    public $password_confirmation;

    // 2FA
    public $is_2fa_enabled = false;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_2fa_enabled = (bool) $user->is_2fa_enabled;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        AuditService::log('Update', 'Profile', $user->id, 'Updated profile details');
        $this->dispatch('notify', message: 'Profile updated successfully!', type: 'success');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        
        AuditService::log('Update', 'Security', $user->id, 'Changed password');
        $this->dispatch('notify', message: 'Password changed successfully!', type: 'success');
    }

    public function toggleTwoFactor()
    {
        $user = auth()->user();
        $user->is_2fa_enabled = !$user->is_2fa_enabled;
        $user->save();
        $this->is_2fa_enabled = $user->is_2fa_enabled;

        $action = $this->is_2fa_enabled ? 'Enabled' : 'Disabled';
        AuditService::log('Update', 'Security', $user->id, "$action Two-Factor Authentication");
        
        $this->dispatch('notify', message: "2FA has been $action.", type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}
