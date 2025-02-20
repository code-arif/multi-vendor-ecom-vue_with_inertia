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
use App\Http\Controllers\Admin\VendorProfileManageController;
use App\Http\Controllers\Admin\AdminManageController;
use App\Http\Controllers\Admin\ProductDetailsController;
use App\Http\Controllers\Admin\ProductSliderController;
use App\Http\Controllers\Admin\ProductSpecificationController;
use App\Http\Controllers\User\ProductUserController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Middleware\TokenVerificationMiddleware;

//=================================ADMIN ROUTES=================================//
//admin login
Route::get('/admin/login', [AdminAuthController::class, 'ShowAdminLogin'])->name('show.admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'AdminLogin'])->name('admin.login');

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
        Route::get('/profile', [VendorProfileManageController::class, 'showVendorProfile'])->name('show.vendor.profile');
        Route::post('/profile/update', [VendorProfileManageController::class, 'updateVendorProfile'])->name('update.vendor.profile');
        Route::post('/business/update', [VendorProfileManageController::class, 'updateVendorBusiness'])->name('update.vendor.business');
        Route::post('/bank/update', [VendorProfileManageController::class, 'updateVendorBank'])->name('update.vendor.bank');

        //desclaimer
        Route::get('/desclaimer', [VendorProfileManageController::class, 'showVendorDesclaimer'])->name('show.vendor.desclaimer');
    });

    //=====================section manage=========================//
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

    //========================product manage========================//
    Route::group(['prefix' => 'product'], function () {
        Route::get('/list', [ProductController::class, 'showProduct'])->name('show.product');
        Route::get('/save', [ProductController::class, 'showSaveProduct'])->name('show.save.product');
        Route::post('/add', [ProductController::class, 'addProduct'])->name('add.product');
        Route::post('/update/{id}', [ProductController::class, 'updateProduct'])->name('update.product');
        Route::delete('/delete/{id}', [ProductController::class, 'deleteProduct'])->name('delete.product');
        Route::post('/change-status/{id}', [ProductController::class, 'changeProductStatus'])->name('change.product.status');

        //product details controller, table, page manage
        Route::get('/details', [ProductDetailsController::class, 'showSaveProductDetails'])->name('show.save.product.details');
        Route::post('/details', [ProductDetailsController::class, 'saveProductDetails'])->name('save.product.details');
        Route::post('/update-details', [ProductDetailsController::class, 'updateProductDetails'])->name('update.product.details');
        //product all details
        Route::get('/product-details', [ProductDetailsController::class, 'showProductDetails'])->name('show.product.details');

        // product sepcification controller,table, page manage
        Route::post('/image', [ProductSpecificationController::class, 'saveProductImage'])->name('save.product.image');
        Route::post('/video', [ProductSpecificationController::class, 'saveProductVideo'])->name('save.product.video');
        Route::get('/specification', [ProductSpecificationController::class, 'showProductSpecification'])->name('show.product.specification');
        Route::post('/specification', [ProductSpecificationController::class, 'saveProductSpecification'])->name('save.product.specification');
        Route::post('/update-specification', [ProductSpecificationController::class, 'updateProductSpecification'])->name('update.product.specification');

        //product slider manage
        Route::get('/slider-list', [ProductSliderController::class, 'showProductSlider'])->name('show.product.slider');
        Route::post('/slider', [ProductSliderController::class, 'saveProductSlider'])->name('save.product.slider');
        Route::post('/slider/update/{id}', [ProductSliderController::class, 'updateProductSlider'])->name('update.product.slider');
        Route::delete('/slider/delete/{id}', [ProductSliderController::class, 'deleteProductSlider'])->name('delete.product.slider');
        Route::post('/change-status/slider/{id}', [ProductSliderController::class, 'changeProductSliderStatus'])->name('change.product.slider.status');
    });
});

/*================================
Vendor Route
================================*/
Route::get('/vendor-account', [VendorController::class, 'showVendorAccountCreatePage'])->name('show.vendor.account.create.page');
Route::post('/vendor-account', [VendorController::class, 'createVendorAccount'])->name('create.vendor.account');
Route::get('/vendor/confirm/{code}', [VendorController::class, 'vendorConfirmation']);

/*================================
User Route
================================*/
//========================index page =========================//
Route::get('/', [HomeController::class, 'home'])->name('show.home.page');

//====================user login registration, forget password=====================//
Route::get('/login', [UserAuthController::class, 'showLogin'])->name('show.user.login');
Route::post('/login', [UserAuthController::class, 'userLogin'])->name('user.login');
Route::get('/otp', [UserAuthController::class, 'showOTP'])->name('show.otp');
Route::post('/otp', [UserAuthController::class, 'verifyOTP'])->name('verify.otp');

//=======================authenticated routes====================================//
Route::group(['middleware' => TokenVerificationMiddleware::class], function () {
    Route::get('/user/logout', [UserAuthController::class, 'userLogout'])->name('user.logout');

    //=======================Cart page =========================//
    Route::get('/cart', [CartController::class, 'showCartPage'])->name('show.cart.page');
    Route::post('/cart-create', [CartController::class, 'createCart'])->name('create.cart');
});

// ======================Product page=======================//
Route::get('/products', [ProductUserController::class, 'showProductPage'])->name('show.products.page');
Route::get('/product', [ProductUserController::class, 'showProductDetailsPage'])->name('show.product.details.page');
Route::get('/product/by-category', [ProductUserController::class, 'productByCategory'])->name('show.product.by.category');
Route::get('/product/by-brand', [ProductUserController::class, 'productByBrand'])->name('show.product.by.brand');

//=======================Checkout=========================//
Route::get('/checkout', [CheckOutController::class, 'showCheckoutPage'])->name('show.checkout.page');

//==========================get section for header =====================//
Route::get('/get-header-section', [HomeController::class, 'getHeaderSection'])->name('get.header.section');
