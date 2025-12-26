<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditService;

class Login extends Component
{
    public $email = '';
    public $password = '';

    public function login()
    {
        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            session()->regenerate();
            
            $user = Auth::user();
            AuditService::log('Login', 'Auth', $user->id, "User {$user->email} logged in");

            if ($user->role === 'agent') {
                return redirect()->route('agent.portal');
            } elseif ($user->role === 'admin' || $user->role === 'officer') {
                return redirect()->route('dashboard'); // Assuming dashboard route exists
            } else {
                 return redirect()->intended('/');
            }
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.client');
    }
}
