<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-partials.head></x-partials.head>
<body
    class="h-[100dvh] text-black  dark:text-white transition-colors duration-300">

<div class="grid md:grid-cols-[12rem_auto] lg:grid-cols-[16rem_auto] h-full">
    <div class="hidden md:block bg-bg-primary border-r border-custom" id="sideBar">
        <div class="h-8 p-2 flex justify-end md:h-16 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                 stroke="currentColor" class="md:hidden size-5 text-secondary hover:bg-bg-secondary" id="sideBarClose">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </div>
        <div
            class="p-2 flex m-2 md:hidden flex-col gap-1 text-center` rounded-md border border-custom text-sm font-semibold uppercase">
            <span class="leading-3.5">{{auth('admin')->user()->name}}</span>
            <span class="text-[0.6rem] font-light text-secondary leading-2">{{auth('admin')->user()->user_type}}</span>
        </div>
        <livewire:layout.sidebar/>
    </div>
    <!-- Content overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black/20 bg-opacity-50 hidden z-40"></div>

    <div class="grid grid-rows-[4rem_1fr] h-full overflow-hidden">
        <div class=" bg-bg-primary border-b border-custom">
            <livewire:layout.navbar/>
        </div>
        {{-- Sidebar + Content --}}

        <div class="bg-bg-secondary p-8 text-sm overflow-y-auto h-full" id="mainContent">
            {{ $slot }}
        </div>
    </div>

</div>

@livewireScripts
<script>
    document.addEventListener('livewire:navigated', () => {
        const sideBar = document.getElementById('sideBar');
        const sideBarOpen = document.getElementById('sideBarOpen');
        const sideBarClose = document.getElementById('sideBarClose');
        const overlay = document.getElementById('overlay');

        // Function to open sidebar
        function openSidebar() {
            sideBar.classList.remove('hidden');
            sideBar.classList.add('absolute', 'top-0', 'bottom-0', 'left-0', 'w-48', 'z-50');
            overlay.classList.remove('hidden');
        }

        // Function to close sidebar
        function closeSidebar() {
            sideBar.classList.add('hidden');
            sideBar.classList.remove('absolute', 'top-0', 'bottom-0', 'left-0', 'w-48', 'z-50');
            overlay.classList.add('hidden');
        }

        // Open sidebar
        sideBarOpen.addEventListener('click', openSidebar);

        // Close sidebar
        sideBarClose.addEventListener('click', closeSidebar);

        // Close when clicking outside (overlay)
        overlay.addEventListener('click', closeSidebar);

        // Optional: Close sidebar if user presses Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
    });
</script>


    <div id="toastContainer" class="toast-container top-right"></div>
</body>
</html>
