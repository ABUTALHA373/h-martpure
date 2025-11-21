<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class Sidebar extends Component
{
    public array $sidebar_items = [
        ["Dashboard", "admin.dashboard"],
        ["Products", "admin.products"],
        ["Categories", "admin.categories"],
        ["Categories", "admin.categories"],
        ["Offers", "admin.offers"],
        ["Orders", "admin.orders"],
        ["Customers", "admin.customers"],
        ["Settings", "admin.settings"],
    ];

    public function render()
    {
        return view('livewire.layout.sidebar');
    }
}
