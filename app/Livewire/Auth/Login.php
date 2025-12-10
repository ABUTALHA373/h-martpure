<?php

namespace App\Livewire\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $admin = Admin::where('email', $this->email)->first();

        if (!$admin) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if ($admin->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact administrator.'],
            ]);
        }

        if (Auth::guard('admin')->attempt([
            'email' => $this->email,
            'password' => $this->password
        ])) {

            session()->regenerate();
            return $this->redirect(route('admin.dashboard'), navigate: true);

        }
        throw ValidationException::withMessages([
            'email' => ['These credentials do not match our records.'],
        ]);
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layout.simple');
    }
}
