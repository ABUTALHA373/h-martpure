<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="mb-4">
        <h1 class="text-3xl font-bold text-text-primary">System Settings</h1>
        <p class="text-text-secondary mt-1">Manage and configure your application preferences</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        {{-- Sidebar Navigation --}}
        <div class="lg:col-span-3">
            <div class="bg-bg-primary border border-custom rounded-xl shadow-sm overflow-hidden sticky top-6">
                <nav class="flex flex-col p-2 space-y-1">
                    {{-- Global Dashboard / General --}}
                    <button wire:click="setTab('general')"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group cursor-pointer
                                   {{ $activeTab === 'general' ? 'bg-secondary/10 text-secondary' : 'text-text-secondary hover:bg-bg-secondary hover:text-text-primary' }}">
                        <div
                            class="{{ $activeTab === 'general' ? 'text-secondary' : 'text-text-secondary group-hover:text-text-primary' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </div>
                        <span class="capitalize tracking-wide">General</span>
                    </button>

                    {{-- Inventory --}}
                    <button wire:click="setTab('inventory')"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group cursor-pointer
                                   {{ $activeTab === 'inventory' ? 'bg-secondary/10 text-secondary' : 'text-text-secondary hover:bg-bg-secondary hover:text-text-primary' }}">
                        <div
                            class="{{ $activeTab === 'inventory' ? 'text-secondary' : 'text-text-secondary group-hover:text-text-primary' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                            </svg>
                        </div>
                        <span class="capitalize tracking-wide">Inventory</span>
                    </button>

                    {{-- Product --}}
                    <button wire:click="setTab('product')"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group cursor-pointer
                                   {{ $activeTab === 'product' ? 'bg-secondary/10 text-secondary' : 'text-text-secondary hover:bg-bg-secondary hover:text-text-primary' }}">
                        <div
                            class="{{ $activeTab === 'product' ? 'text-secondary' : 'text-text-secondary group-hover:text-text-primary' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/>
                            </svg>

                        </div>
                        <span class="capitalize tracking-wide">Product</span>
                    </button>

                    {{-- Order Settings --}}
                    <button wire:click="setTab('order')"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group cursor-pointer
                                   {{ $activeTab === 'order' ? 'bg-secondary/10 text-secondary' : 'text-text-secondary hover:bg-bg-secondary hover:text-text-primary' }}">
                        <div
                            class="{{ $activeTab === 'order' ? 'text-secondary' : 'text-text-secondary group-hover:text-text-primary' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                            </svg>
                        </div>
                        <span class="capitalize tracking-wide">Order</span>
                    </button>

                    {{-- UI / Frontend --}}
                    <button wire:click="setTab('ui')"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group cursor-pointer
                                   {{ $activeTab === 'ui' ? 'bg-secondary/10 text-secondary' : 'text-text-secondary hover:bg-bg-secondary hover:text-text-primary' }}">
                        <div
                            class="{{ $activeTab === 'ui' ? 'text-secondary' : 'text-text-secondary group-hover:text-text-primary' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
                            </svg>
                        </div>
                        <span class="capitalize tracking-wide">UI & Design</span>
                    </button>
                </nav>
            </div>
        </div>

        {{-- Main Settings Area --}}
        <div class="lg:col-span-9 space-y-4">
            <div class="bg-bg-primary border border-custom rounded-xl shadow-sm overflow-hidden">
                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-custom bg-bg-secondary flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-text-primary capitalize">{{ $activeTab }} Settings</h2>
                        <p class="text-xs text-text-secondary">Configure settings for {{ $activeTab }} module</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-6">
                        @forelse($currentSettings as $setting)
                            <div class="group">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                                    {{-- Label Side --}}
                                    <div class="md:col-span-4 pt-2">
                                        <label class="block text-sm font-semibold text-text-primary">
                                            {{ ucfirst(str_replace(['_', '.'], ' ', $setting->key)) }}
                                        </label>
                                        @if($setting->description)
                                            <p class="text-xs text-text-secondary mt-1 leading-relaxed max-w-[90%]">{{ $setting->description }}</p>
                                        @endif
                                    </div>

                                    {{-- Input Side --}}
                                    <div class="md:col-span-8">
                                        <div class="relative">
                                            @if($setting->type === 'boolean')
                                                <div class="max-w-xs">
                                                    <x-others.select
                                                        wire:model.defer="settingValues.{{ $setting->key }}"
                                                        :options="[
                                                            ['label' => 'Enabled', 'value' => '1'],
                                                            ['label' => 'Disabled', 'value' => '0'],
                                                        ]"
                                                        class="w-full bg-bg-secondary"
                                                    />
                                                </div>

                                            @elseif($setting->type === 'integer')
                                                <x-others.input
                                                    type="number"
                                                    wire:model.defer="settingValues.{{ $setting->key }}"
                                                    class="w-full bg-bg-secondary max-w-sm"
                                                />

                                            @elseif($setting->type === 'json')
                                                <div class="relative">
                                                    <textarea
                                                        wire:model.defer="settingValues.{{ $setting->key }}"
                                                        rows="4"
                                                        class="w-full px-4 py-3 rounded-lg border border-custom bg-bg-secondary text-text-primary focus:outline-none focus:border-secondary font-mono text-xs shadow-sm shadow-black/5"
                                                    ></textarea>
                                                </div>

                                            @elseif($setting->type === 'enum')
                                                <div class="max-w-xs">
                                                    @php
                                                        $enumOptions = [];
                                                        if($setting->value_options) {
                                                            $rawOptions = explode(',', $setting->value_options);
                                                            foreach($rawOptions as $opt) {
                                                                $opt = trim($opt);
                                                                $enumOptions[] = ['label' => ucfirst($opt), 'value' => $opt];
                                                            }
                                                        }
                                                    @endphp
                                                    <x-others.select
                                                        wire:model.defer="settingValues.{{ $setting->key }}"
                                                        :options="$enumOptions"
                                                        class="w-full bg-bg-secondary"
                                                        placeholder="Select Option"
                                                    />
                                                </div>

                                            @else
                                                {{-- Default String --}}
                                                <x-others.input
                                                    type="text"
                                                    wire:model.defer="settingValues.{{ $setting->key }}"
                                                    class="w-full bg-bg-secondary"
                                                />
                                            @endif

                                            @if($setting->key_note)
                                                <div
                                                    class="mt-2 text-xs text-secondary flex items-start gap-1.5 opacity-80">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                         fill="currentColor" class="size-3.5 flex-shrink-0 mt-0.5">
                                                        <path fill-rule="evenodd"
                                                              d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0ZM8.94 6.94a.75.75 0 1 1-1.061-1.061 3 3 0 1 1 2.871 5.026v.345a.75.75 0 0 1-1.5 0v-.5c0-.72.57-1.172 1.081-1.287A1.5 1.5 0 1 0 8.94 6.94ZM10 15a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                                              clip-rule="evenodd"/>
                                                    </svg>
                                                    <span>{{ $setting->key_note }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <div class="h-px bg-custom w-full mt-6"></div>
                                @endif
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 text-text-secondary">
                                <div
                                    class="w-16 h-16 bg-bg-secondary rounded-full flex items-center justify-center mb-4 text-text-secondary/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium">No settings available in this group.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Footer Action --}}
                <div class="px-6 py-4 bg-bg-secondary/50 border-t border-custom flex justify-end">
                    <button wire:click="save"
                            class="btn btn-primary"
                            wire:loading.attr="disabled">
                        <span wire:loading wire:target="save" class="animate-spin mr-2">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25"
                                                                                                    cx="12" cy="12"
                                                                                                    r="10"
                                                                                                    stroke="currentColor"
                                                                                                    stroke-width="4"></circle><path
                                    class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
