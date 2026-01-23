<?php

namespace App\Livewire\Admin\Inventory;

use App\Models\Category;
use App\Models\Inventory as InventoryModel;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layout.admin')]
class Inventory extends Component
{
    use WithPagination;

    public $searchText = '';
    public $searchCategory = '';
    public $searchStatus = ''; // Filter for stock status
    public $searchSort = 'latest'; // Default sort

    // UI State
    public $expandedProductIds = []; // To track which rows are expanded

    // Modal State
    public $manageBatchModal = false;
    public $isEdit = false;
    public $selectedProductId = null;

    // Form Fields
    public $batch_uid;
    public $purchase_date;
    public $expiry_date; // NEW
    public $initial_quantity;
    public $remaining_quantity;

    public $supplier_cost;
    public $transport_cost;
    public $handling_cost;
    public $other_cost;

    public $sell_price;
    public $minimum_sell_price;
    public $offer_price;

    public $status = 'active';
    public $sell_order = 0;
    public $store_location;
    public $notes;

    public $editingBatchId;

    public function render()
    {
        $products = Product::with(['category', 'brand'])
            ->when($this->searchText, function ($q) {
                $q->where('name', 'like', '%' . $this->searchText . '%')
                    ->orWhere('sku', 'like', '%' . $this->searchText . '%');
            })
            ->when($this->searchCategory, function ($q) {
                $q->where('category_id', $this->searchCategory);
            })
            // Status Filtering (Based on computed stock levels is tricky in Eloquent directly without subqueries or having a stock column)
            // Assuming Product has 'stock' column updated via caching, we can filter directly.
            ->when($this->searchStatus, function ($q) {
                if ($this->searchStatus === 'in_stock') {
                    $q->where('stock', '>', 5); // Example threshold
                } elseif ($this->searchStatus === 'low_stock') {
                    $q->where('stock', '>', 0)->where('stock', '<=', 5);
                } elseif ($this->searchStatus === 'out_of_stock') {
                    $q->where('stock', '<=', 0);
                }
            })
            // Sorting
            ->when($this->searchSort, function ($q) {
                if ($this->searchSort === 'latest') {
                    $q->latest();
                } elseif ($this->searchSort === 'oldest') {
                    $q->oldest();
                } elseif ($this->searchSort === 'stock_high') {
                    $q->orderBy('stock', 'desc');
                } elseif ($this->searchSort === 'stock_low') {
                    $q->orderBy('stock', 'asc');
                } elseif ($this->searchSort === 'name_asc') {
                    $q->orderBy('name', 'asc');
                } elseif ($this->searchSort === 'name_desc') {
                    $q->orderBy('name', 'desc');
                }
            })
            ->paginate(10);

        $productCategory = Category::select('id', 'name')->orderBy('name')->get()
            ->map(function ($p) {
                return ['label' => $p->name, 'value' => $p->id];
            })->toArray();

        return view('livewire.admin.inventory.inventory', [
            'products' => $products,
            'productOptions' => $productCategory
        ]);
    }

    public function toggleRow($productId)
    {
        if (in_array($productId, $this->expandedProductIds)) {
            $this->expandedProductIds = array_diff($this->expandedProductIds, [$productId]);
        } else {
            $this->expandedProductIds[] = $productId;
        }
    }

    public function openAddBatchModal($productId)
    {
        $this->resetBatchForm();
        $this->selectedProductId = $productId;
        $this->batch_uid = strtoupper(Str::random(8));
        $this->purchase_date = date('Y-m-d');
        $this->isEdit = false;
        $this->manageBatchModal = true;
    }

    public function editBatch($batchId)
    {
        $batch = InventoryModel::find($batchId);
        if (!$batch) return;

        $this->selectedProductId = $batch->product_id;
        $this->editingBatchId = $batch->id;
        $this->batch_uid = $batch->batch_uid;
        $this->purchase_date = $batch->purchase_date ? $batch->purchase_date->format('Y-m-d') : null;
        $this->expiry_date = $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : null; // NEW

        $this->initial_quantity = $batch->initial_quantity;
        $this->remaining_quantity = $batch->remaining_quantity;

        $this->supplier_cost = $batch->supplier_cost;
        $this->transport_cost = $batch->transport_cost;
        $this->handling_cost = $batch->handling_cost;
        $this->other_cost = $batch->other_cost;

        $this->sell_price = $batch->sell_price;
        $this->minimum_sell_price = $batch->minimum_sell_price;
        $this->offer_price = $batch->offer_price;

        $this->status = $batch->status;
        $this->sell_order = $batch->sell_order;
        $this->store_location = $batch->store_location;
        $this->notes = $batch->notes;

        $this->isEdit = true;
        $this->manageBatchModal = true;
    }

    public function saveBatch()
    {
        $this->validate([
            'batch_uid' => 'required|unique:inventories,batch_uid,' . $this->editingBatchId,
            'initial_quantity' => 'required|integer|min:1',
            'remaining_quantity' => 'required|integer|min:0|max:' . $this->initial_quantity,
            'status' => 'required|in:active,sold_out,inactive',
        ]);

        $data = [
            'product_id' => $this->selectedProductId,
            'batch_uid' => $this->batch_uid,
            'purchase_date' => $this->purchase_date,
            'expiry_date' => $this->expiry_date, // NEW
            'initial_quantity' => $this->initial_quantity,
            'remaining_quantity' => $this->remaining_quantity,
            'reserved_quantity' => 0, // Default 0 for now

            'supplier_cost' => $this->supplier_cost,
            'transport_cost' => $this->transport_cost,
            'handling_cost' => $this->handling_cost,
            'other_cost' => $this->other_cost,

            'sell_price' => $this->sell_price,
            'minimum_sell_price' => $this->minimum_sell_price,
            'offer_price' => $this->offer_price,

            'status' => $this->status,
            'sell_order' => $this->sell_order ?? 0,
            'store_location' => $this->store_location,
            'notes' => $this->notes,
        ];

        if ($this->isEdit && $this->editingBatchId) {
            $batch = InventoryModel::find($this->editingBatchId);
            $batch->update($data);
            $this->dispatch('toast', type: 'success', title: 'Updated', message: 'Batch updated successfully.');
        } else {
            InventoryModel::create($data);
            $this->dispatch('toast', type: 'success', title: 'Created', message: 'New batch added successfully.');
        }

        // Update Product Stock Cache
        $this->updateProductStock($this->selectedProductId);

        $this->manageBatchModal = false;
        $this->resetBatchForm();
    }

    public function updateProductStock($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $total = $product->inventories()
                ->whereIn('status', ['active', 'sold_out'])
                ->sum('remaining_quantity');

            $product->stock = $total;
            $product->save();
        }
    }

    public function resetBatchForm()
    {
        $this->reset([
            'batch_uid', 'purchase_date', 'expiry_date', 'initial_quantity', 'remaining_quantity',
            'supplier_cost', 'transport_cost', 'handling_cost', 'other_cost',
            'sell_price', 'minimum_sell_price', 'offer_price',
            'status', 'sell_order', 'store_location', 'notes',
            'editingBatchId', 'isEdit', 'selectedProductId'
        ]);
        $this->status = 'active'; // Reset default
    }

    public function closeManageBatchModal()
    {
        $this->manageBatchModal = false;
        $this->resetBatchForm();
    }
}
