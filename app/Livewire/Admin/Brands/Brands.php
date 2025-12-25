<?php

namespace App\Livewire\Admin\Brands;

use App\AdminPermission;
use App\Models\Brand;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('components.layout.admin')]
class Brands extends Component
{
    use WithFileUploads, AdminPermission;

    public $name, $code_name, $description, $logo = null, $is_active = 1, $is_featured = 0, $display_order = 999;

    public $searchText, $searchActive = '', $searchFeatured = '', $searchSort;

    public $manageBrandModal = false, $isManage = false, $newLogo = null, $brandForDelete;


    public $manageBrandId; // Store ID for editing

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

    public function openManageBrandModal()
    {
        $this->authorizeAdmin('brands.create');
        $this->reset(['name', 'code_name', 'description', 'logo', 'is_active', 'is_featured', 'display_order', 'manageBrandId', 'isManage']);
        $this->manageBrandModal = true;
    }

    public function closeManageBrandModal()
    {
        $this->manageBrandModal = false;
        $this->reset(['name', 'code_name', 'description', 'logo', 'is_active', 'is_featured', 'display_order', 'manageBrandId', 'isManage']);
    }

    public function updatedLogo(): void
    {
        $this->validate([
            'logo' => 'image|max:5000', // 5MB per file
        ]);
    }

    public function removeImage()
    {
        $this->logo = null;
    }

    public function manageBrand($id)
    {
        $this->authorizeAdmin('brands.edit');
        $brand = Brand::find($id);
        if ($brand) {
            $this->name = $brand->name;
            $this->code_name = $brand->code_name;
            $this->description = $brand->description;
            $this->logo = $brand->logo;
            $this->is_active = $brand->is_active;
            $this->is_featured = $brand->is_featured;
            $this->display_order = $brand->display_order;

            $this->isManage = true;
            $this->manageBrandModal = true;
            $this->manageBrandId = $brand->id;
        }
    }

    public function saveBrand()
    {
        if ($this->isManage) {
            $this->authorizeAdmin('brands.edit');
        } else {
            $this->authorizeAdmin('brands.create');
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'code_name' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
            'logo' => 'nullable',
        ]);

        $logoPath = $this->logo;

        // Handle Image Upload if it's a new file
        if ($this->logo instanceof TemporaryUploadedFile) {
            try {

                $uploaded = Cloudinary::uploadApi()->upload(
                    $this->logo->getRealPath(),
                    ['folder' => 'brands']
                );

                $logoPath = $uploaded['secure_url'];
            } catch (Exception $e) {
                // Fallback to local storage
                $logoPath = $this->logo->store('brands', 'public');
            }
        } elseif ($this->isManage && !empty($this->manageBrandId)) {
            // If editing and no new file uploaded, keep existing logo
            // Logic handled by polymorphism of $logo (string vs object) or pulling from DB if needed.
            // But since we set $this->logo = $brand->logo in manageBrand, checking $logoPath is enough.
            // However, if user removed image?
            // If $this->logo is null, it means no image.
        }

        $data = [
            'name' => $this->name,
            'code_name' => $this->code_name ?? Str::slug($this->name),
            'description' => $this->description,
            'logo' => $logoPath,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'display_order' => $this->display_order,
        ];

        if ($this->isManage && $this->manageBrandId) {
            $brand = Brand::find($this->manageBrandId);
            if ($brand) {
                $brand->update($data);
                $this->dispatch('toast', type: 'success', title: 'Updated!', message: 'Brand updated successfully.');
            }
        } else {
            Brand::create($data);
            $this->dispatch('toast', type: 'success', title: 'Created!', message: 'Brand created successfully.');
        }
        $this->isManage = false;
        $this->closeManageBrandModal();
    }

    public function confirmDelete(Brand $brand)
    {
        $this->authorizeAdmin('brands.delete');
        if ($brand->products->count() > 0) {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: 'Cannot delete brand because it has products.');
            return;
        }
        $this->brandForDelete = $brand;
        $this->dispatch('confirmSwal', dpText: 'confirmDeleteCategory');
    }

    #[On('confirmDeleteCategory')]
    public function deleteCategory()
    {
        if (empty($this->brandForDelete)) {
            $this->dispatch('toast', type: 'error', title: 'Not Found!', message: 'Brand not found. Please Try Again.');
        }
        if ($this->brandForDelete->delete()) {
            $this->dispatch('toast', type: 'success', title: 'Deleted!', message: 'The brand has been deleted.');
        }
        $this->brandForDelete = null;

    }
}
