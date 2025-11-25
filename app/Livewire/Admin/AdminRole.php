<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AdminRole extends Component
{
    public function render()
    {
        return view('livewire.admin.admin-role')
            ->layout('components.layout.admin');
    }
}
