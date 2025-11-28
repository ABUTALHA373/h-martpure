<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('components.layout.admin')]
class AdminUser2Permissions extends Component
{
    public User $user;
    public $selectedDirectPermissions = [];
    public $selectedRoles = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->selectedDirectPermissions = $user->getDirectPermissions()->pluck('name')->toArray();
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }

    public function render()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('livewire.admin.admin-user-permissions', compact('roles', 'permissions'));
    }

    public function updateRoles()
    {
        $this->user->syncRoles($this->selectedRoles);
        session()->flash('role_message', 'Roles updated successfully.');
    }

    public function updateDirectPermissions()
    {
        $this->user->syncPermissions($this->selectedDirectPermissions);
        session()->flash('permission_message', 'Direct permissions updated successfully.');
    }
}
