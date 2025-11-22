@php
    $userClass = $attributes->get('class', '');

    // Detect any bg-* class from the user attributes
    preg_match('/\bbg-[\w-]+\b/', $userClass, $matches);
    $bgClass = $matches[0] ?? 'bg-bg-primary'; // fallback
@endphp

@props([
    'options' => [],
    'placeholder' => 'Select an option',
    'optionValue' => 'value',
    'optionLabel' => 'label',
    'dataKeys' => []
])

<div x-data="{
    open: false,
    selected: @entangle($attributes->wire('model')),
    options: {{ json_encode($options) }},
    get label() {
        if (!this.selected) return '{{ $placeholder }}';
        const option = this.options.find(o => o['{{ $optionValue }}'] == this.selected);
        return option ? option['{{ $optionLabel }}'] : '{{ $placeholder }}';
    },
    select(value) {
        this.selected = value;
        this.open = false;
    }
}" class="relative w-full">

    {{-- Trigger Button --}}
    <button @click="open = !open" @click.outside="open = false" type="button"
            class="w-full h-9 px-3 text-left rounded-md border border-custom {{ $bgClass }} text-text-primary
                   focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors
                   flex justify-between items-center cursor-pointer text-md">
        <span x-text="label"
              :class="{ 'text-text-secondary': !selected }"
              class="truncate overflow-hidden whitespace-nowrap max-w-full"
              @mouseenter="$el.title = $el.scrollWidth > $el.clientWidth ? label : ''"
              @mouseleave="$el.title = ''">
        </span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="size-4 text-text-secondary transition-transform duration-200" :class="{ 'rotate-180': open }">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 w-full mt-1 rounded-lg border border-custom {{ $bgClass }} shadow-lg max-h-60 overflow-auto py-1"
         style="display: none;">

        <template x-for="option in options" :key="option['{{ $optionValue }}']">
            <div @click="select(option['{{ $optionValue }}'])"
                 class="px-4 py-2 cursor-pointer transition-colors flex items-center justify-between group"
                 :class="{
                     'bg-secondary/15 text-secondary': selected == option['{{ $optionValue }}'],
                     'text-text-primary hover:bg-secondary/7': selected != option['{{ $optionValue }}']
                 }"
                 x-bind="{
                    @foreach($dataKeys as $key)
                        'data-{{ $key }}': option['{{ $key }}'],
                    @endforeach
                 }"
            >
                <span class="font-medium truncate overflow-hidden whitespace-nowrap max-w-[85%]"
                      x-text="option['{{ $optionLabel }}']"
                      @mouseenter="$el.title = $el.scrollWidth > $el.clientWidth ? option['{{ $optionLabel }}'] : ''"
                      @mouseleave="$el.title = ''"></span>

                {{-- Checkmark for selected item --}}
                <svg x-show="selected == option['{{ $optionValue }}']" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 20 20" fill="currentColor" class="size-4 text-secondary">
                    <path fill-rule="evenodd"
                          d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
        </template>
    </div>
</div>
