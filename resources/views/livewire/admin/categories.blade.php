<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        <div class="text-center sm:text-left">
            <h1 class="text-3xl font-bold text-text-primary">Categories</h1>
            <p class="text-text-secondary mt-1">Manage your product categories and subcategories here</p>
        </div>
        <button wire:click="openManageCategoryModal"
                class="btn btn-primary w-full sm:w-auto flex items-center justify-center gap-2 py-3 px-4 text-lg whitespace-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                <path
                    d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
            </svg>
            Add New Category
        </button>
    </div>

    {{-- Product Table --}}
    <div class="bg-bg-primary border border-custom rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-bg-secondary text-text-primary font-semibold uppercase tracking-wider border-b border-custom">
                <tr>
                    <th scope="col" class="px-6 py-2 text-text-secondary">#</th>
                    <th scope="col" class="px-6 py-2">Name</th>
                    <th scope="col" class="px-6 py-2">Slug</th>
                    <th scope="col" class="px-6 py-2">Type</th>
                    <th scope="col" class="px-6 py-2">Products Count</th>
                    <th scope="col" class="px-6 py-2">Status</th>
                    <th scope="col" class="px-6 py-2 text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-custom">
                @php foreach ($categories as $k=>$category){  @endphp
                <tr class="hover:bg-bg-secondary/50 transition-colors group">
                    <td class="w-8 pl-6 pr-3 py-2 text-text-secondary">{{$k+1}}</td>
                    <td class="px-6 py-2 text-text-primary font-bold">{{$category->name}}</td>
                    <td class="px-6 py-2 text-text-primary">{{$category->slug}}</td>
                    <td class="px-6 py-2">
                        @if(empty($category->parent_id))
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-400 border border-sky-200 dark:border-sky-800">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                                Parent
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-fuchsia-100 text-fuchsia-800 dark:bg-fuchsia-900/30 dark:text-fuchsia-400 border border-fuchsia-200 dark:border-fuchsia-800">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m16.49 12 3.75 3.75m0 0-3.75 3.75m3.75-3.75H3.74V4.499"/>
                                </svg>
                                Child
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-2 text-text-primary">N/A</td>
                    <td class="px-6 py-2">
                        @php if($category->status){ @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                            Active
                                        </span>
                        @php }else{ @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                            Inactive
                                        </span>
                        @php } @endphp
                    </td>
                    <td class="px-6 py-2 text-right">
                        <div
                            class="flex items-center justify-end gap-2 transition-opacity">
                            <button
                                class="p-1.5 text-secondary hover:bg-bg-secondary rounded-md transition-colors cursor-pointer"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                </svg>
                            </button>
                            <button
                                type="button"
                                {{--                                wire:click="confirmDelete({{ $product->id }})"--}}
                                class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors cursor-pointer outline-none"
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
                @php foreach ($category->children as $l=>$child){  @endphp
                <tr class="hover:bg-bg-secondary/50 transition-colors group">
                    <td class="w-8 pl-6 pr-3 py-2 text-text-secondary">{{$k+1 ."." . $l+1}}</td>
                    <td class="pl-12 px-6 py-2 text-text-primary font-bold align-middle">
                        <div class="flex items-center">
                            <span class="text-text-primary font-extrabold mr-1">└</span>
                            {{ $child->name }}
                        </div>
                    </td>

                    <td class="px-6 py-2 text-text-primary">{{$child->slug}}</td>
                    <td class="px-6 py-2">
                        @if(empty($child->parent_id))
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-400 border border-sky-200 dark:border-sky-800">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                                Parent
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-fuchsia-100 text-fuchsia-800 dark:bg-fuchsia-900/30 dark:text-fuchsia-400 border border-fuchsia-200 dark:border-fuchsia-800">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m16.49 12 3.75 3.75m0 0-3.75 3.75m3.75-3.75H3.74V4.499"/>
                            </svg>
                            Child
                        </span>
                        @endif
                    </td>

                    <td class="px-6 py-2 text-text-primary">N/A</td>
                    <td class="px-6 py-2">
                        @php if($child->status){ @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                                Active
                                            </span>
                        @php }else{ @endphp
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                                Inactive
                                            </span>
                        @php } @endphp
                    </td>
                    <td class="px-6 py-2 text-right">
                        <div
                            class="flex items-center justify-end gap-2 transition-opacity">
                            <button
                                class="p-1.5 text-secondary hover:bg-bg-secondary rounded-md transition-colors cursor-pointer"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                </svg>
                            </button>
                            <button
                                type="button"
                                {{--                                wire:click="confirmDelete({{ $product->id }})"--}}
                                class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors cursor-pointer outline-none"
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

                @php
                    }
                 }
                @endphp
                </tbody>
            </table>
        </div>

    </div>

    {{-- Add Category Modal --}}
    @if($manageCategoryModal)
        <x-others.modal>
            <div class="p-4 sm:p-6 border-b border-custom flex justify-between items-center">
                <h2 class="text-xl font-bold text-text-primary">Add New Category</h2>
                <button wire:click="closeManageCategoryModal"
                        class="text-text-secondary hover:text-text-primary cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 sm:p-6 space-y-6 flex-1 overflow-y-auto">
                {{-- Basic Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Category Name</label>
                        <x-others.input wire:model.defer="name" class="w-full bg-bg-secondary"
                                        placeholder="Enter category name"/>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Parent (Only for Subcategory)</label>
                        @php
                            $optionDefault = [['label' => 'None(For New Category)', 'value' => '']];
                            $optionsParents =  collect($parentCategories)->map(fn($p) =>     [
                            'label' => $p['name'],      // Visible text
                            'value' => $p['id'],      // Actual value
                                ])->toArray();
                            $options = array_merge($optionDefault,$optionsParents)
                        @endphp

                        <x-others.select
                            class="bg-bg-secondary"
                            wire:model.defer="parent"
                            placeholder="All Categories"
                            :options="$options"
                        />
                        @error('parent')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Display Order</label>
                        <x-others.input wire:model.defer="display_order" class="w-full bg-bg-secondary"
                                        type="number"
                                        placeholder="Enter display order"/>
                        @error('display_order')
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
            </div>
            <div class="p-4 sm:p-6 border-t border-custom flex justify-end gap-3">
                <button wire:click="closeManageCategoryModal" class="btn btn-tertiary ">
                    Cancel
                </button>
                <button wire:click="saveCategory($updateId)" class="btn btn-primary">
                    <svg fill="#ffffff" wire:loading wire:target="saveCategory"
                         class="animate-spin h-5 w-5 mr-2 text-white" viewBox="0 0 16 16"
                         xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path d="M8,1V2.8A5.2,5.2,0,1,1,2.8,8H1A7,7,0,1,0,8,1Z"/>
                        </g>
                    </svg>
                    Add Category
                </button>
            </div>
        </x-others.modal>
    @endif

</div>
