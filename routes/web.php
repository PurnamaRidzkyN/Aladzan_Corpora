<?php

use App\Models\Cart;
use App\Models\Order;
use App\Models\Reseller;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\OngkirController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\ResellerMiddleware;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardResellerController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [CatalogController::class, 'ShowHome'])->name('home');
Route::get('/product/{slug}', [CatalogController::class, 'showProduct'])->name('product.show');
Route::get('/search', [CatalogController::class, 'search'])->name('search');
Route::get('/kategori/{slug}', [CatalogController::class, 'kategori'])->name('category.show');
Route::get('/shop/{slug}', [CatalogController::class, 'shop'])->name('shop.show');

/*
|--------------------------------------------------------------------------
| AUTH RESELLER
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginResellerForm'])->name('login.reseller');
Route::post('/login', [AuthController::class, 'loginReseller'])->name('login.reseller.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/register/verify/{email}', [AuthController::class, 'verifyLink'])->name('register.verify');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password/{ir}', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}/{email}', [AuthController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('change.password');
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change.password.post');

/*
|--------------------------------------------------------------------------
| LOGIN WITH GOOGLE
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| STAFF / ADMIN AUTH
|--------------------------------------------------------------------------
*/
Route::get('/staff-only', [AuthController::class, 'showLoginAdminForm'])->name('login.admin');
Route::post('/staff-only', [AuthController::class, 'loginAdmin'])->name('login.admin.post');

/*
|--------------------------------------------------------------------------
| STAFF-ONLY PREFIX ROUTES (ADMIN CRUD)
|--------------------------------------------------------------------------
*/
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::prefix('staff-only')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard_admin');
        })->name('dashboard.admin');
        Route::resource('categories', CategoryController::class);
        Route::resource('shops.products', ProductController::class)->shallow();
        Route::resource('shops', ShopController::class);
        Route::get('/orders', [OrderController::class, 'currentOrders'])->name('orders.current');
        Route::post('/orders/update-status', [OrderController::class, 'changeStatus'])->name('order.update-status');
        Route::get('/orders/history', [OrderController::class, 'history_orders'])->name('orders.history');
        Route::get('reviews/{slug}', [ReviewController::class, 'index'])->name('reviews.show');
        Route::put('reviews/{id}/reply', [ReviewController::class, 'reviewReply'])->name('reviews.reply');
        Route::resource('admins', AdminController::class);
        Route::get('/course', [CourseController::class, 'groupVideoIndex'])->name('group.course');
        Route::post('/course', [CourseController::class, 'groupVideoStore'])->name('group.course.store');
        Route::put('/course/{id}', [CourseController::class, 'groupVideoUpdate'])->name('group.course.update');
        Route::delete('/course/{id}', [CourseController::class, 'groupVideoDestroy'])->name('group.course.destroy');
        Route::get('/course/{id}/video', [CourseController::class, 'videoIndex'])->name('group.course.video');
        Route::post('/course/{id}/video', [CourseController::class, 'videoStore'])->name('group.course.video.store');
        Route::put('/course/{id}/video/{video_id}', [CourseController::class, 'videoUpdate'])->name('group.course.video.update');
        Route::delete('/course/{id}/video/{video_id}', [CourseController::class, 'videoDestroy'])->name('group.course.video.destroy');
        Route::get('/course/{id}/video/{video_id}', [CourseController::class, 'videoShow'])->name('group.course.video.show');
        Route::resource('discount', DiscountController::class);
        Route::get('/resellers', [ResellerController::class, 'resellerAccount'])->name('reseller.index');
        Route::delete('/resellers/{id}', [ResellerController::class, 'resellerDestroy'])->name('reseller.destroy');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications');

        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsReadAdmin'])->name('admin.notifications.read');
    });
});
Route::middleware([ResellerMiddleware::class])->group(function () {
    Route::get('/favorite', [WishlistController::class, 'favorite'])->name('favorite');
    Route::post('/favorite', [WishlistController::class, 'favoriteStore'])->name('favorite.store');
    Route::delete('/favorite/{id}', [WishlistController::class, 'favoriteDestroy'])->name('favorite.destroy');
    Route::post('prouct/cart', [CartController::class, 'handleCartOrBuy'])->name('product.handleAction');
    Route::get('/cart', [CartController::class, 'cart'])->name('cart');
    Route::delete('/cart/{id}', [CartController::class, 'cartDestroy'])->name('cart.destroy');
    Route::resource('address', AddressController::class);
    Route::get('/order-history', [OrderController::class, 'orderHistory'])->name('order.history');
    Route::get('/order/{order_code}', [OrderController::class, 'orderDetail'])->name('order.detail');
    Route::prefix('checkout')->group(function () {
        Route::post('/choose-address', [PaymentController::class, 'chooseAddress'])->name('checkout.chooseAddress');
        Route::post('/', [PaymentController::class, 'checkout'])->name('checkout');
        Route::post('/confirm', [PaymentController::class, 'checkoutConfirm'])->name('checkout.confirm');
    });
    Route::post('/review', [ReviewController::class, 'review'])->name('review');

    Route::get('/payment/{order_code}', [PaymentController::class, 'payment'])->name('payment');
    Route::post('/payment/{order_code}/confirm', [PaymentController::class, 'paymentConfirm'])->name('payment.confirm');

    Route::get('/profil', function () {
        return view('store.profile.profile');
    })->name('profile');
    Route::post('/profile/edit', [ResellerController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/change-email/{name}/{new_email}/{old_email}', [ResellerController::class, 'changeEmailReseller'])->name('change.email.reseller');

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardResellerController::class, 'index'])->name('dashboard.reseller');
        Route::get('/course', [CourseController::class, 'groupVideoIndex'])->name('reseller.course');
        Route::get('/course/{id}/video', [CourseController::class, 'videoIndex'])->name('reseller.course.video');
        Route::get('/course/{id}/video/{video_id}', [CourseController::class, 'videoShow'])->name('reseller.course.video.show');
    });
    Route::get('/notifications', [NotificationController::class, 'index'])->name('reseller.notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsReadReseller'])->name('reseller.notifications.read');
    Route::get('/upgrade-account', [ResellerController::class, 'showUpgradeAccount'])->name('upgrade.account');
    Route::get('/check-discount/{code}', [DiscountController::class, 'check'])->name('check.discount');
    Route::post('/upgrade-account', [ResellerController::class, 'upgradeAccount'])->name('upgrade.account.post');
    Route::post('/media/download', [MediaController::class, 'downloadSelected'])
    ->name('media.downloadSelected');
});
