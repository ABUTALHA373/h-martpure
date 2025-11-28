<div>
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        <div class="text-center sm:text-left">
            <h2 class="text-3xl font-bold text-text-primary">Manage Permissions: <span
                    class="text-secondary">{{ $role->name }}</span></h2>
            <p class="text-sm text-text-secondary mt-1">Select permissions to assign to this role</p>
        </div>
        <a href="{{ route('admin.adminRole') }}" class="btn btn-tertiary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
            </svg>
            Back to Roles
        </a>
    </div>
    <!-- Direct Permissions Section -->
    <div class="bg-bg-primary rounded-xl shadow-sm border border-custom">
        <div
            class="text-center sm:text-left py-4 px-4 sm:px-6 border-b border-custom ">
            <h2 class="text-xl font-bold text-text-primary">Permissions</h2>
            <p class="text-sm text-text-secondary">These permissions are assigned directly to the roles.</p>
        </div>
        <div class="py-4 px-4 sm:px-6 sm:py-6">
            <form wire:submit="updatePermissions">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    @foreach($permissions as $permission)
                        <label
                            class="flex items-center p-3 border border-custom rounded-lg hover:bg-bg-secondary cursor-pointer transition-colors">
                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}"
                                   class="w-5 h-5 text-secondary rounded border-gray-300 focus:ring-secondary">
                            <span class="ml-3 text-text-primary">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
