<div class="p-2 py-4 flex flex-col gap-1 relative">
    {{--arrow collapse--}}
    {{--    <div class="absolute top-3 -right-3 bg-green-600 rounded-full cursor-pointer">--}}
    {{--        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6">--}}
    {{--            <path fill-rule="evenodd"--}}
    {{--                  d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"--}}
    {{--                  clip-rule="evenodd"/>--}}
    {{--        </svg>--}}
    {{--    </div>--}}
    @php
        $nav_cl_a = "flex items-center gap-3 px-2 py-2 rounded-lg bg-secondary/25 ";
        $nav_cl = "flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-secondary/15 ";
    @endphp
    <a wire:navigate
       href="{{ route('admin.dashboard') }}"
       class="{{ request()->routeIs('admin.dashboard') ? $nav_cl_a : $nav_cl}}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v2.25A2.25 2.25 0 0 0 6 10.5Zm0 9.75h2.25A2.25 2.25 0 0 0 10.5 18v-2.25a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25V18A2.25 2.25 0 0 0 6 20.25Zm9.75-9.75H18a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 18 3.75h-2.25A2.25 2.25 0 0 0 13.5 6v2.25a2.25 2.25 0 0 0 2.25 2.25Z"/>
        </svg>
        Dashboard
    </a>
    <a wire:navigate
       href="{{ route('admin.products') }}"
       class="{{ request()->routeIs('admin.products') ? $nav_cl_a : $nav_cl}}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
        </svg>
        Products
    </a>

</div>
