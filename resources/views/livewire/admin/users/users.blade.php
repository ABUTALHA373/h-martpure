<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
        <div class="text-center sm:text-left">
            <h1 class="text-3xl font-bold text-text-primary">Users</h1>
            <p class="text-text-secondary">Manage general users</p>
        </div>

    </div>
    {{-- Filters & Search --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-3">
        {{-- Search --}}
        <div class="md:col-span-6 relative">
            <x-others.input
                wire:model.live.debounce="searchText"
                class="w-full bg-bg-primary"
                type="text"
                placeholder="Search user by name & email">
            </x-others.input>
        </div>
        {{-- Stock Status Filter --}}
        <div class="md:col-span-3">
            <x-others.select
                wire:model.live="searchStatus"
                placeholder="All Status"
                :options="[
                    ['label' => 'Active', 'value' => 'active'],
                    ['label' => 'Pending', 'value' => 'pending'],
                    ['label' => 'Blocked', 'value' => 'blocked'],
                    ['label' => 'Disabled', 'value' => 'disabled'],
                ]"
            />

        </div>

        {{-- Sort --}}
        <div class="md:col-span-3">

            <x-others.select
                wire:model.live="searchSort"
                placeholder="Sort By"
                :options="[
                    ['label' => 'Latest', 'value' => 'latest'],
                    ['label' => 'Oldest', 'value' => 'oldest'],
                    ['label' => 'Name (A-Z)', 'value' => 'az'],
                    ['label' => 'Name (Z-A)', 'value' => 'za']
                ]"
            />
        </div>
    </div>


    <div class="bg-bg-primary border border-custom rounded-lg overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead
                    class="bg-bg-secondary text-text-secondary text-xs font-semibold uppercase tracking-wider border-b border-custom">
                <tr>
                    <th scope="col" class="w-8 pl-6 pr-3 py-3 text-text-secondary">#</th>
                    <th scope="col" class="px-3 py-3">Admin</th>
                    <th scope="col" class="px-3 py-3">Email</th>
                    <th scope="col" class="px-3 py-3">Order Count</th>
                    <th scope="col" class="px-3 py-3">Reg Date</th>
                    <th scope="col" class="px-3 py-3">Status</th>
                    <th scope="col" class="px-3 py-3">Verified At</th>
                </tr>
                <tbody class="divide-y divide-custom">
                @foreach($users as $k=>$user)
                    <tr class="hover:bg-bg-secondary/50 transition-colors text-text-secondary">
                        <td class="w-8 pl-6 pr-3 py-3 ">{{$k+1}}</td>
                        <td class="px-3 py-3 font-bold text-text-primary">{{ $user->name }}</td>
                        <td class="px-3 py-3">{{ $user->email }}</td>
                        <td class="px-3 py-3">{{ 0 }}</td>
                        <td class="px-3 py-3">{{$user->created_at->format('l, d-m-Y')}}</td>
                        <td class="px-3 py-3">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500',
                                    'active' => 'bg-green-500/10 text-green-500 border-green-500',
                                    'blocked' => 'bg-red-500/10 text-red-500 border-red-500',
                                    'disabled' => 'bg-gray-500/10 text-gray-500 border-gray-500',
                                ];
                                $colorClass = $statusColors[$user->status] ?? 'bg-gray-500/10 text-gray-500 border-gray-500';
                            @endphp
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-2.5 py-1 rounded-md text-xs font-medium border {{ $colorClass }} capitalize">
                                    {{ $user->status }}
                                </span>
                                @adminHasPermission('users.update-status')
                                <button wire:click="openStatusModal({{ $user->id }}, '{{ $user->status }}')"
                                        class="text-text-secondary hover:text-secondary transition-colors cursor-pointer"
                                        title="Change Status">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                         class="size-4">
                                        <path fill-rule="evenodd"
                                              d="M10 4.5c1.215 0 2.417.055 3.604.162a.68.68 0 0 1 .615.597c.124 1.038.208 2.088.25 3.15l-1.689-1.69a.75.75 0 0 0-1.06 1.061l2.999 3a.75.75 0 0 0 1.06 0l3.001-3a.75.75 0 1 0-1.06-1.06l-1.748 1.747a41.31 41.31 0 0 0-.264-3.386 2.18 2.18 0 0 0-1.97-1.913 41.512 41.512 0 0 0-7.477 0 2.18 2.18 0 0 0-1.969 1.913 41.16 41.16 0 0 0-.16 1.61.75.75 0 1 0 1.495.12c.041-.52.093-1.038.154-1.552a.68.68 0 0 1 .615-.597A40.012 40.012 0 0 1 10 4.5ZM5.281 9.22a.75.75 0 0 0-1.06 0l-3.001 3a.75.75 0 1 0 1.06 1.06l1.748-1.747c.042 1.141.13 2.27.264 3.386a2.18 2.18 0 0 0 1.97 1.913 41.533 41.533 0 0 0 7.477 0 2.18 2.18 0 0 0 1.969-1.913c.064-.534.117-1.071.16-1.61a.75.75 0 1 0-1.495-.12c-.041.52-.093 1.037-.154 1.552a.68.68 0 0 1-.615.597 40.013 40.013 0 0 1-7.208 0 .68.68 0 0 1-.615-.597 39.785 39.785 0 0 1-.25-3.15l1.689 1.69a.75.75 0 0 0 1.06-1.061l-2.999-3Z"
                                              clip-rule="evenodd"/>
                                    </svg>

                                </button>
                                @endAdminHasPermission
                            </div>
                        </td>
                        <td class="px-3 py-3">{{$user->email_verified_at->format('l, d-m-Y')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{--    <!-- Add Role Modal -->--}}
    {{--    @if($showAddModal)--}}
    {{--        <x-others.modal>--}}
    {{--            <div class="p-4 sm:p-6 border-b border-custom flex justify-between items-center">--}}
    {{--                <h2 class="text-xl font-bold text-text-primary">Create New Role</h2>--}}
    {{--                <button wire:click="closeAddModal" class="text-text-secondary hover:text-text-primary cursor-pointer">--}}
    {{--                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
    {{--                         stroke="currentColor" class="size-6">--}}
    {{--                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>--}}
    {{--                    </svg>--}}
    {{--                </button>--}}
    {{--            </div>--}}
    {{--            <div class="p-4 sm:p-6 space-y-6">--}}
    {{--                <div>--}}
    {{--                    <label class="block text-sm font-medium mb-1">Role Name</label>--}}
    {{--                    <x-others.input wire:model.defer="role_name" class="w-full bg-bg-secondary"--}}
    {{--                                    placeholder="Enter role name"/>--}}
    {{--                    @error('role_name')--}}
    {{--                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>--}}
    {{--                    @enderror--}}
    {{--                </div>--}}

    {{--            </div>--}}
    {{--            <div class="p-4 sm:p-6 border-t border-custom flex justify-end gap-3">--}}
    {{--                <button wire:click="closeAddModal" class="btn btn-tertiary ">--}}
    {{--                    Cancel--}}
    {{--                </button>--}}
    {{--                <button wire:click="createRole" class="btn btn-primary">--}}
    {{--                    <svg fill="#ffffff" wire:loading wire:target="createRole"--}}
    {{--                         class="animate-spin h-5 w-5 mr-2 text-white" viewBox="0 0 16 16"--}}
    {{--                         xmlns="http://www.w3.org/2000/svg">--}}
    {{--                        <g>--}}
    {{--                            <path d="M8,1V2.8A5.2,5.2,0,1,1,2.8,8H1A7,7,0,1,0,8,1Z"/>--}}
    {{--                        </g>--}}
    {{--                    </svg>--}}
    {{--                    Create Role--}}
    {{--                </button>--}}
    {{--            </div>--}}
    {{--        </x-others.modal>--}}
    {{--    @endif--}}
    {{--    <!-- Assign Role Modal -->--}}
    <!-- Status Change Modal -->
    @adminHasPermission(['users.update-status'])
    @if($showStatusModal)
        <x-others.modal class="w-lg overflow-visible">
            <div class="p-4 sm:p-6 border-b border-custom flex justify-between items-center">
                <h2 class="text-xl font-bold text-text-primary">Update Status</h2>
                <button wire:click="closeStatusModal"
                        class="text-text-secondary hover:text-text-primary cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 sm:p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <x-others.select
                        class="bg-bg-secondary"
                        wire:model.defer="updateStatus"
                        placeholder="Update Status"
                        :options="[
                                    ['label' => 'Pending', 'value' => 'pending'],
                                    ['label' => 'Active', 'value' => 'active'],
                                    ['label' => 'Blocked', 'value' => 'blocked'],
                                    ['label' => 'Disabled', 'value' => 'disabled']
                                ]"
                    />
                </div>
            </div>
            <div class="p-4 sm:p-6 border-t border-custom flex justify-end gap-3">
                <button wire:click="closeStatusModal" class="btn btn-tertiary">
                    Cancel
                </button>
                <button wire:click="updateUserStatus" class="btn btn-primary">
                    <svg fill="#ffffff" wire:loading wire:target="updateAdminStatus"
                         class="animate-spin h-5 w-5 mr-2 text-white" viewBox="0 0 16 16"
                         xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path d="M8,1V2.8A5.2,5.2,0,1,1,2.8,8H1A7,7,0,1,0,8,1Z"/>
                        </g>
                    </svg>
                    Update Status
                </button>
            </div>
        </x-others.modal>
    @endif
    @endAdminHasPermission
</div>
