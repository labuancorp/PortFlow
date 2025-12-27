<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Register extends Component
{
    // User Details
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    // Organization Details
    public $company_name = '';
    public $company_code = '';
    public $billing_address = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:8',
                'same:password_confirmation',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'company_name' => 'required|string|max:255',
            'company_code' => 'required|string|max:10|unique:organizations,code',
            'billing_address' => 'required|string',
        ];
    }

    public function register()
    {
        $this->validate();

        // 1. Create Organization (Agent)
        $org = Organization::create([
            'type' => 'agent',
            'name' => $this->company_name,
            'code' => strtoupper($this->company_code),
            'billing_address' => $this->billing_address,
        ]);

        // 2. Create User linked to Org
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'agent',
            'organization_id' => $org->id,
        ]);

        // 3. Login and Redirect
        Auth::login($user);
        
        $this->dispatch('notify', message: 'Welcome to PortFlow! Please register your vessels.');
        return redirect()->route('agent.portal');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('components.layouts.client');
    }
}
