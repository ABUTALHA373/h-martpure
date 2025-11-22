<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

//use Livewire\TemporaryUploadedFile;

class Products extends Component
{
    use WithFileUploads;

    // Modal States
    public $showAddModal = false;
    public $showDetailModal = false;
    public $lightboxOpen = false;

    // Form Fields
    public $name, $brand, $sku, $category, $main_category, $measurement, $measurement_unit, $status = 1, $description;

    public $images = []; // Stores all valid images
    public $newImages = []; // Temporary for file input


    // Detail View
    public $selectedProduct = null;
    public $currentImageIndex = 0;
    protected $rules = [
        'name' => 'required|string|max:255',
        'brand' => 'string|max:255',
        'sku' => 'required|string|max:255|unique:products,sku',
        'category' => 'required|int',
        'measurement' => 'nullable|string|max:255',
        'measurement_unit' => 'nullable|string|max:255',
        'status' => 'required|boolean',
        'description' => 'nullable|string',
        'newImages.*' => 'image|max:4096', // 2MB per image
    ];

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.admin.products')->layout('components.layout.admin');
    }

    // Add Modal Actions

    public function openAddModal()
    {
        $this->reset(['name', 'brand', 'sku', 'category', 'main_category', 'measurement', 'measurement_unit', 'status', 'description', 'newImages', 'images']);
        $this->showAddModal = true;
    }

    public function removeImage($index)
    {
        array_splice($this->images, $index, 1);
    }

    public function saveProduct()
    {
        $this->validate();

        $storedPaths = $this->storeImages(); // stores temp files & keeps existing paths
        $storedPaths = array_slice($storedPaths, 0, 5); // ensure max 5 images

        Product::create([
            'name' => $this->name,
            'brand' => $this->brand,
            'sku' => $this->sku,
            'slug' => $this->slug(),
            'category_id' => $this->category,
            'measurement' => $this->measurement,
            'measurement_unit' => $this->measurement_unit,
            'status' => $this->status,
            'description' => $this->description,
            'images' => json_encode($storedPaths),
        ]);


        $this->closeAddModal();
    }

    private function storeImages()
    {
        $paths = [];

        // Only store images that are Livewire temporary uploads
        foreach ($this->images as $image) {
            if ($image instanceof TemporaryUploadedFile) {
                $paths[] = $image->store('products', 'public');
            } else {
                // Already stored path
                $paths[] = $image;
            }
        }

        return $paths;
    }

    public function slug()
    {
        return Str::slug("{$this->name}-{$this->measurement}-{$this->measurement_unit}");
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->reset(['name', 'brand', 'sku', 'category', 'main_category', 'measurement', 'measurement_unit', 'status', 'description', 'newImages', 'images']);
    }

    public function updatedNewImages()
    {
        $this->validate([
            'newImages.*' => 'image|max:5000', // 5MB per file
        ]);

        foreach ($this->newImages as $image) {
            if (count($this->images) < 5) {
                $this->images[] = $image; // just store Livewire temp files
            }
        }

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
