<?php

namespace App\Livewire\Admin\Brands;

use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layout.admin')]
class Brands extends Component
{
    public $name, $code_name, $description, $logo, $is_active, $is_featured, $display_order;

    public $searchText, $searchActive = '', $searchFeatured = '', $searchSort;

    public function render()
    {
        $brands = Brand::withCount('products')
            ->when($this->searchText, function ($query) {
                $query->where('name', 'like', '%' . $this->searchText . '%');
            })
            ->when($this->searchActive !== '' && $this->searchActive !== null, function ($q) {
                $q->where('is_active', $this->searchActive);
            })
            ->when($this->searchFeatured !== '' && $this->searchFeatured !== null, function ($q) {
                $q->where('is_featured', $this->searchFeatured);
            })
            ->when($this->searchSort, function ($q) {
                if ($this->searchSort === 'latest') {
                    $q->orderBy('created_at', 'desc');
                } elseif ($this->searchSort === 'oldest') {
                    $q->orderBy('created_at', 'asc');
                } elseif ($this->searchSort === 'order_desc') {
                    $q->orderBy('display_order', 'desc');
                } elseif ($this->searchSort === 'az') {
                    $q->orderBy('name', 'asc');
                } elseif ($this->searchSort === 'za') {
                    $q->orderBy('name', 'desc');
                } elseif ($this->searchSort === 'order_asc') {
                    $q->orderBy('display_order', 'asc');
                }
            })
            ->paginate(20);
        return view('livewire.admin.brands.brands', compact('brands'));
    }
}
