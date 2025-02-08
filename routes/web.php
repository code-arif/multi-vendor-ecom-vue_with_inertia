<?php

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\User\CheckOutController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VendorAuthController;
use App\Http\Controllers\Admin\AdminManageController;

//=================================ADMIN ROUTES=================================//
Route::group(['middleware' => AdminAuthMiddleware::class], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('show.admin.dashboard');
        Route::get('/profile', [AdminAuthController::class, 'showAdminProfile'])->name('show.admin.profile');
        Route::post('/profile/update', [AdminAuthController::class, 'updateAdminProfile'])->name('update.admin.profile');
        Route::post('/password/update', [AdminAuthController::class, 'updateAdminPassword'])->name('update.admin.password');
        Route::get('/logout', [AdminAuthController::class, 'AdminLogout'])->name('admin.logout');
    });

    //======================manage admin, subadmin, vendor======================//
    Route::get('/admins/{type?}', [AdminManageController::class, 'AdminManage'])->name('admin.manage');
    Route::get('/vendor-details/{id}', [AdminManageController::class, 'getVendorDetails'])->name('show.vendor.details');
    Route::post('/change-status/{id}', [AdminManageController::class, 'changeStatus'])->name('change.status');

    //=========================vendor============================//
    Route::group(['prefix' => 'vendor'], function () {
        Route::get('/profile', [VendorAuthController::class, 'showVendorProfile'])->name('show.vendor.profile');
        Route::post('/profile/update', [VendorAuthController::class, 'updateVendorProfile'])->name('update.vendor.profile');
        Route::post('/business/update', [VendorAuthController::class, 'updateVendorBusiness'])->name('update.vendor.business');
        Route::post('/bank/update', [VendorAuthController::class, 'updateVendorBank'])->name('update.vendor.bank');
    });

    //=======================section manage ==========================//
    Route::group(['prefix' => 'section'], function () {
        Route::get('/list', [SectionController::class, 'showSections'])->name('show.section');
        Route::post('/add', [SectionController::class, 'addSection'])->name('add.section');
        Route::post('/update/{id}', [SectionController::class, 'updateSection'])->name('update.section');
        Route::delete('/delete/{id}', [SectionController::class, 'deleteSection'])->name('delete.section');
        Route::post('/change-status/{id}', [SectionController::class, 'changeSectionStatus'])->name('change.section.status');
    });

    //=======================Category manage ==========================//
    Route::group(['prefix' => 'category'], function () {
        Route::get('/list', [CategoryController::class, 'showCategory'])->name('show.category');
        Route::get('/save', [CategoryController::class, 'showSaveCategory'])->name('show.save.category');
        Route::post('/add', [CategoryController::class, 'addCategory'])->name('add.category');
        Route::post('/update/{id}', [CategoryController::class, 'updateCategory'])->name('update.category');
        Route::delete('/delete/{id}', [CategoryController::class, 'deleteCategory'])->name('delete.category');
        Route::post('/change-status/{id}', [CategoryController::class, 'changeCategoryStatus'])->name('change.category.status');
    });

    //=======================Brand manage ==========================//
    Route::group(['prefix' => 'brand'], function () {
        Route::get('/list', [BrandController::class, 'showBrand'])->name('show.brand');
        Route::post('/add', [BrandController::class, 'addBrand'])->name('add.brand');
        Route::post('/update/{id}', [BrandController::class, 'updateBrand'])->name('update.brand');
        Route::delete('/delete/{id}', [BrandController::class, 'deleteBrand'])->name('delete.brand');
        Route::post('/change-status/{id}', [BrandController::class, 'changeBrandStatus'])->name('change.brand.status');
    });

    //======================product manage======================//
    Route::group(['prefix' => 'product'], function () {
        Route::get('/list', [ProductController::class, 'showProduct'])->name('show.product');
        Route::get('/save', [ProductController::class, 'showSaveProduct'])->name('show.save.product');
        Route::post('/add', [ProductController::class, 'addProduct'])->name('add.product');
        Route::post('/update/{id}', [ProductController::class, 'updateProduct'])->name('update.product');
        Route::delete('/delete/{id}', [ProductController::class, 'deleteProduct'])->name('delete.product');
        Route::post('/change-status/{id}', [ProductController::class, 'changeProductStatus'])->name('change.product.status');
        Route::get('/details', [ProductController::class, 'showSaveProductDetails'])->name('show.save.product.details');
        Route::post('/details/{id?}', [ProductController::class, 'saveProductDetails'])->name('save.product.details');
    });
});

//admin login
Route::get('/admin/login', [AdminAuthController::class, 'ShowAdminLogin'])->name('show.admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'AdminLogin'])->name('admin.login');


/*================================
User Route
================================*/
Route::get('/login', [UserAuthController::class, 'showLogin']);
Route::get('/register', [UserAuthController::class, 'showRegister']);


Route::get('/',[HomeController::class,'home'])->name('show.home.page');


// ======================Product page=======================//
Route::get('/products', [ProductController::class, 'showProductPage'])->name('show.products.page');
Route::get('/product/id', [ProductController::class, 'showProductDetailsPage'])->name('show.product.details.page');

//=======================Checkout=========================//
Route::get('/checkout', [CheckOutController::class, 'showCheckoutPage'])->name('show.checkout.page');

//=======================Cart page =========================//
Route::get('/cart', [CartController::class, 'showCartPage'])->name('show.cart.page');
