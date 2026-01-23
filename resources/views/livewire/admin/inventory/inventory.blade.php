<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
        <div class="text-center sm:text-left">
            <h1 class="text-3xl font-bold text-text-primary">Inventory Management</h1>
            <p class="text-text-secondary">Manage stocks, batches, and costs per product</p>
        </div>
        {{-- General Add Batch button could go here, or just stick to adding per product --}}
    </div>

    {{-- Filters & Search --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-3">
        <div class="md:col-span-4 relative">
            <x-others.input
                wire:model.live.debounce="searchText"
                class="w-full bg-bg-primary"
                type="text"
                placeholder="Name or SKU">
            </x-others.input>
        </div>

        <div class="md:col-span-2">
            <x-others.select
                wire:model.live="searchCategory"
                placeholder="Category"
                :options="$productOptions"
            />
        </div>

        <div class="md:col-span-2">
            <x-others.select
                wire:model.live="searchStatus"
                placeholder="Stock Status"
                :options="[
                    ['label' => 'All Status', 'value' => ''],
                    ['label' => 'In Stock', 'value' => 'in_stock'],
                    ['label' => 'Low Stock', 'value' => 'low_stock'],
                    ['label' => 'Out of Stock', 'value' => 'out_of_stock'],
                ]"
            />
        </div>

        <div class="md:col-span-2">
            <x-others.select
                wire:model.live="searchSort"
                placeholder="Sort By"
                :options="[
                    ['label' => 'Latest Updated', 'value' => 'latest'],
                    ['label' => 'Oldest Updated', 'value' => 'oldest'],
                    ['label' => 'Stock (High)', 'value' => 'stock_high'],
                    ['label' => 'Stock (Low)', 'value' => 'stock_low'],
                    ['label' => 'Name (A-Z)', 'value' => 'name_asc'],
                    ['label' => 'Name (Z-A)', 'value' => 'name_desc'],
                ]"
            />
        </div>
    </div>

    {{-- Main Inventory Table --}}
    <div class="bg-bg-primary border border-custom rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-bg-secondary text-text-primary font-semibold uppercase tracking-wider border-b border-custom">
                <tr>
                    <th scope="col" class="px-4 py-2 text-text-secondary">#</th>
                    <th scope="col" class="px-6 py-2">Product</th>
                    <th scope="col" class="px-6 py-2">Details</th>
                    <th scope="col" class="px-6 py-2">Quantities</th>
                    <th scope="col" class="px-6 py-2">Product Status</th>
                    <th scope="col" class="px-6 py-2">Stock Status</th>
                    <th scope="col" class="px-6 py-2 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-custom">
                @forelse ($products as $k => $product)
                    {{-- Main Product Row --}}
                    <tr class="hover:bg-bg-secondary/50 transition-colors {{ in_array($product->id, $expandedProductIds) ? 'bg-bg-secondary/30' : '' }}">
                        <td class="px-4 py-2 text-text-secondary w-12">{{ $products->firstItem() + $k }}</td>
                        <td class="px-6 py-2 max-w-[250px]">
                            <div class="flex items-center gap-4">
                                <div
                                    class="relative w-12 h-12 rounded-lg border border-custom overflow-hidden flex-shrink-0 group bg-white">
                                    <img src="{{ $product->first_image_url }}" alt=""
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <p class="text-text-primary font-bold leading-tight line-clamp-2" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-2">
                            <ul class="text-xs space-y-1">
                                <li class="flex items-center gap-2">
                                    <span class="text-text-secondary w-16">SKU:</span>
                                    <span class="font-mono text-text-primary">{{ $product->sku }}</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-text-secondary w-16">Brand:</span>
                                    <span class="text-text-primary">{{ $product->brand->name ?? '-' }}</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="text-text-secondary w-16">Category:</span>
                                    <span
                                        class="text-text-primary">{{ $product->category->name ?? '-' }}</span>
                                </li>
                            </ul>
                        </td>
                        <td class="px-6 py-2">
                            <div class="text-lg font-bold font-mono text-text-primary">
                                {{ $product->stock ?? 0 }}
                            </div>
                            <div class="text-xs text-text-secondary">Total Units</div>
                        </td>
                        <td class="px-6 py-2">
                            @if($product->status)
                                {{-- Assuming status is boolean coming from product --}}
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                    Active
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-2">
                            @if(($product->stock ?? 0) <= 0)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold bg-red-100 text-red-800 border border-red-200">
                                    Sold Out
                                </span>
                            @elseif(($product->stock ?? 0) <= 5)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    Low Stock
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold bg-green-100 text-green-800 border border-green-200">
                                    In Stock
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-2 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openAddBatchModal({{ $product->id }})"
                                        class="p-1.5 text-secondary hover:bg-bg-secondary rounded-md transition-colors cursor-pointer"
                                        title="Add New Stock Batch">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                                </button>
                                <button wire:click="toggleRow({{ $product->id }})"
                                        class="p-1.5 text-secondary hover:bg-bg-secondary rounded-md transition-colors cursor-pointer {{ in_array($product->id, $expandedProductIds) ? 'bg-bg-secondary text-primary' : '' }}"
                                        title="View Batches">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Expanded Batches Row --}}
                    @if(in_array($product->id, $expandedProductIds))
                        <tr class="bg-amber-500/10">
                            <td colspan="7" class="bg-bg-secondary/20 p-0 border-b border-custom">
                                <div class="px-8 py-4">
                                    <div
                                        class="bg-bg-primary border border-custom rounded-lg shadow-sm overflow-hidden">
                                        <table class="w-full text-xs text-left">
                                            <thead
                                                class="bg-bg-secondary text-text-secondary font-medium uppercase border-b border-custom">
                                            <tr>
                                                <th class="px-4 py-2">Batch UID</th>
                                                <th class="px-4 py-2">Purchase / Expiry</th>
                                                <th class="px-4 py-2">Stock (Rem / Init)</th>
                                                <th class="px-4 py-2">Cost (Supplier)</th>
                                                <th class="px-4 py-2">Sell Price</th>
                                                <th class="px-4 py-2">Location</th>
                                                <th class="px-4 py-2">Status</th>
                                                <th class="px-4 py-2 text-right">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="divide-y divide-custom">
                                            @forelse($product->inventories()->latest()->get() as $batch)
                                                <tr class="hover:bg-bg-secondary/50 group transition-colors">
                                                    <td class="px-4 py-2 font-mono text-text-primary font-medium">{{ $batch->batch_uid }}</td>
                                                    <td class="px-4 py-2 text-text-secondary">
                                                        <div class="flex flex-col gap-0.5">
                                                            <div><span
                                                                    class="font-medium">In:</span> {{ $batch->purchase_date ? $batch->purchase_date->format('M d, Y') : '-' }}
                                                            </div>
                                                            @if($batch->expiry_date)
                                                                <div
                                                                    class="{{ $batch->expiry_date->isPast() ? 'text-red-500 font-bold' : '' }}">
                                                                    <span
                                                                        class="font-medium">Exp:</span> {{ $batch->expiry_date->format('M d, Y') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex items-center gap-1">
                                                                <span
                                                                    class="font-bold text-sm {{ $batch->remaining_quantity == 0 ? 'text-red-500' : 'text-text-primary' }}">
                                                                    {{ $batch->remaining_quantity }}
                                                                </span>
                                                            <span
                                                                class="text-text-secondary text-[10px]">/ {{ $batch->initial_quantity }}</span>
                                                        </div>
                                                        <div
                                                            class="w-20 h-1.5 mt-1 bg-gray-200 rounded-full overflow-hidden flex"
                                                            title="Reserved: {{ $batch->reserved_quantity }} | Available: {{ max(0, $batch->remaining_quantity - $batch->reserved_quantity) }}">
                                                            @php
                                                                $total = $batch->initial_quantity > 0 ? $batch->initial_quantity : 1;
                                                                $reserved = $batch->reserved_quantity ?? 0;
                                                                $remaining = $batch->remaining_quantity ?? 0;

                                                                // Available is portion of remaining that isn't reserved
                                                                $available = max(0, $remaining - $reserved);
                                                                // If reserved > remaining (shouldn't happen), cap it
                                                                $reservedVisible = min($reserved, $remaining);

                                                                $pctReserved = ($reservedVisible / $total) * 100;
                                                                $pctAvailable = ($available / $total) * 100;
                                                            @endphp
                                                            <div class="h-full bg-yellow-400"
                                                                 style="width: {{ $pctReserved }}%"></div>
                                                            <div class="h-full bg-green-500"
                                                                 style="width: {{ $pctAvailable }}%"></div>
                                                        </div>
                                                        @if($batch->reserved_quantity > 0)
                                                            <div class="text-[9px] text-text-secondary mt-0.5">
                                                                Reserved: {{ $batch->reserved_quantity }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 text-text-secondary">{{ number_format($batch->supplier_cost, 2) }}</td>
                                                    <td class="px-4 py-2 text-text-primary font-medium">{{ number_format($batch->sell_price, 2) }}</td>
                                                    <td class="px-4 py-2 text-text-secondary">{{ $batch->store_location ?? '-' }}</td>
                                                    <td class="px-4 py-2">
                                                            <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold border
                                                                {{ $batch->status === 'active' ? 'bg-green-100 text-green-800 border-green-200' : '' }}
                                                                {{ $batch->status === 'sold_out' ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                                                                {{ $batch->status === 'inactive' ? 'bg-gray-100 text-gray-800 border-gray-200' : '' }}">
                                                                {{ str_replace('_', ' ', $batch->status) }}
                                                            </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-right">
                                                        <button wire:click="editBatch({{ $batch->id }})"
                                                                class="p-1.5 text-secondary hover:text-primary hover:bg-bg-secondary rounded transition-colors"
                                                                title="Edit Batch">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                 fill="currentColor" class="size-4">
                                                                <path
                                                                    d="m2.695 14.762-1.262 3.155a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.886L17.5 5.501a2.121 2.121 0 0 0-3-3L3.58 13.419a4 4 0 0 0-.885 1.343Z"/>
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8"
                                                        class="px-4 py-8 text-center text-text-secondary italic">
                                                        <div class="flex flex-col items-center justify-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                 viewBox="0 0 24 24" stroke-width="1.5"
                                                                 stroke="currentColor" class="size-8 mb-2 opacity-50">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                                                            </svg>
                                                            <span>No inventory records yet. Add a stock batch to get started.</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-text-secondary">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-16 mb-4 text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                                </svg>
                                <p class="text-lg font-medium">No products found matching your search.</p>
                                <p class="text-sm">Try adjusting your filters or search query.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-custom">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Manage Batch Modal --}}
    @if($manageBatchModal)
        <x-others.modal>
            <div class="p-5 sm:p-6 border-b border-custom flex justify-between items-center bg-bg-secondary">
                <h2 class="text-xl font-bold text-text-primary">{{ $isEdit ? 'Update' : 'Add New' }} Stock Batch</h2>
                <button wire:click="closeManageBatchModal"
                        class="text-text-secondary hover:text-text-primary cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-5 sm:p-6 flex-1 overflow-y-auto">
                {{-- Form Grid --}}
                <div class="space-y-6">

                    {{-- 1. Identity & Dates --}}
                    <div class="bg-bg-primary rounded-lg border border-custom p-4">
                        <h4 class="text-xs font-bold uppercase  tracking-wider mb-4 border-b border-custom pb-2">
                            Batch Identity</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="col-span-1">
                                <label class="block text-xs font-medium  mb-1">Batch UID</label>
                                <x-others.input wire:model.defer="batch_uid" class="w-full bg-bg-secondary font-mono"/>
                                @error('batch_uid') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-1">
                                <label class="block text-xs font-medium  mb-1">Purchase Date</label>
                                <x-others.input type="date" wire:model.defer="purchase_date"
                                                class="w-full bg-bg-secondary"/>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-xs font-medium  mb-1">Expiry Date</label>
                                <x-others.input type="date" wire:model.defer="expiry_date"
                                                class="w-full bg-bg-secondary"/>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Quantity --}}
                    <div class="bg-bg-primary rounded-lg border border-custom p-4">
                        <h4 class="text-xs font-bold uppercase  tracking-wider mb-4 border-b border-custom pb-2">
                            Stock Level</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium  mb-1">Initial
                                    Quantity</label>
                                <x-others.input type="number" wire:model.defer="initial_quantity"
                                                class="w-full bg-bg-secondary"/>
                                @error('initial_quantity') <span
                                    class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium  mb-1">Remaining
                                    Quantity</label>
                                <x-others.input type="number" wire:model.defer="remaining_quantity"
                                                class="w-full bg-bg-secondary"/>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Costs --}}
                    <div class="bg-bg-primary rounded-lg border border-custom p-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider mb-4 border-b border-custom pb-2">
                            Cost Analysis (Per Unit)</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs  mb-1">Supplier Cost</label>
                                <x-others.input type="number" step="0.01" wire:model.defer="supplier_cost"
                                                class="w-full bg-bg-secondary text-right" placeholder="0.00"/>
                            </div>
                            <div>
                                <label class="block text-xs  mb-1">Transport</label>
                                <x-others.input type="number" step="0.01" wire:model.defer="transport_cost"
                                                class="w-full bg-bg-secondary text-right" placeholder="0.00"/>
                            </div>
                            <div>
                                <label class="block text-xs  mb-1">Handling</label>
                                <x-others.input type="number" step="0.01" wire:model.defer="handling_cost"
                                                class="w-full bg-bg-secondary text-right" placeholder="0.00"/>
                            </div>
                            <div>
                                <label class="block text-xs  mb-1">Other</label>
                                <x-others.input type="number" step="0.01" wire:model.defer="other_cost"
                                                class="w-full bg-bg-secondary text-right" placeholder="0.00"/>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Pricing & Control --}}
                    <div class="bg-bg-primary rounded-lg border border-custom p-4">
                        <h4 class="text-xs font-bold uppercase  tracking-wider mb-4 border-b border-custom pb-2">
                            Pricing & Control</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs  mb-1">Sell Price (Override)</label>
                                <x-others.input type="number" step="0.01" wire:model.defer="sell_price"
                                                class="w-full bg-bg-secondary"/>
                            </div>
                            <div>
                                <label class="block text-xs  mb-1">Store Location</label>
                                <x-others.input type="text" wire:model.defer="store_location"
                                                class="w-full bg-bg-secondary" placeholder="e.g. Shelf A-5"/>
                            </div>
                            <div>
                                <label class="block text-xs  mb-1">Status</label>
                                <x-others.select class="w-full bg-bg-secondary" wire:model.defer="status"
                                                 :options="[
                                        ['label' => 'Active', 'value' => 'active'],
                                        ['label' => 'Sold Out', 'value' => 'sold_out'],
                                        ['label' => 'Inactive', 'value' => 'inactive'],
                                    ]"
                                />
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-xs  mb-1">Notes / Remarks</label>
                            <textarea wire:model.defer="notes" rows="2"
                                      class="w-full px-4 py-2 rounded-lg border border-custom bg-bg-secondary text-text-primary focus:outline-none focus:border-secondary text-sm"></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <div class="p-5 sm:p-6 border-t border-custom flex justify-end gap-3 bg-bg-secondary">
                <button wire:click="closeManageBatchModal" class="btn btn-tertiary">Cancel</button>
                <button wire:click="saveBatch" class="btn btn-primary shadow-lg shadow-primary/30"
                        wire:loading.attr="disabled">
                    <span wire:loading wire:target="saveBatch" class="mr-2 animate-spin">
                         <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25"
                                                                                                 cx="12" cy="12" r="10"
                                                                                                 stroke="currentColor"
                                                                                                 stroke-width="4"></circle><path
                                 class="opacity-75" fill="currentColor"
                                 d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                    {{ $isEdit ? 'Update Batch' : 'Add Batch' }}
                </button>
            </div>
        </x-others.modal>
    @endif
</div>
