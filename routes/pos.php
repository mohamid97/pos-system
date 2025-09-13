<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    POSController,
    ProductController,
    CustomerController,
    SaleController,
    CategoryController,
    RoleController,
    UserController,
    Auth\LoginController
};






 Route::middleware(['throttle:pos'])->group(function () {

        Route::middleware('guest')->controller(LoginController::class)->group(function () {
                Route::get('/login', 'showLoginForm')->name('login');
                Route::post('/login', 'login');

        });


        Route::middleware('auth')->group(function () {

                // ==================== Dashboard ====================

                
                Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:view_dashboard');

                // ==================== Roles ========================
                Route::prefix('roles')->name('roles.')->controller(RoleController::class)->group(function () {
                        Route::middleware(['permission:create_role'])->group(function () {
                              Route::post('/store', [RoleController::class, 'store'])->name('store');
                              Route::get('/create', [RoleController::class, 'create'])->name('create');

                         });
                        Route::middleware(['permission:view_role'])->group(function () {
                                Route::get('/', [RoleController::class, 'index'])->name('index');
                                Route::get('/{id}', [RoleController::class, 'show'])->name('show');
                        });


                        
                        Route::middleware(['permission:edit_role'])->group(function () {
                                Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
                                Route::put('/{id}', [RoleController::class, 'update'])->name('update');
                        });

                        Route::middleware(['permission:delete_role'])->group(function () {
                                Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
                        });




                });



                Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {


                        Route::middleware('permission:create_user')->group(function () {
                                Route::get('/create', 'create')->name('create');
                                Route::post('/', 'store')->name('store');
                        });


                        Route::middleware('permission:view_user')->group(function () {
                                Route::get('/', 'index')->name('index');
                                Route::get('/{user}', 'show')->name('show');
                        });
                

                
                        Route::middleware('permission:edit_user')->group(function () {
                                Route::get('/{user}/edit', 'edit')->name('edit');
                                Route::put('/{user}', 'update')->name('update');
                                Route::patch('/{user}/toggle-status', 'toggleStatus')->name('toggle-status');
                        });
                
                        Route::middleware('permission:delete_user')->group(function () {
                                Route::delete('/{user}', 'destroy')->name('destroy');
                        });
                });




                // ==================== Categories ====================
                Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
                        Route::get('/', 'index')->name('index')->middleware('permission:view_category');
                        Route::get('/create', 'create')->name('create')->middleware('permission:create_category');
                        Route::post('/', 'store')->name('store')->middleware('permission:create_category');
                        Route::get('/{category}/edit', 'edit')->name('edit')->middleware('permission:edit_category');
                        Route::put('/{category}', 'update')->name('update')->middleware('permission:edit_category');
                        Route::delete('/{category}', 'destroy')->name('destroy')->middleware('permission:delete_category');
                });

                // ==================== POS ====================
                Route::prefix('pos')->name('pos.')->controller(POSController::class)->group(function () {
                        Route::get('/', 'index')->name('index')->middleware('permission:view_pos');
                        Route::post('/process', 'processSale')->name('process')->middleware('permission:create_pos');
                        // Route::get('/receipt', 'processSale')->name('receipt');
                });

                // ==================== Products ====================
                Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function () {
                        Route::get('/', 'index')->name('index')->middleware('permission:view_product');
                        Route::get('/create', 'create')->name('create')->middleware('permission:create_product');
                        Route::post('/', 'store')->name('store')->middleware('permission:create_product');
                        Route::get('/{product}/edit', 'edit')->name('edit')->middleware('permission:edit_product');
                        Route::put('/{product}', 'update')->name('update')->middleware('permission:edit_product');
                        Route::delete('/{product}', 'destroy')->name('destroy')->middleware('permission:delete_product');
                });


                // ==================== Customers ====================
                Route::prefix('customers')->name('customers.')->controller(CustomerController::class)->group(function () {
                        Route::get('/', 'index')->name('index')->middleware('permission:view_customer');
                        Route::get('/create', 'create')->name('create')->middleware('permission:create_customer');
                        Route::post('/', 'store')->name('store')->middleware('permission:create_customer');
                        Route::get('/{customer}/edit', 'edit')->name('edit')->middleware('permission:edit_customer');
                        Route::put('/{customer}', 'update')->name('update')->middleware('permission:edit_customer');
                        Route::delete('/{customer}', 'destroy')->name('destroy')->middleware('permission:delete_customer');
                });

                // ==================== Sales ====================
                Route::prefix('sales')->name('sales.')->controller(SaleController::class)->group(function () {
                        Route::get('/', 'index')->name('index')->middleware('permission:view_sale');
                        Route::get('/{sale}', 'show')->name('show')->middleware('permission:view_sale');
                        Route::get('/{sale}/cancel', 'cancel')->name('cancel')->middleware('permission:cancel_sale');
                });








        });








        
});












// Logout (if using authentication)
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');