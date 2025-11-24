<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;

class Categories extends Component
{
    public $addCategoryModal = false;
    public $name, $parent, $status = 1, $display_order = 0, $description;

    public $rules = [
        'name' => 'required|string|min:3',
        'parent' => 'nullable|exists:categories,id',
        'status' => 'required',
        'display_order' => 'numeric|min:0',
    ];

    public function render()
    {
        $parentCategories = Category::select(['id', 'name'])->whereNull('parent_id')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('livewire.categories')
            ->with('parentCategories', $parentCategories)
            ->with('categories', $categories)
            ->layout('components.layout.admin');
    }

    public function openAddCategoryModal()
    {
        $this->reset(['name', 'parent', 'display_order', 'status', 'description']);
        $this->addCategoryModal = true;
    }

    public function closeAddCategoryModal()
    {
        $this->reset(['name', 'parent', 'display_order', 'status', 'description']);
        $this->addCategoryModal = false;
    }

    public function addCategory()
    {
        $this->validate();

        $admin = auth('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized');
        }

        $slug = Str::slug($this->name);
        // Check slug uniqueness
        if (Category::where('slug', $slug)->exists()) {
            $this->addError('name', 'Category with this name already exists.');
            return;
        }

        $success = Category::create([
            'name' => ucwords($this->name),
            'parent_id' => $this->parent ?? null,
            'status' => $this->status,
            'slug' => $slug,
            'display_order' => $this->display_order,
            'description' => $this->description,
        ]);

        if ($success) {
            $this->dispatch('toast', type: 'success', title: 'New Category!', message: 'New Category has been created.');
        }
        $this->addCategoryModal = false;

    }
}
