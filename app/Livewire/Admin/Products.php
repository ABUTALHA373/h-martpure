<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;

class Products extends Component
{
    use WithFileUploads;

    // Modal States
    public $showAddModal = false;
    public $showDetailModal = false;
    public $lightboxOpen = false;

    // Form Fields
    public $name = '';
    public $sku = '';
    public $category = '';
    public $price = '';
    public $stock = '';
    public $status = 'in_stock';
    public $description = '';
    public $images = []; // For file uploads
    public $persons = [
        ["roll" => 1, "name" => "John Doe", "email" => "john@example.com", "age" => 22],
        ["roll" => 2, "name" => "Emma Smith", "email" => "emma@example.com", "age" => 25],
    ];


    // Detail View
    public $selectedProduct = null;
    public $currentImageIndex = 0;

    public function render()
    {
        return view('livewire.admin.products')->layout('components.layout.admin');
    }

    // Add Modal Actions
    public function openAddModal()
    {
        $this->reset(['name', 'sku', 'category', 'price', 'stock', 'status', 'description', 'images']);
        $this->showAddModal = true;
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->reset(['name', 'sku', 'category', 'price', 'stock', 'status', 'description', 'images']);
    }

    public function removeImage($index)
    {
        array_splice($this->images, $index, 1);
    }

    public function saveProduct()
    {
        // Validation would go here
        // For now, just close the modal
        $this->closeAddModal();
    }

    public function updatedImages()
    {
        $this->validate([
            'images.*' => 'image|max:1024', // 1MB Max
            'images' => 'max:5', // Max 5 images
        ]);
    }

    // Detail Modal Actions
    public function openDetailModal($product)
    {
        $this->selectedProduct = $product;
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedProduct = null;
        $this->lightboxOpen = false;
    }

    // Lightbox Actions
    public function openLightbox($index)
    {
        $this->currentImageIndex = $index;
        $this->lightboxOpen = true;
    }

    public function closeLightbox()
    {
        $this->lightboxOpen = false;
    }

    public function nextImage()
    {
        if ($this->selectedProduct && isset($this->selectedProduct['images']) && count($this->selectedProduct['images']) > 0) {
            $this->currentImageIndex = ($this->currentImageIndex + 1) % count($this->selectedProduct['images']);
        }
    }

    public function prevImage()
    {
        if ($this->selectedProduct && isset($this->selectedProduct['images']) && count($this->selectedProduct['images']) > 0) {
            $count = count($this->selectedProduct['images']);
            $this->currentImageIndex = ($this->currentImageIndex - 1 + $count) % $count;
        }
    }
}
