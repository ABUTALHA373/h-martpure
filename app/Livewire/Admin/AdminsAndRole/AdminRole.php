<?php

namespace App\Livewire\Admin\AdminsAndRole;

use App\AdminPermission;
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
    use AdminPermission;

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
        $roles = [];
        $admins = [];
        if ($this->canAdmin('role-permission.view-role')) {
            $roles = Role::where('guard_name', 'admin')->get();
        }
        if ($this->canAdmin('admins.view')) {
            $admins = Admin::with('roles')->get();
        }

        return view('livewire.admin.admins-and-role.admin-role', compact('roles', 'admins'));
    }

    public function openAddModal()
    {
        $this->authorizeAdmin('role-permission.create-role');
        $this->resetValidation();
        $this->role_name = '';
        $this->showAddModal = true;
    }

    public function createRole()
    {
        $this->authorizeAdmin('role-permission.create-role');
        $this->validate([
            'role_name' => 'required|unique:roles,name,',
        ]);

        $role = Role::create(['name' => $this->role_name, 'guard_name' => 'admin']);
        if ($role) {
            admin_log('created', [
                'model' => Role::class,
                'model_id' => $role->id,
                'previous' => null,
                'new' => [
                    'role_name' => $role->name,
                ],
                'changes' => [
                    'role_name' => $role->name,
                ],
            ]);
            $this->closeAddModal();
            $this->dispatch('toast', type: 'success', title: 'Created!', message: 'New role has been created.');
        } else {
            $this->dispatch('toast', type: 'error', title: 'Error!', message: 'Failed to create role.');
        }
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->role_name = '';
    }

    public function confirmRemoveRole($adminId, $roleName)
    {
        $this->authorizeAdmin('admins.update-status');

        $this->adminId = $adminId;
        $admin = Admin::find($this->adminId);
        if (auth('admin')->user()->id == $admin->id) {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: 'Cannot remove own admin role.');
            return;
        }
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
            $previousRoles = $admin->roles->pluck('name')->toArray();
            if ($admin) {
                $admin->removeRole([$this->deleteUserRoleName]);
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
                        'removed_role' => $this->deleteUserRoleName,
                    ],
                ]);
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
        $this->authorizeAdmin('role-permission.delete-role');

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

        $this->authorizeAdmin('role-permission.delete-role');

        $role = Role::where('name', $this->deleteRoleName)->first();

        if (!$role) {
            $this->dispatch('toast', type: 'error', title: 'Not Found', message: 'Role not found.');
            return;
        }

        if ($role->users()->count() > 0) {
            $this->dispatch('toast', type: 'error', title: 'Cannot Delete', message: 'This role is assigned to users.');
            return;
        }

        admin_log('deleted', [
            'model' => Role::class,
            'model_id' => $role->id,
            'previous' => [
                'role_name' => $role->name
            ],
            'new' => [],
            'changes' => [
                'role_name' => $role->name
            ]
        ]);
        $role->delete();
        $this->dispatch('toast', type: 'success', title: 'Deleted!', message: 'Role deleted successfully!');


        $this->deleteRoleName = null;
    }


    public function openAddAdminModal()
    {

        $this->authorizeAdmin('admins.create');

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
        $this->authorizeAdmin('admins.create');

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
