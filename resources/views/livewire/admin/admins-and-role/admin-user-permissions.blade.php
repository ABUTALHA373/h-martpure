<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        <div class="text-center sm:text-left">
            <h1 class="text-3xl font-bold text-text-primary">Manage Admin Role & Permission</h1>
            <p class="text-text-secondary mt-1">Assign admin role and their direct permissions.</p>
        </div>
        <a wire:navigate href="{{ route('admin.adminRole') }}" class="btn btn-tertiary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
            </svg>
            Back to List
        </a>
    </div>
    <!-- Roles Section -->
    <div class="bg-bg-primary rounded-xl shadow-sm border border-custom">
        <div
            class="flex flex-col sm:flex-row justify-between items-center gap-4 py-4 px-4 sm:px-6 border-b border-custom ">
            <h2 class="text-xl font-bold text-text-primary">Assigned Role: <span
                    class="text-secondary">{{ $admin->name }}</span></h2>
        </div>

        <div class="py-4 px-4 sm:px-6 sm:py-6">
            <form wire:submit="updateRoles">
                <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($roles as $role)
                        <label
                            class="flex items-center p-3 border border-custom rounded-lg hover:bg-bg-secondary cursor-pointer transition-colors">
                            <input type="radio" wire:model="assignedRoles" value="{{ $role->name }}"
                                   class="w-5 h-5 text-secondary rounded border-gray-300 focus:ring-secondary">
                            <span class="ml-3 text-text-primary">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="flex justify-end gap-2 mt-4 sm:mt-6">
                    @adminHasPermission('role-permission.remove-admin-role')
                    @if (!empty($assignedRoles))
                        <button type="button"
                                wire:click="confirmRemoveRole({{ $admin->id }}, '{{ json_encode($assignedRoles) }}')"
                                class="btn btn-tertiary">
                            Remove Role
                        </button>
                    @endif
                    @endAdminHasPermission
                    @adminHasPermission('role-permission.assign-admin-role')
                    <button type="submit" class="btn btn-primary">
                        <svg fill="#ffffff" wire:loading wire:target="updateRoles"
                             class="animate-spin h-5 w-5 mr-2 text-white" viewBox="0 0 16 16"
                             xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path d="M8,1V2.8A5.2,5.2,0,1,1,2.8,8H1A7,7,0,1,0,8,1Z"/>
                            </g>
                        </svg>
                        Update Role
                    </button>
                    @endAdminHasPermission
                </div>
            </form>
        </div>
    </div>
    <!-- Direct Permissions Section -->
    <div class="bg-bg-primary rounded-xl shadow-sm border border-custom">
        <div
            class="text-center sm:text-left py-4 px-4 sm:px-6 border-b border-custom ">
            <h2 class="text-xl font-bold text-text-primary">Direct Permissions</h2>
            <p class="text-sm text-text-secondary">These permissions are assigned directly to the admin,
                independent of
                their roles.</p>
        </div>
        <div class="py-4 px-4 sm:px-6 sm:py-6">
            <form wire:submit="updateDirectPermissions">
                @php
                    $groupedPermissions = $permissions->groupBy(function($permission) {
                        return explode('.', $permission->name)[0];
                    });

                    $getShortName = function($permissionName) {
                        $parts = explode('.', $permissionName);
                        return ucfirst(end($parts));
                    };
                @endphp

                <div class="space-y-8" x-data="{
                        selected: @entangle('selectedDirectPermissions'),
                        toggleAll(groupPermissions) {
                            let allSelected = groupPermissions.every(p => this.selected.includes(p));
                            if (allSelected) {
                                this.selected = this.selected.filter(p => !groupPermissions.includes(p));
                            } else {
                                this.selected = [...new Set([...this.selected, ...groupPermissions])];
                            }
                        },
                        isAllSelected(groupPermissions) {
                            return groupPermissions.every(p => this.selected.includes(p));
                        }
                    }">
                    @foreach($groupedPermissions as $group => $groupPermissions)
                        @php
                            $permissionNames = $groupPermissions->pluck('name')->toArray();
                            $jsonPermissions = json_encode($permissionNames);
                        @endphp
                        <div class="bg-bg-primary rounded-xl shadow-sm border border-custom overflow-hidden">
                            <div
                                class="flex items-center justify-between px-6 py-4 bg-bg-secondary/30 border-b border-custom">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-1 bg-secondary rounded-full"></div>
                                    <h3 class="text-lg font-bold text-text-primary capitalize">{{ $group }}</h3>
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-bg-secondary text-text-secondary border border-custom">
                                            {{ $groupPermissions->count() }} Permissions
                                        </span>
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox"
                                               @click="toggleAll({{ $jsonPermissions }})"
                                               :checked="isAllSelected({{ $jsonPermissions }})"
                                               class="peer w-5 h-5 text-secondary rounded border-gray-300 focus:ring-secondary cursor-pointer transition-all">
                                    </div>
                                </label>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                    @foreach($groupPermissions as $permission)
                                        <label class="relative flex items-center p-3 rounded-xl border cursor-pointer transition-all duration-200 hover:shadow-md group
                                                      bg-bg-primary hover:bg-bg-secondary/50
                                                      "
                                               :class="selected.includes('{{ $permission->name }}') ? 'border-secondary ring-1 ring-secondary' : 'border-custom hover:border-secondary/50'"
                                        >
                                            <div class="flex items-center h-5">
                                                <input type="checkbox"
                                                       value="{{ $permission->name }}"
                                                       x-model="selected"
                                                       class="w-4 h-4 text-secondary rounded border-gray-300 focus:ring-secondary">
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <span
                                                    class="font-semibold text-sm text-text-primary">{{ $getShortName($permission->name) }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @adminHasPermission('role-permission.manage-role-permission')
                <div class="flex justify-end mt-4 sm:mt-6">
                    <button type="submit" class="btn btn-primary">
                        <svg fill="#ffffff" wire:loading wire:target="updateDirectPermissions"
                             class="animate-spin h-5 w-5 mr-2 text-white" viewBox="0 0 16 16"
                             xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path d="M8,1V2.8A5.2,5.2,0,1,1,2.8,8H1A7,7,0,1,0,8,1Z"/>
                            </g>
                        </svg>
                        Update Direct Permissions
                    </button>
                </div>
                @endAdminHasPermission
            </form>
        </div>
    </div>
</div>
