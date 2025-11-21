<div class="flex justify-between items-center gap-2 px-4 w-full h-full">
    <div class="cursor-pointer md:hidden" id="sideBarOpen">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
             class="text-secondary size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
        </svg>
    </div>
    <div class="p-2 hidden md:flex flex-col gap-1 rounded-md border border-custom text-sm font-semibold uppercase">
        <span class="leading-3.5">{{auth('admin')->user()->name}}</span>
        <span class="text-[0.6rem] font-light text-secondary leading-2">{{auth('admin')->user()->user_type}}</span>
    </div>
    <div class="text-lg font-bold">
        MARTPURE
    </div>
    <div class="flex gap-2 ">
        <div class="flex items-center border border-custom rounded">
            <livewire:layout.theme-toggle/>
        </div>
        <div class="p-2 rounded-md border border-custom text-sm font-semibold uppercase">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
            </svg>

        </div>
    </div>
</div>
