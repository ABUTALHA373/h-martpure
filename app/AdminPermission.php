<?php

namespace App;

trait AdminPermission
{
    /**
     * Enforce admin permission. Abort if not allowed.
     *
     * @param string|array $permissions
     * @return bool
     */
    public function authorizeAdmin(string|array $permissions): bool
    {
        $admin = auth('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized Admin!');
        }

        // Super-admin bypass
        if ($admin->hasRole('super-admin')) {
            return true;
        }

        $permissions = is_array($permissions) ? $permissions : [$permissions];

        foreach ($permissions as $permission) {
            if ($admin->can($permission)) {
                return true;
            }
        }

        abort(403, 'Unauthorized action!');
    }

    /**
     * Check if admin has permission. Returns true/false.
     *
     * @param string|array $permissions
     * @return bool
     */
    public function canAdmin(string|array $permissions): bool
    {
        $admin = auth('admin')->user();

        if (!$admin) return false;

        if ($admin->hasRole('super-admin')) return true;

        $permissions = is_array($permissions) ? $permissions : [$permissions];

        foreach ($permissions as $permission) {
            if ($admin->can($permission)) return true;
        }

        return false;
    }
}
