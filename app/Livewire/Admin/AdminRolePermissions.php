<?php

namespace App\Livewire\Admin;

use App\AdminPermission;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('components.layout.admin')]
class AdminRolePermissions extends Component
{
    use AdminPermission;

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
        $this->authorizeAdmin('role-permission.manage-role-permission');
        if (auth('admin')->user()->hasRole($this->role)) {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: "Cannot change own role's permissions.");
            return;
        }
        if ($this->role->name === 'super-admin') {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: "Cannot change to the super-admin role.");
            return;
        }
        $previousPermissions = $this->role->permissions->pluck('name')->toArray();
        $this->role->syncPermissions($this->selectedPermissions);
        $newPermissions = $this->role->permissions->pluck('name')->toArray();
        $changes = [
            'added' => array_diff($newPermissions, $previousPermissions),
            'removed' => array_diff($previousPermissions, $newPermissions),
        ];

        admin_log('updated', [
            'model' => Role::class,
            'model_id' => $this->role->id,
            'previous' => [
                'previous_permissions' => $previousPermissions
            ],
            'new' => [
                'new_permissions' => $newPermissions
            ],
            'changes' => $changes,
        ]);
        $this->dispatch('toast', type: 'success', title: 'Updated!', message: "Direct permissions updated successfully.");
    }
}
