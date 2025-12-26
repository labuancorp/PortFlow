<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class TwoFactorChallenge extends Component
{
    public $code;
    public $message = '';

    public function mount()
    {
        // On load, if code is missing/expired, generate a new one
        $user = auth()->user();
        if (!$user->two_factor_code || $user->two_factor_expires_at < now()) {
            $this->resendCode();
        }
    }

    public function resendCode()
    {
        $user = auth()->user();
        $code = rand(100000, 999999);
        
        $user->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10)
        ]);
        
        // Log it for local dev (User can see it in terminal or UI)
        \App\Services\AuditService::log('security', '2fa_sent', $user->id, "Code: $code");
        
        // In production: Mail::to($user)->send(new TwoFactorMail($code));
        
        session()->flash('success', "A new code has been sent to your email. (Dev: Check Audit Logs or Database for Code: $code)");
    }

    public function verify()
    {
        $this->validate([
            'code' => 'required|numeric|digits:6'
        ]);

        $user = auth()->user();

        if ($this->code == $user->two_factor_code && $user->two_factor_expires_at > now()) {
            session(['2fa_verified' => true]);
            
            // Clear code
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null
            ]);

            \App\Services\AuditService::log('login', '2fa_success', $user->id, 'User completed 2FA challenge');

            return redirect()->route('dashboard');
        }

        $this->addError('code', 'The provided code is invalid or expired.');
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')->layout('components.layouts.client');
    }
}
