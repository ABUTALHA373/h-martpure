<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

//use Livewire\TemporaryUploadedFile;

class Products extends Component
{
    use WithFileUploads, WithPagination;

    // Modal States
    public $showAddModal = false;
    public $showDetailModal = false;
    public $lightboxOpen = false;

    // Form Fields
    public $name, $brand, $sku, $category, $main_category, $measurement, $measurement_unit, $status = 1, $description;

    // product filters
    public $searchText, $searchCategory, $searchStatus, $searchSort;

    public $uploadedImages = []; // Stores all valid images
    public $newImages = []; // Temporary for file input


    // Detail View
    public $selectedProduct = null;
    public $currentImageIndex = 0;
    public $deleteId = null;
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

    public function render()
    {
        $products = Product::query()->with('category')

            // search by name or SKU
            ->when($this->searchText, function ($q) {
                $q->where('name', 'like', "%{$this->searchText}%")
                    ->orWhere('sku', 'like', "%{$this->searchText}%");
            })

            // category filter (you will replace it with real category IDs later)
            ->when($this->searchCategory, fn($q) => $q->where('category_id', $this->searchCategory))

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
                } elseif ($this->searchSort === 'stock_high') {
                    $q->orderBy('stock', 'desc');
                } elseif ($this->searchSort === 'stock_low') {
                    $q->orderBy('stock', 'asc');
                }
            })
            ->paginate(5);

        return view('livewire.admin.products', compact('products'))
            ->layout('components.layout.admin');
    }


    // Add Modal Actions

    public function openAddModal()
    {
        $this->reset(['name', 'brand', 'sku', 'category', 'main_category', 'measurement', 'measurement_unit', 'status', 'description', 'newImages']);
        $this->uploadedImages = [];
        $this->showAddModal = true;
    }

    public function removeImage($index)
    {
        array_splice($this->uploadedImages, $index, 1);
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
        foreach ($this->uploadedImages as $image) {
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
        $this->reset(['name', 'brand', 'sku', 'category', 'main_category', 'measurement', 'measurement_unit', 'status', 'description', 'newImages', 'uploadedImages']);
    }

    public function updatedNewImages()
    {
        $this->validate([
            'newImages.*' => 'image|max:5000', // 5MB per file
        ]);

        foreach ($this->newImages as $image) {
            if (count($this->uploadedImages) < 5) {
                $this->uploadedImages[] = $image; // just store Livewire temp files
            }
        }

        $this->reset('newImages');
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

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('show-delete-confirmation');
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed()
    {
        $admin = auth('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized');
        }

        $product = Product::find($this->deleteId);

        if ($product) {
            $product->delete();
            $this->dispatch('toast', type: 'success', title: 'Deleted!', message: 'Product has been deleted.');
        }

        $this->deleteId = null;
    }

}
