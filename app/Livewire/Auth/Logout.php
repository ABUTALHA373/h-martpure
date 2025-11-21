<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        Auth::guard('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return $this->redirect(route('admin.login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.logout');
    }
}
