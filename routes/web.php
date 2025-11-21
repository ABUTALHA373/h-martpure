<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Products;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('pages.home');
//});
Route::get('/login', Login::class)->name('login');
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', Login::class)->name('login')->middleware('auth.guest');

    Route::middleware('auth.admin')->group(function () {

//        Route::post('/logout', function () {
//            Auth::guard('admin')->logout();
//            session()->invalidate();
//            session()->regenerateToken();
//            return redirect()->route('admin.login');
//        })->name('logout');
        Route::get('/', (function () {
            return redirect()->route('admin.dashboard');
        }));

        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/products', Products::class)->name('products');

    });

});
