<div class="flex justify-between items-center gap-2 px-4 w-full h-full">
    <div class="cursor-pointer md:hidden" id="sideBarOpen">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
             class=" size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
        </svg>
    </div>
    <div class="flex gap-2 ">
        <div class="flex items-center border border-custom rounded">
            <livewire:layout.theme-toggle/>
        </div>
        <div class="p-2 rounded-md border border-custom text-sm font-semibold uppercase">
            {{auth('admin')->user()->name}}
        </div>
    </div>
</div>
