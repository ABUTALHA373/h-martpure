<?php

namespace App\Livewire\Admin\Categories;

use App\AdminPermission;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Categories extends Component
{
    use AdminPermission;


    public $manageCategoryModal = false;
    public $name, $parent, $status = 1, $display_order = 0, $description;
    public $categoryForDelete = null;
    public $categoryForManage = null;
    public $isManage = false;

    public $rules = [
        'name' => 'required|string|min:3',
        'parent' => 'nullable|exists:categories,id',
        'status' => 'required',
        'display_order' => 'numeric|min:0',
    ];

    public function render()
    {
        $parentCategories = Category::select(['id', 'name'])->whereNull('parent_id')->get();
        $categories = Category::whereNull('parent_id')->with('children')->withCount('products')->get()->sortBy('name');

        return view('livewire.admin.categories.categories')
            ->with('parentCategories', $parentCategories)
            ->with('categories', $categories)
            ->layout('components.layout.admin');
    }

    public function resetCategoryModal()
    {
        $this->reset(['name', 'parent', 'display_order', 'status', 'description']);
    }

    public function openManageCategoryModal()
    {
        $this->authorizeAdmin('categories.create');
        $this->resetValidation();
        $this->resetCategoryModal();
        $this->manageCategoryModal = true;
    }

    public function closeManageCategoryModal()
    {
        $this->resetCategoryModal();
        $this->manageCategoryModal = false;
    }

    public function manageCategory(Category $category)
    {
        $this->authorizeAdmin('categories.edit');
        $this->resetValidation();
        $this->resetCategoryModal();

        $this->name = $category->name;
        $this->parent = $category->parent_id;
        $this->status = $category->status;
        $this->display_order = $category->display_order;
        $this->description = $category->description;

        $this->categoryForManage = $category;
        $this->manageCategoryModal = true;
        $this->isManage = true;
    }


    public function saveCategory()
    {
        if ($this->isManage) {
            $this->updateCategory();
        } else {
            $this->addCategory();
        }
    }

    public function addCategory()
    {
        $this->validate();

        $this->authorizeAdmin('categories.create');

        $slug = Str::slug($this->name);
        // Check slug uniqueness
        if (Category::where('slug', $slug)->exists()) {
            $this->addError('name', 'Category with this name already exists.');
            return;
        }

        $success = Category::create([
            'name' => ucwords($this->name),
            'parent_id' => $this->parent ?: null,
            'status' => $this->status,
            'slug' => $slug,
            'display_order' => $this->display_order,
            'description' => $this->description,
        ]);

        if ($success) {
            $this->dispatch('toast', type: 'success', title: 'New Category!', message: 'New Category has been created.');
        }
        $this->manageCategoryModal = false;

    }

    public function updateCategory()
    {
        $this->authorizeAdmin('categories.create');
        $this->validate();

        $slug = Str::slug($this->name);

        if (
            Category::where('slug', $slug)
                ->where('id', '!=', $this->categoryForManage->id)
                ->exists()
        ) {
            $this->addError('name', 'Category with this name already exists.');
            return;
        }

        // Update the existing category
        $success = $this->categoryForManage->update([
            'name' => ucwords($this->name),
            'parent_id' => $this->parent ?: null,
            'status' => $this->status,
            'slug' => $slug,
            'display_order' => $this->display_order,
            'description' => $this->description,
        ]);

        if ($success) {
            $this->dispatch('toast', type: 'success', title: 'Category Updated!', message: 'Category has been updated successfully.'
            );
        }

        $this->categoryForManage = null;
        $this->manageCategoryModal = false;
        $this->isManage = false;
        $this->resetCategoryModal();
    }


    public function confirmDelete(Category $category)
    {
        $this->authorizeAdmin('categories.delete');
        if ($category->children->count() > 0 || $category->products->count() > 0) {
            $this->dispatch('toast', type: 'error', title: 'Restricted!', message: 'Cannot delete category because it has subcategories or products.');
            return;
        }
        $this->categoryForDelete = $category;
        $this->dispatch('confirmSwal', dpText: 'confirmDeleteCategory');
    }

    #[On('confirmDeleteCategory')]
    public function deleteCategory()
    {
        if (empty($this->categoryForDelete)) {
            $this->dispatch('toast', type: 'error', title: 'Not Found!', message: 'Category not found. Please Try Again.');
        }
        $this->categoryForDelete->delete();
        $this->categoryForDelete = null;

    }

}
