<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    POSController,
    ProductController,
    CustomerController,
    SaleController,
    CategoryController,
    Auth\LoginController
};


Route::middleware(['throttle:pos'])->group(function () {

        Route::middleware('guest')->controller(LoginController::class)->group(function () {
                Route::get('/login', 'showLoginForm')->name('login');
                Route::post('/login', 'login');

        });


        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        // ==================== Categories ====================
        Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{category}/edit', 'edit')->name('edit');
                Route::put('/{category}', 'update')->name('update');
                Route::delete('/{category}', 'destroy')->name('destroy');
        });


        // ==================== POS ====================
        Route::prefix('pos')->name('pos.')->controller(POSController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/process', 'processSale')->name('process');
                // Route::get('/receipt', 'processSale')->name('receipt');
        });

        // ==================== Products ====================
        Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{product}/edit', 'edit')->name('edit');
                Route::put('/{product}', 'update')->name('update');
                Route::delete('/{product}', 'destroy')->name('destroy');
        });

        // ==================== Customers ====================
        Route::prefix('customers')->name('customers.')->controller(CustomerController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{customer}/edit', 'edit')->name('edit');
                Route::put('/{customer}', 'update')->name('update');
                Route::delete('/{customer}', 'destroy')->name('destroy');
        });

        // ==================== Sales ====================
        Route::prefix('sales')->name('sales.')->controller(SaleController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{sale}', 'show')->name('show');
                Route::get('/{sale}/cancel', 'cancel')->name('cancel');
        });

        
});












// Logout (if using authentication)
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');