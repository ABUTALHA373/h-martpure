<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class ThemeToggle extends Component
{
    public bool $darkMode = false;

    public function mount()
    {
        // Livewire will be synced by Alpine x-init
    }

    public function toggleTheme(): void
    {
        $this->darkMode = !$this->darkMode;

        $this->dispatch('theme-toggled', darkMode: $this->darkMode);
    }

    public function render()
    {
        return view('livewire.layout.theme-toggle');
    }
}

