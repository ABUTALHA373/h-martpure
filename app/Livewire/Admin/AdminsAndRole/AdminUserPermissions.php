<?php

namespace App\Livewire\Admin\AdminsAndRole;

use App\AdminPermission;
use App\Models\Admin;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('components.layout.admin')]
class AdminUserPermissions extends Component
{
    use AdminPermission;

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
        return view('livewire.admin.admins-and-role.admin-user-permissions', compact('roles', 'permissions'));
    }

    public function updateRoles()
    {
        $this->authorizeAdmin('role-permission.assign-admin-role');
        if (auth('admin')->user()->id == $this->admin->id) {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: 'Cannot change own admin role.');
            return;
        }
        if ($this->admin->user_type === 'system-admin') {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: "Cannot update system admin's role.");
            return;
        }
        $previous_roles = $this->admin->roles->pluck('name')->toArray();
        $this->admin->syncRoles($this->assignedRoles);
        $new_roles = $this->admin->roles->pluck('name')->toArray();

        $changes = [
            'added' => array_diff($new_roles, $previous_roles),
            'removed' => array_diff($previous_roles, $new_roles),
        ];
        admin_log('update', [
            'model' => Role::class,
            'model_id' => $this->admin->id,
            'previous' => [
                'previous_role' => $previous_roles
            ],
            'new' => [
                'new_role' => $new_roles,
            ],
            'changes' => $changes,
        ]);
        $this->dispatch('toast', type: 'success', title: 'Updated!', message: "Roles updated successfully.");
    }

    public function updateDirectPermissions()
    {
        $this->authorizeAdmin('role-permission.manage-admin-permission');
        if (auth('admin')->user()->id == $this->admin->id) {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: 'Cannot change own permissions.');
            return;
        }
        if ($this->admin->user_type === 'system-admin') {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: "Cannot update system admin's permission.");
            return;
        }
        $previous_permissions = $this->admin->getDirectPermissions()->pluck('name')->toArray();
        $this->admin->syncPermissions($this->selectedDirectPermissions);
        $new_permissions = $this->admin->getDirectPermissions()->pluck('name')->toArray();
        $changes = [
            'added' => array_diff($new_permissions, $previous_permissions),
            'removed' => array_diff($previous_permissions, $new_permissions),
        ];
        admin_log('updated', [
            'model' => Admin::class,
            'model_id' => $this->admin->id,
            'previous' => [
                'previous_permissions' => $previous_permissions
            ],
            'new' => [
                'new_permissions' => $new_permissions
            ],
            'changes' => $changes,
        ]);
        $this->dispatch('toast', type: 'success', title: 'Updated!', message: "Direct permissions updated successfully.");
    }

    public function confirmRemoveRole($adminId, $roleNamesJson)
    {
        $this->authorizeAdmin('role-permission.remove-admin-role');
        $this->adminId = $adminId;

        // Decode JSON string into array
        $roleNames = json_decode($roleNamesJson, true);

        $this->deleteUserRoleNames = $roleNames ?? [];

        $admin = Admin::find($this->adminId);
        if (auth('admin')->user()->id == $admin->id) {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: 'Cannot remove own admin role.');
            return;
        }
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

        $previousRoles = $admin->roles->pluck('name')->toArray();
        $admin->removeRole($this->deleteUserRoleNames);
        admin_log('deleted', [
            'model' => Role::class,
            'model_id' => $admin->id,
            'previous' => [
                'previous_role' => $previousRoles
            ],
            'new' => [
                'roles' => $admin->roles->pluck('name')->toArray(),
            ],
            'changes' => [
                'removed_role' => $this->deleteUserRoleNames,
            ],
        ]);

        $this->dispatch('toast', type: 'success', title: 'Removed!', message: 'Role has been removed.');

        // Reset
        $this->adminId = null;
        $this->deleteUserRoleNames = [];

        // Refresh admin roles on UI
        $this->assignedRoles = $admin->roles->pluck('name')->toArray();
    }

}
