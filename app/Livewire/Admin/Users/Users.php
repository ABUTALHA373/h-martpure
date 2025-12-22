<?php

namespace App\Livewire\Admin\Users;

use App\AdminPermission;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layout.admin')]
class Users extends Component
{
    use AdminPermission;

    public $searchText, $searchStatus, $searchSort;

    public $showStatusModal = false;
    public $updateStatus, $statusUserId;

    public function render()
    {
        $users = User::when($this->searchText, function ($q) {
            $q->where('name', 'like', "%{$this->searchText}%")
                ->orWhere('email', 'like', "%{$this->searchText}%");
        })
            // stock status filter
            ->when($this->searchStatus !== '' && $this->searchStatus !== null, function ($q) {
                $q->where('status', $this->searchStatus);
            })

            // sorting
            ->when($this->searchSort, function ($q) {
                if ($this->searchSort === 'latest') {
                    $q->orderBy('created_at', 'desc');
                } elseif ($this->searchSort === 'oldest') {
                    $q->orderBy('created_at', 'asc');
                } elseif ($this->searchSort === 'az') {
                    $q->orderBy('name', 'asc');
                } elseif ($this->searchSort === 'za') {
                    $q->orderBy('name', 'desc');
                }
            })
            ->paginate(20);
        return view('livewire.admin.users.users')->with('users', $users);
    }

    public function openStatusModal($id, $status)
    {
        $this->statusUserId = $id;
        $this->updateStatus = $status;
        $this->showStatusModal = true;
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->statusUserId = null;
        $this->updateStatus = null;
    }

    public function updateUserStatus()
    {
        $this->authorizeAdmin('users.update-status');
        $user = User::find($this->statusUserId);
        if (!$user) {
            $this->dispatch('toast', type: 'error', title: 'Error!', message: 'Admin not found.');
            return;
        }
        $user->status = $this->updateStatus;
        $user->save();

        $this->closeStatusModal();
        $this->dispatch('toast', type: 'success', title: 'Updated!', message: 'Admin status updated successfully.');
    }


}
