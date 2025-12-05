<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            'Global' => [
                'delete', 'edit'
            ],
            'dashboard' => [
                'view',
            ],

            'products' => [
                'view', 'create', 'edit', 'delete',
                'status-update', 'inventory-update',
            ],

            'categories' => [
                'view', 'create', 'edit', 'delete',
            ],

            'coupons' => [
                'view', 'create', 'edit', 'delete',
            ],

            'orders' => [
                'view', 'update', 'delete', 'process',
                'refund', 'assign-delivery',
            ],

            'customers' => [
                'view', 'edit', 'block', 'delete',
            ],

            'cms' => [
                'view', 'create', 'edit', 'delete',
                'homepage-update', 'seo-update',
            ],

            'admins' => [
                'view', 'create', 'edit', 'delete',
            ],

            'roles' => [
                'view', 'create', 'edit', 'delete',
            ],

            'permissions' => [
                'view', 'assign',
            ],

            'settings' => [
                'view', 'update',
                'security-update', 'payment-update',
                'shipping-update', 'notifications-update',
            ],
        ];

        // Create all permissions
        foreach ($permissions as $group => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$group}.{$action}",
                    'guard_name' => 'admin',
                ]);
            }
        }
    }
}
