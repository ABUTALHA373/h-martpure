<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('components.layout.admin')]
class AdminUserPermissions extends Component
{
    public Admin $admin;
    public $selectedDirectPermissions = [];
    public $assignedRoles = [];

    public $adminId = null, $deleteUserRoleNames = [];

    public function mount(Admin $admin)
    {
        if ($admin->user_type === 'system-admin') {
            abort(403, 'System admin cannot be modified.');
        }

        $this->admin = $admin;
        $this->selectedDirectPermissions = $admin->getDirectPermissions()->pluck('name')->toArray();
        $this->assignedRoles = $admin->roles->pluck('name')->toArray();
    }

    public function render()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('livewire.admin.admin-user-permissions', compact('roles', 'permissions'));
    }

    public function updateRoles()
    {

        if ($this->admin->user_type === 'system-admin') {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: "Cannot update system admin's role.");
            return;
        }
        $this->admin->syncRoles($this->assignedRoles);
        $this->dispatch('toast', type: 'success', title: 'Updated!', message: "Roles updated successfully.");
    }

    public function updateDirectPermissions()
    {

        if ($this->admin->user_type === 'system-admin') {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: "Cannot update system admin's permission.");
            return;
        }
        $this->admin->syncPermissions($this->selectedDirectPermissions);
        $this->dispatch('toast', type: 'success', title: 'Updated!', message: "Direct permissions updated successfully.");
    }

    public function confirmRemoveRole($adminId, $roleNamesJson)
    {
        $this->adminId = $adminId;

        // Decode JSON string into array
        $roleNames = json_decode($roleNamesJson, true);

        $this->deleteUserRoleNames = $roleNames ?? [];

        $admin = Admin::find($this->adminId);
        if ($admin->user_type === 'system-admin' && in_array('super-admin', $this->deleteUserRoleNames)) {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: "Cannot remove system admin's role.");
            return;
        }

        $this->dispatch('confirmSwal', buttonText: 'Yes, remove it!', dpText: 'confirmRemoveRole');
    }


    #[On('confirmRemoveRole')]
    public function removeAdminRole()
    {
        $userAdmin = auth('admin')->user();
        if (!$userAdmin->hasRole('super-admin')) {
            abort(403, 'Unauthorized');
        }
        $admin = Admin::find($this->adminId);
        if (!$admin) {
            $this->dispatch('toast', type: 'error', title: 'Not found!', message: 'User not found.');
            return;
        }
        // Remove the specific role
        foreach ($this->deleteUserRoleNames as $roleName) {
            $admin->removeRole($roleName);
        }

        $this->dispatch('toast', type: 'success', title: 'Removed!', message: 'Role has been removed.');

        // Reset
        $this->adminId = null;
        $this->deleteUserRoleNames = [];

        // Refresh admin roles on UI
        $this->assignedRoles = $admin->roles->pluck('name')->toArray();
    }

}
