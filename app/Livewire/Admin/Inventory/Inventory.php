<?php

namespace App\Livewire\Admin\Inventory;

use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('components.layout.admin')]
class Inventory extends Component
{
    public function render()
    {
        return view('livewire.admin.inventory.inventory');
    }
}
