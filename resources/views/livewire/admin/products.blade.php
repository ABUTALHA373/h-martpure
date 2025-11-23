<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-text-primary">Products</h1>
            <p class="text-text-secondary mt-1">Manage your product inventory and catalog</p>
        </div>
        <button wire:click="openAddModal" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                <path
                    d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
            </svg>
            Add New Product
        </button>
    </div>

    {{-- Filters & Search --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6">
        {{-- Search --}}
        <div class="md:col-span-5 relative">
            <x-others.input
                wire:model.live.debounce="searchText"
                class="w-full bg-bg-primary"
                type="text"
                placeholder="Search products by name, SKU...">
            </x-others.input>
        </div>
        {{-- Category Filter --}}
        <div class="md:col-span-3">
            <x-others.select
                wire:model.live="searchCategory"
                placeholder="All Categories"
                :options="[
                    ['label' => 'Electronics', 'value' => 1],
                    ['label' => 'Clothing', 'value' => 2],
                    ['label' => 'Home & Garden', 'value' => 3]
                ]"
            />
        </div>

        {{-- Stock Status Filter --}}
        <div class="md:col-span-2">
            <x-others.select
                wire:model.live="searchStatus"
                placeholder="All Status"
                :options="[
                    ['label' => 'Active', 'value' => '1'],
                    ['label' => 'Inactive', 'value' => '0'],
                ]"
            />

        </div>

        {{-- Sort --}}
        <div class="md:col-span-2">
            {{--            @php--}}
            {{--                $options = collect($persons)->map(fn($p) =>     [--}}
            {{--            'label' => $p['name'],      // Visible text--}}
            {{--            'value' => $p['roll'],      // Actual value--}}
            {{--                ])->toArray();--}}
            {{--            @endphp--}}


            <x-others.select
                wire:model.live="searchSort"
                placeholder="Sort By"
                :options="[
                    ['label' => 'Latest', 'value' => 'latest'],
                    ['label' => 'Oldest', 'value' => 'oldest'],
                    ['label' => 'Stock High → Low', 'value' => 'stock_high'],
                    ['label' => 'Stock Low → High', 'value' => 'stock_low']
                ]"
            />
        </div>
    </div>

    {{-- Product Table --}}
    <div class="bg-bg-primary border border-custom rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-bg-secondary text-text-primary font-semibold uppercase tracking-wider border-b border-custom">
                <tr>
                    <th scope="col" class="px-6 py-4">Product</th>
                    <th scope="col" class="px-6 py-4">Slug</th>
                    <th scope="col" class="px-6 py-4">Brand</th>
                    <th scope="col" class="px-6 py-4">Category</th>
                    <th scope="col" class="px-6 py-4">Measurement</th>
                    <th scope="col" class="px-6 py-4">Sales Count</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-custom">
                @php foreach ($products as $k=>$product){  @endphp
                <tr class="hover:bg-bg-secondary/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-12 w-12 rounded-lg bg-gray-200 flex-shrink-0 overflow-hidden border border-custom">
                                @php
                                    $images = json_decode($product->images, true) ?? [];
                                    $firstImage = $images[0] ?? 'default-product-image.svg';
                                @endphp

                                <img src="{{ asset('storage/' . $firstImage) }}" alt="Product Image"
                                     class="h-full w-full object-cover">


                            </div>
                            <div>
                                <div class="font-medium text-text-primary">{{$product->name}}</div>
                                <div class="text-xs text-text-secondary">{{$product->sku}}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-text-secondary">{{$product->slug}}</td>
                    <td class="px-6 py-4 text-text-secondary">{{$product->brand}}</td>
                    <td class="px-6 py-4 text-text-secondary">{{$product->category->name}}</td>
                    <td class="px-6 py-4 text-text-secondary">{{$product->measurement . " ". $product->measurement_unit}}</td>
                    <td class="px-6 py-4 text-text-secondary">{{$product->sales_count}}</td>
                    <td class="px-6 py-4">
                        @php if($product->status){ @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                            Active
                        </span>
                        @php }else{ @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                            Disabled
                        </span>
                        @php } @endphp
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div
                            class="flex items-center justify-end gap-2 transition-opacity">
                            <button
                                class="p-1.5 text-text-secondary hover:text-secondary hover:bg-bg-secondary rounded-md transition-colors cursor-pointer"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                </svg>
                            </button>
                            <button class="btn btn-success"
                                    onclick="showToast('success', 'Success!', 'Operation completed successfullyyyyy')">
                                Success
                            </button>
                            <button
                                type="button"
                                wire:click="confirmDelete({{ $product->id }})"
                                class="p-1.5 text-text-secondary hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors cursor-pointer outline-none"
                                title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @php } @endphp
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-custom ">
            {{ $products->links() }}

        </div>
    </div>

    {{-- Add Product Modal --}}
    @if($showAddModal)
        <x-others.modal>
            <div class="p-6 border-b border-custom flex justify-between items-center">
                <h2 class="text-xl font-bold text-text-primary">Add New Product</h2>
                <button wire:click="closeAddModal" class="text-text-secondary hover:text-text-primary cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-6 flex-1 overflow-y-auto">
                {{-- Basic Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Product Name</label>
                        <x-others.input wire:model.defer="name" class="w-full bg-bg-secondary"
                                        placeholder="Enter product name"/>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Brand</label>
                        <x-others.input wire:model.defer="brand" class="w-full bg-bg-secondary"
                                        placeholder="Enter Brand"/>
                        @error('brand')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">SKU</label>
                        <x-others.input wire:model.defer="sku" class="w-full bg-bg-secondary" placeholder="Enter SKU"/>
                        @error('sku')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Category</label>
                        <x-others.select
                            class="bg-bg-secondary"
                            wire:model.defer="main_category"
                            placeholder="Select Category"
                            :options="[
                                ['label' => 'Electronics', 'value' => 'electronics'],
                                ['label' => 'Food Item', 'value' => 'food']
                            ]"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Sub Category</label>
                        <x-others.select
                            class="bg-bg-secondary"
                            wire:model.defer="category"
                            placeholder="Select Category"
                            :options="[
                                ['label' => 'Mobile', 'value' => 1],
                                ['label' => 'Rice', 'value' => 2]
                            ]"
                        />
                        @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Measurement</label>
                        <x-others.input wire:model.defer="measurement" type="number" class="w-full bg-bg-secondary"
                                        placeholder="e.g. 1, 500, 10"/>
                        @error('measurement')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Measurement Unit</label>
                        <x-others.select
                            class="bg-bg-secondary w-full"
                            wire:model.defer="measurement_unit"
                            placeholder="Select Unit"
                            :options="[
                                ['label' => 'Kilogram', 'value' => 'kg'],
                                ['label' => 'Gram', 'value' => 'gm'],
                                ['label' => 'Liter', 'value' => 'ltr'],
                                ['label' => 'Piece', 'value' => 'pcs'],
                                ['label' => 'Dozen', 'value' => 'dozen'],
                                ['label' => 'Pack', 'value' => 'pack'],
                                ['label' => 'Box', 'value' => 'box'],
                                ['label' => 'Set', 'value' => 'set']
                            ]"
                        />
                        @error('measurement_unit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <x-others.select
                            class="bg-bg-secondary"
                            wire:model.defer="status"
                            placeholder="Select Status"
                            :options="[
                                ['label' => 'Active', 'value' => 1],
                                ['label' => 'Inactive', 'value' => 0]
                            ]"
                        />
                        @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea wire:model.defer="description" rows="4"
                                  class="w-full px-4 py-2 rounded-lg border border-custom bg-bg-secondary text-text-primary focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary placeholder-text-secondary"></textarea>
                    </div>
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Product Images (Max 5)</label>
                    <div
                        class="border-2 border-dashed border-custom rounded-lg p-8 text-center hover:bg-bg-secondary transition-colors relative cursor-pointer">
                        <input type="file" wire:model="newImages" multiple accept="image/*"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="text-text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-10 mx-auto mb-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                            <p>Click or drag to upload images</p>
                        </div>
                    </div>

                    {{-- Previews --}}
                    @if($uploadedImages)
                        <div class="grid grid-cols-5 gap-4 mt-4">
                            @foreach($uploadedImages as $index => $img)
                                <div
                                    class="relative aspect-square rounded-lg overflow-hidden border border-custom group">
                                    <img src="{{ is_string($img) ? $img : $img->temporaryUrl() }}"
                                         class="w-full h-full object-cover">
                                    <button wire:click="removeImage({{ $index }})"
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             class="size-3">
                                            <path
                                                d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="p-6 border-t border-custom flex justify-end gap-3">
                <button wire:click="closeAddModal" class="btn btn-tertiary ">
                    Cancel
                </button>
                <button wire:click="saveProduct" class="btn btn-primary">
                    <svg fill="#ffffff" wire:loading wire:target="saveProduct"
                         class="animate-spin h-5 w-5 mr-2 text-white" viewBox="0 0 16 16"
                         xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path d="M8,1V2.8A5.2,5.2,0,1,1,2.8,8H1A7,7,0,1,0,8,1Z"/>
                        </g>
                    </svg>
                    Save Product
                </button>
            </div>
        </x-others.modal>
    @endif

    {{-- Product Detail Modal --}}
    @if($showDetailModal && $selectedProduct)

        <x-others.modal>
            <div class="p-6 border-b border-custom flex justify-between items-center">
                <h2 class="text-xl font-bold text-text-primary">{{ $selectedProduct['name'] }}</h2>
                <button wire:click="closeDetailModal"
                        class="text-text-secondary hover:text-text-primary cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8 flex-1 overflow-y-auto">
                {{-- Image Gallery --}}
                <div class="space-y-4">
                    <div class="aspect-square rounded-xl overflow-hidden border border-custom bg-gray-100">
                        <img src="{{ $selectedProduct['images'][0] }}"
                             class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity"
                             wire:click="openLightbox(0)">
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($selectedProduct['images'] as $index => $img)
                            <div
                                class="aspect-square rounded-lg overflow-hidden border border-custom cursor-pointer hover:ring-2 hover:ring-secondary transition-all"
                                wire:click="openLightbox({{ $index }})">
                                <img src="{{ $img }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Details --}}
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-bg-secondary text-text-secondary border border-custom">{{ $selectedProduct['category'] }}</span>
                            <span
                                class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">{{ $selectedProduct['status'] }}</span>
                        </div>
                        <h3 class="text-2xl font-bold text-text-primary mb-1">{{ $selectedProduct['price'] }}</h3>
                        <p class="text-sm text-text-secondary">SKU: <span>{{ $selectedProduct['sku'] }}</span></p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-text-primary mb-2">Description</h4>
                        <p class="text-text-secondary leading-relaxed">{{ $selectedProduct['description'] }}</p>
                    </div>

                    <div class="pt-6 border-t border-custom">
                        <div class="flex justify-between items-center">
                            <span class="text-text-secondary">Stock Quantity</span>
                            <span class="font-medium text-text-primary">{{ $selectedProduct['stock'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-others.modal>
    @endif

    {{-- Lightbox --}}
    @if($lightboxOpen && $selectedProduct)
        <div class="fixed inset-0 z-[60] bg-black/95 flex items-center justify-center">

            <button wire:click="closeLightbox"
                    class="absolute top-4 right-4 text-white/70 hover:text-white p-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>

            <button wire:click="prevImage" class="absolute left-4 text-white/70 hover:text-white p-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </button>

            <div class="max-w-5xl max-h-[85vh] p-4">
                <img src="{{ $selectedProduct['images'][$currentImageIndex] }}"
                     class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>

            <button wire:click="nextImage" class="absolute right-4 text-white/70 hover:text-white p-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </button>

            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/70 text-sm">
                <span>{{ $currentImageIndex + 1 }}</span> / <span>{{ count($selectedProduct['images']) }}</span>
            </div>
        </div>
    @endif

</div>
