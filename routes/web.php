<?php

use App\Livewire\Admin\AdminRole;
use App\Livewire\Admin\AdminRolePermissions;
use App\Livewire\Admin\AdminUserPermissions;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Products;
use App\Livewire\Admin\Users;
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
        Route::get('/categories', Categories::class)->name('categories');
        Route::get('/users', Users::class)->name('users');
        Route::get('/adminRole', AdminRole::class)->name('adminRole')
            ->middleware('permission:admins.view|role-permission.view-role');
        Route::get('/roles/{role}/permissions', AdminRolePermissions::class)->name('role.permissions')
            ->middleware('permission:role-permission.manage-role-permission');
        Route::get('/admins/{admin}/permissions', AdminUserPermissions::class)->name('user.permissions')
            ->middleware('permission:role-permission.manage-admin-permission');

    });

});
