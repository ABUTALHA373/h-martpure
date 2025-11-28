<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Layout;

#[Layout('components.layout.admin')]
class AdminRolePermissions extends Component
{
    public Role $role;
    public $selectedPermissions = [];

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    public function render()
    {
        $permissions = Permission::all();
        return view('livewire.admin.admin-role-permissions', compact('permissions'));
    }

    public function updatePermissions()
    {
        $this->role->syncPermissions($this->selectedPermissions);
        session()->flash('message', 'Permissions updated successfully.');
    }
}
