<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        <div class="text-center sm:text-left">
            <h1 class="text-3xl font-bold text-text-primary">Role & Permission Management</h1>
            <p class="text-text-secondary mt-1">Manage admin roles and permissions</p>
        </div>

    </div>


    <!-- Roles Grid -->
    <div class="bg-bg-primary rounded-xl shadow-sm border border-custom overflow-hidden">
        <div
            class="flex flex-col sm:flex-row justify-between items-center gap-4 py-4 px-4 sm:px-6 border-b border-custom ">
            <h2 class="text-xl font-bold text-text-primary">Roles</h2>
            <button wire:click="openAddModal"
                    class="btn btn-primary w-full sm:w-auto flex items-center justify-center gap-2 py-3 px-4 text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
                </svg>
                Create New Role
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4 sm:p-6">
            @foreach($roles as $role)
                <div
                    class="p-4 bg-bg-secondary border border-custom rounded-lg hover:border-secondary hover:shadow-md transition-all relative">

                    <div class="flex items-center justify-between">

                        <!-- LEFT: ICON + NAME -->
                        <a href="{{ route('admin.role.permissions', $role->id) }}"
                           class="flex items-center gap-3 cursor-pointer group">
                            <div class="p-2 bg-secondary/10 text-secondary rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="font-semibold text-base text-text-primary leading-tight group-hover:text-secondary transition-colors">
                                    {{ $role->name }}
                                </h3>
                                <p class="text-xs text-text-secondary">
                                    {{ $role->permissions->count() }} Permissions
                                </p>
                            </div>
                        </a>

                        <div class="opacity-60 group-hover:opacity-100 transition-opacity">
                            <button wire:click="confirmDeleteRole('{{ $role->name }}')"
                                    class="p-1 hover:bg-red-500/10 rounded-sm ml-1.5 text-text-secondary hover:text-red-500 focus:outline-none cursor-pointer transition-colors"
                                    title="Delete Role">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>

                            </button>
                        </div>

                    </div>

                </div>

            @endforeach

        </div>
    </div>

    <!-- Admins List -->
    <div class="bg-bg-primary rounded-xl shadow-sm border border-custom overflow-hidden">
        <div
            class="flex flex-col sm:flex-row justify-between items-center gap-4 py-4 px-4 sm:px-6 border-b border-custom ">
            <h2 class="text-xl font-bold text-text-primary">Admins with Roles</h2>
            <button wire:click="openAddAdminModal"
                    class="btn btn-primary w-full sm:w-auto flex items-center justify-center gap-2 py-3 px-4 text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                    <path
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
                </svg>
                Create New Admin
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead
                    class="bg-bg-secondary text-text-secondary text-xs font-semibold uppercase tracking-wider border-b border-custom">
                <tr>
                    <th scope="col" class="w-8 pl-6 pr-3 py-3 text-text-secondary">#</th>
                    <th scope="col" class="px-3 py-3">Admin</th>
                    <th scope="col" class="px-3 py-3">Email</th>
                    <th scope="col" class="px-3 py-3">Type</th>
                    <th scope="col" class="px-3 py-3">Assigned Role</th>
                    <th scope="col" class="px-3 py-3">Custom Permission</th>
                    <th scope="col" class="px-3 py-3">Last Update</th>
                    <th scope="col" class="pl-3 pr-6 py-3 text-right">Actions</th>
                </tr>
                <tbody class="divide-y divide-custom">
                @foreach($admins as $k=>$admin)
                    <tr class="hover:bg-bg-secondary/50 transition-colors text-text-secondary">
                        <td class="w-8 pl-6 pr-3 py-3 ">{{$k+1}}</td>
                        <td class="px-3 py-3 font-bold text-text-primary">{{ $admin->name }}</td>
                        <td class="px-3 py-3">{{ $admin->email }}</td>
                        <td class="px-3 py-3">{{ ucwords(str_replace('-',' ',$admin->user_type)) }}</td>
                        <td class="px-3 py-3">
                            <div class="flex flex-wrap gap-2">
                                @forelse($admin->roles as $role)
                                    <div
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-secondary/10 text-secondary border border-secondary group">
                                        {{ $role->name }}
                                        <button wire:click="confirmRemoveRole({{ $admin->id }}, '{{ $role->name }}')"
                                                class="ml-1.5 text-secondary group-hover:text-red-500 focus:outline-none cursor-pointer transition-colors"
                                                title="Remove Role">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20"
                                                 fill="currentColor">
                                                <path fill-rule="evenodd"
                                                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                @empty
                                    <span class="text-sm text-text-secondary italic">No roles assigned</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-3 py-3">
                            @if(!$admin->permissions->count()==0)
                                <span
                                    class="px-2.5 py-1 rounded-md text-xs font-medium lowercase  bg-blue-500/10 text-blue-500 border border-blue-500">{{$admin->permissions->count()}} Custom</span>
                            @else
                                <span class="text-sm text-text-secondary italic">None</span>
                            @endif
                        </td>
                        <td class="px-3 py-3">{{$admin->created_at->format('l, d-m-Y')}}</td>
                        <td class="px-3 py-3 text-right">
                            @if ($admin->user_type === 'system-admin')
                                <span
                                    class="inline-flex items-center justify-center p-2 rounded-md text-text-secondary cursor-not-allowed opacity-60"
                                    title="System Admin's role/permissions cannot be modified">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                </span>
                            @else
                                <a href="{{ route('admin.user.permissions', $admin->id) }}"
                                   class="inline-flex items-center justify-center p-2 rounded-md text-text-secondary hover:text-red-500 hover:bg-red-500/10 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Role Modal -->
    @if($showAddModal)
        <x-others.modal>
            <div class="p-4 sm:p-6 border-b border-custom flex justify-between items-center">
                <h2 class="text-xl font-bold text-text-primary">Create New Role</h2>
                <button wire:click="closeAddModal" class="text-text-secondary hover:text-text-primary cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 sm:p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Role Name</label>
                    <x-others.input wire:model.defer="role_name" class="w-full bg-bg-secondary"
                                    placeholder="Enter role name"/>
                    @error('role_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


            </div>
            <div class="p-4 sm:p-6 border-t border-custom flex justify-end gap-3">
                <button wire:click="closeAddModal" class="btn btn-tertiary ">
                    Cancel
                </button>
                <button wire:click="createRole" class="btn btn-primary">
                    <svg fill="#ffffff" wire:loading wire:target="createRole"
                         class="animate-spin h-5 w-5 mr-2 text-white" viewBox="0 0 16 16"
                         xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path d="M8,1V2.8A5.2,5.2,0,1,1,2.8,8H1A7,7,0,1,0,8,1Z"/>
                        </g>
                    </svg>
                    Create Role
                </button>
            </div>
        </x-others.modal>
    @endif
    <!-- Assign Role Modal -->
    <!-- Add Role Modal -->
    @if($showAddAdminModal)
        <x-others.modal>
            <div class="p-4 sm:p-6 border-b border-custom flex justify-between items-center">
                <h2 class="text-xl font-bold text-text-primary">Create New Admin User</h2>
                <button wire:click="closeAddModal" class="text-text-secondary hover:text-text-primary cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 sm:p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Full Name</label>
                    <x-others.input wire:model.defer="name" class="w-full bg-bg-secondary"
                                    placeholder="Enter role name"/>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <x-others.input wire:model="email" class="w-full bg-bg-secondary"
                                    placeholder="Enter valid email"/>
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="p-4 sm:p-6 border-t border-custom flex justify-end gap-3">
                <button wire:click="closeAddAdminModal" class="btn btn-tertiary ">
                    Cancel
                </button>
                <button wire:click="createAdminUser" class="btn btn-primary">
                    <svg fill="#ffffff" wire:loading wire:target="createAdminUser"
                         class="animate-spin h-5 w-5 mr-2 text-white" viewBox="0 0 16 16"
                         xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path d="M8,1V2.8A5.2,5.2,0,1,1,2.8,8H1A7,7,0,1,0,8,1Z"/>
                        </g>
                    </svg>
                    Create Admin User
                </button>
            </div>
        </x-others.modal>
    @endif
    <!-- Assign Role Modal -->
</div>
