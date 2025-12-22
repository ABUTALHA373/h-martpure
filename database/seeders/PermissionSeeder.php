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

            'brands' => [
                'view', 'create', 'edit', 'delete',
            ],

            'orders' => [
                'view', 'update', 'delete',
                'process', 'refund', 'assign-delivery',
                'status-update', 'tracking-update',
            ],

            'inventory' => [
                'view', 'update', 'stock-adjustment',
                'stock-report',
            ],

            'payments' => [
                'view', 'verify', 'reject',
                'transaction-update',
            ],

            'coupons-offers' => [
                'view', 'create', 'edit', 'delete',
                'activate', 'deactivate',
            ],

            'cms-pages' => [
                'view', 'create', 'edit', 'delete',
                'homepage-update', 'seo-update',
            ],

            'users' => [
                'view', 'edit', 'update-status', 'order-history',
            ],

            'admins' => [
                'view', 'create', 'update-status', 'reset-password',
            ],

            'role-permission' => [
                'view-role', 'create-role', 'delete-role', 'assign-admin-role', 'remove-admin-role',
                'manage-role-permission', 'manage-admin-permission'
            ],

            'reports' => [
                'view', 'export',
            ],

            'settings' => [
                'view', 'update',
                'security-update', 'payment-update',
                'shipping-update', 'notifications-update',
                'email-update', 'theme-update',
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
