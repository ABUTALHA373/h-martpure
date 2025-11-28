<div class="p-6 space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Manage User: {{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
        </div>
        <a href="{{ route('admin.adminRole') }}"
           class="px-4 py-2 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white transition-colors">
            Back to List
        </a>
    </div>

    <!-- Roles Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Assigned Roles</h3>

        @if (session()->has('role_message'))
            <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200">
                {{ session('role_message') }}
            </div>
        @endif

        <form wire:submit="updateRoles">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                @foreach($roles as $role)
                    <label
                        class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors">
                        <input type="checkbox" wire:model="selectedRoles" value="{{ $role->name }}"
                               class="w-5 h-5 text-secondary rounded border-gray-300 focus:ring-secondary">
                        <span class="ml-3 text-gray-700 dark:text-gray-300">{{ $role->name }}</span>
                    </label>
                @endforeach
            </div>
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                    Update Roles
                </button>
            </div>
        </form>
    </div>

    <!-- Direct Permissions Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Direct Permissions</h3>
        <p class="text-sm text-gray-500 mb-6">These permissions are assigned directly to the user, independent of their
            roles.</p>

        @if (session()->has('permission_message'))
            <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200">
                {{ session('permission_message') }}
            </div>
        @endif

        <form wire:submit="updateDirectPermissions">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                @foreach($permissions as $permission)
                    <label
                        class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors">
                        <input type="checkbox" wire:model="selectedDirectPermissions" value="{{ $permission->name }}"
                               class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="ml-3 text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                    Update Direct Permissions
                </button>
            </div>
        </form>
    </div>
</div>
