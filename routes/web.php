<?php

use App\Livewire\Admin\AdminsAndRole\AdminRole;
use App\Livewire\Admin\AdminsAndRole\AdminRolePermissions;
use App\Livewire\Admin\AdminsAndRole\AdminUserPermissions;
use App\Livewire\Admin\Brands\Brands;
use App\Livewire\Admin\Categories\Categories;
use App\Livewire\Admin\Dashboard\Dashboard;
use App\Livewire\Admin\Inventory\Inventory;
use App\Livewire\Admin\Products\Products;
use App\Livewire\Admin\SystemSetting\SystemSetting;
use App\Livewire\Admin\Users\Users;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    return 'test';
})->name('test');

Route::get('/login', Login::class)->name('login');
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', Login::class)->name('login')->middleware('auth.guest');

    Route::middleware('auth.admin')->group(function () {

        Route::get('/', (function () {
            return redirect()->route('admin.dashboard');
        }));

        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/products', Products::class)->name('products');
        Route::get('/categories', Categories::class)->name('categories')
            ->middleware('permission:categories.view');
        Route::get('/brands', Brands::class)->name('brands')
            ->middleware('permission:brands.view');
        Route::get('/users', Users::class)->name('users')
            ->middleware('permission:users.view');
        Route::get('/inventory', Inventory::class)->name('inventory')
            ->middleware('permission:inventory.view');
        Route::get('/adminRole', AdminRole::class)->name('adminRole')
            ->middleware('permission:admins.view|role-permission.view-role');
        Route::get('/roles/{role}/permissions', AdminRolePermissions::class)->name('role.permissions')
            ->middleware('permission:role-permission.manage-role-permission');
        Route::get('/admins/{admin}/permissions', AdminUserPermissions::class)->name('user.permissions')
            ->middleware('permission:role-permission.manage-admin-permission');
        Route::get('/siteSetting', SystemSetting::class)->name('siteSetting')
            ->middleware('permission:settings.view');
    });
});
