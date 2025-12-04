<?php

namespace App\Livewire\Admin;

use App\Mail\SendMail;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Throwable;

#[Layout('components.layout.admin')]
class AdminRole extends Component
{
    public $role_name;
    public $showAddModal = false;
    public $showAddAdminModal = false;
    public $showStatusModal = false;
    public $deleteRoleName = null, $adminId = null, $deleteUserRoleName = null;
    public $name, $email, $password;
    public $statusAdminId, $updateStatus;

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:admins,email',
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[a-zA-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/',
        ],
    ];
    protected $messages = [
        'password.regex' => 'Password must include letters, numbers, and special characters.',
    ];

    public function render()
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $admins = Admin::with('roles')
            ->get();

        return view('livewire.admin.admin-role', compact('roles', 'admins'));
    }

    public function openAddModal()
    {
        $this->resetValidation();
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
        $this->password = '';
        $this->showAddAdminModal = true;
    }

    public function closeAddAdminModal()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->showAddAdminModal = false;
    }

    public function createAdminUser()
    {
        $this->validate();

        $adminName = $this->name;
        $adminEmail = $this->email;
        $adminPassword = $this->password;

        $admin = auth('admin')->user()->hasRole('super-admin');
        if (!$admin) {
            abort(403, 'Unauthorized');
        }
        $createdAdmin = Admin::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => bcrypt($adminPassword),
            'user_type' => 'admin',
            'status' => 'active',
        ]);
        if (!$createdAdmin) {
            $this->dispatch('toast', type: 'error', title: 'Error!', message: 'Unable to create admin user.');
            return;
        }
        $this->closeAddAdminModal();
        $this->dispatch('toast', type: 'success', title: 'Created!', message: 'New admin user has been created.');

        $this->sendNewAdminsCreds($adminEmail, $adminPassword);
    }

    public function sendNewAdminsCreds($email, $password)
    {
        try {

            Mail::to($email)->send(new SendMail($email, $password, 'Your Account Details'));

            // Success toast
            $this->dispatch('toast',
                type: 'success',
                title: 'Password Sent!',
                message: 'Password has been sent to ' . $email . '!'
            );

        } catch (Throwable $e) {

            // Error toast
            $this->dispatch('toast',
                type: 'error',
                title: 'Email Failed!',
                message: 'Unable to send email to ' . $email . '. Please check SMTP configuration.'
            );
        }
    }

    public function openStatusModal($id, $status)
    {
        $this->statusAdminId = $id;
        $this->updateStatus = $status;
        $this->showStatusModal = true;
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->statusAdminId = null;
        $this->updateStatus = null;
    }

    public function updateAdminStatus()
    {
        $admin = Admin::find($this->statusAdminId);
        if (!$admin) {
            $this->dispatch('toast', type: 'error', title: 'Error!', message: 'Admin not found.');
            return;
        }

        if ($admin->user_type === 'system-admin') {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: 'Cannot change system admin status.');
            return;
        }

        $admin->status = $this->updateStatus;
        $admin->save();

        $this->closeStatusModal();
        $this->dispatch('toast', type: 'success', title: 'Updated!', message: 'Admin status updated successfully.');
    }


}
