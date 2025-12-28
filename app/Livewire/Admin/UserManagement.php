<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $isEdit = false;
    public $userId;
    
    // Form fields
    public $name;
    public $email;
    public $password;
    public $role = 'agent'; // Default
    public $organization_id;

    public $search = '';

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:admin,agent,hse,asset_manager',
            'organization_id' => 'nullable|exists:organizations,id',
            'password' => $this->isEdit ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    public function render()
    {
        $users = User::with('organization')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $organizations = Organization::orderBy('name')->get();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'organizations' => $organizations,
        ]);
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'role', 'organization_id', 'userId']);
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function editUser($id)
    {
        $this->resetValidation();
        $this->isEdit = true;
        $this->userId = $id;
        
        $user = User::findOrFail($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->organization_id = $user->organization_id;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $user = User::findOrFail($this->userId);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'organization_id' => $this->organization_id,
            ];
            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }
            $user->update($data);
            session()->flash('success', 'User updated successfully.');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'organization_id' => $this->organization_id,
            ]);
            session()->flash('success', 'User created successfully.');
        }

        $this->showModal = false;
        $this->reset(['name', 'email', 'password', 'role', 'organization_id']);
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'User deleted successfully.');
    }
}
