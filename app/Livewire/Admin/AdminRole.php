<?php

namespace App\Livewire\Admin;

use App\Mail\TestMail;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('components.layout.admin')]
class AdminRole extends Component
{
    public $role_name;
    public $showAddModal = false;
    public $showAddAdminModal = false;
    public $deleteRoleName = null, $adminId = null, $deleteUserRoleName = null;
    public $name, $email;
    public $searchAdmin = '';

    public function render()
    {
        $roles = Role::where('guard_name', 'admin')->get();

        $admins = Admin::with('roles')
            ->when($this->searchAdmin, function ($query) {
                $query->where('name', 'like', '%' . $this->searchAdmin . '%')
                    ->orWhere('email', 'like', '%' . $this->searchAdmin . '%');
            })
            ->get();

        return view('livewire.admin.admin-role', compact('roles', 'admins'));
    }

    public function openAddModal()
    {
        $this->resetValidation();
        $this->sendTestEmail();
        $this->role_name = '';
        $this->showAddModal = true;
    }

    public function createRole()
    {
        $this->validate([
            'role_name' => 'required|unique:roles,name,',
        ]);

        Role::create(['name' => $this->role_name, 'guard_name' => 'admin']);
        $this->closeAddModal();
        $this->dispatch('toast', type: 'success', title: 'Created!', message: 'New role has been created.');
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->role_name = '';
    }

    public function confirmRemoveRole($adminId, $roleName)
    {
        $this->adminId = $adminId;
        $admin = Admin::find($this->adminId);
        $this->deleteUserRoleName = $roleName;
        if ($admin->user_type === 'system-admin' && $this->deleteUserRoleName === 'super-admin') {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: "Cannot remove system admin's role.");
            return;
        }
        $this->dispatch('confirmSwal', buttonText: 'Yes, remove it!', dpText: 'confirmRemoveRole');
    }

    #[On('confirmRemoveRole')]
    public function removeAdminRole()
    {
        $userAdmin = auth('admin')->user();

        if ($userAdmin->hasRole('super-admin')) {
            $admin = Admin::find($this->adminId);

            if ($admin) {
                $admin->removeRole($this->deleteUserRoleName);
                $this->dispatch('toast', type: 'success', title: 'Removed!', message: 'Role has been removed.');
            } else {
                $this->dispatch('toast', type: 'error', title: 'Not fund!', message: 'User not found.');
                return;
            }
        } else {
            abort(403, 'Unauthorized');
        }
        $this->adminId = null;
        $this->deleteUserRoleName = null;
    }

    public function confirmDeleteRole($deleteRoleName)
    {
        $this->deleteRoleName = $deleteRoleName;
        if ($this->deleteRoleName == 'super-admin') {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: 'Cannot delete super admin role.');
            return;
        }
        $this->dispatch('confirmSwal', dpText: 'confirmDeleteRole');
    }

    #[On('confirmDeleteRole')]
    public function deleteRole()
    {
        $userAdmin = auth('admin')->user();
        if ($userAdmin->hasRole('super-admin')) {

            $role = Role::where('name', $this->deleteRoleName)->first();

            if (!$role) {
                $this->dispatch('toast', type: 'error', title: 'Not Found', message: 'Role not found.');
                return;
            }

            if ($role->users()->count() > 0) {
                $this->dispatch('toast', type: 'error', title: 'Cannot Delete', message: 'This role is assigned to users.');
                return;
            }

            $role->delete();
            $this->dispatch('toast', type: 'success', title: 'Deleted!', message: 'Role deleted successfully!');

        } else {
            abort(403, 'Unauthorized');
        }
        $this->deleteRoleName = null;
    }


    public function openAddAdminModal()
    {
        $this->resetValidation();
        $this->name = '';
        $this->email = '';
        $this->showAddAdminModal = true;
    }

    public function closeAddAdminModal()
    {
        $this->name = '';
        $this->email = '';
        $this->showAddAdminModal = false;
    }

    public function sendTestEmail()
    {
        Mail::to('abutalha5896@gmail.com')->send(new TestMail('Hello from Livewire!'));

        $this->dispatch('toast', type: 'success', title: 'Email Sent', message: 'SMTP email sent successfully!');
    }

}
