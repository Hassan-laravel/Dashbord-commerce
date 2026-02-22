<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Public Routes (Language Switcher)
|--------------------------------------------------------------------------
*/

Route::get('switch-language/{lang}', function ($lang) {
    if (array_key_exists($lang, config('language.supported'))) {
        Session::put('locale', $lang);
    }
    return redirect()->back();
})->name('switch.language');

/*
|--------------------------------------------------------------------------
| Guest Routes (Accessible before login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('admin/login', [LoginController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Protected Admin Dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout Route (Placed outside prefix to maintain 'logout' name independently)
    Route::post('admin/logout', [LoginController::class, 'logout'])->name('logout');

    // Protected Administration Routes
    Route::prefix('admin')->as('admin.')->group(function () {

        // Admin Dashboard Home: http://domain.com/admin
        Route::get('/', function () {
            return view('admin.index');
        })->name('dashboard');

        // Profile Management
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        // RBAC & Staff Management
        // 1. Staff Management (Requires 'manage-users' permission)
        Route::resource('users', UserController::class)->middleware('permission:manage-users');

        // 2. Role Management (Requires 'manage-roles' permission)
        Route::resource('roles', RoleController::class)->middleware('permission:manage-roles');

        // 3. Permissions Management (Exclusive to Super Admin)
        Route::resource('permissions', PermissionController::class)->middleware('role:Super Admin');

        // Content & E-commerce Management
        Route::resource('categories', CategoryController::class)->middleware('permission:manage-categories');

        // Note: Delete product image route defined before resource to avoid ID conflicts
        Route::delete('products/image/{id}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
        Route::resource('products', ProductController::class);

        Route::resource('orders', OrderController::class)->except(['create', 'store', 'edit']);
        Route::resource('customers', CustomerController::class)->except(['create', 'store', 'show']);
        Route::resource('pages', PageController::class);

        // System Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

/*
|--------------------------------------------------------------------------
| Root Redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| Alternative Permission Implementation (Commented for Reference)
|--------------------------------------------------------------------------
| 1. Index route available to those with either 'view' or 'manage' permissions.
|    The '|' symbol represents the "OR" operator.
| 2. Other CRUD operations restricted exclusively to 'manage' permission.
*/

// Route::get('categories', [CategoryController::class, 'index'])
//     ->name('categories.index')
//     ->middleware('permission:view-categories|manage-categories');

// Route::resource('categories', CategoryController::class)
//     ->except(['index'])
//     ->middleware('permission:manage-categories');
