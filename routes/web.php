<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\ResellerMiddleware;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ResellerController;
// use App\Http\Middleware\AdminMiddleware; // Jika ingin pakai admin middleware
// use App\Http\Controllers\OrderHistoryController;
// use App\Http\Controllers\GoogleDriveController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [ResellerController::class, 'ShowHome'])->name('home');
Route::get('/product/{slug}', [ResellerController::class, 'showProduct'])->name('product.show');
Route::get('/search', [ResellerController::class, 'search'])->name('search');
Route::get('/kategori/{slug}', [ResellerController::class, 'kategori'])->name('category.show');
Route::get('/shop/{slug}', [ResellerController::class, 'shop'])->name('shop.show');
Route::post('prouct/cart', [ResellerController::class, 'handleCartOrBuy'])->name('product.handleAction');
Route::get('/cart', [ResellerController::class, 'cart'])->name('cart');
Route::get('/dashboard', function () {
    return view('dashboard');
});

/*
|--------------------------------------------------------------------------
| AUTH RESELLER
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginResellerForm'])->name('login.reseller');
Route::post('/login', [AuthController::class, 'loginReseller'])->name('login.reseller.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/register/verify/{name}/{email}/{password}', [AuthController::class, 'verifyLink'])->name('register.verify');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

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
| RESELLER AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware([ResellerMiddleware::class])->group(function () {
    Route::get('/favorite', [ResellerController::class, 'favorite'])->name('favorite');
    Route::post('/favorite', [ResellerController::class, 'favoriteStore'])->name('favorite.store');
    Route::delete('/favorite/{id}', [ResellerController::class, 'favoriteDestroy'])->name('favorite.destroy');
});

/*
|--------------------------------------------------------------------------
| STAFF / ADMIN AUTH
|--------------------------------------------------------------------------
*/
Route::get('/staff-only', [AuthController::class, 'showLoginAdminForm'])->name('login.admin');
Route::post('/staff-only', [AuthController::class, 'loginAdmin'])->name('login.admin.post');

/*
|--------------------------------------------------------------------------
| ADMIN PAGES (NO GUARD YET)
|--------------------------------------------------------------------------
*/
Route::get('/admin/reseller', function () {
    return view('admin.reseller.index');
})->name('resellers.index');

/*
|--------------------------------------------------------------------------
| STAFF-ONLY PREFIX ROUTES (ADMIN CRUD)
|--------------------------------------------------------------------------
*/
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::prefix('staff-only')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('shops.products', ProductController::class)->shallow();
        Route::resource('shops', ShopController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('reviews', ReviewController::class);
        Route::resource('admins', AdminController::class);
        // Route::resource('orders/history', OrderHistoryController::class); // Enable when controller exists
    });
});
/*
|--------------------------------------------------------------------------
| (Optional) Google Drive Integration
|--------------------------------------------------------------------------
*/
// Route::get('/google/authorize', [GoogleDriveController::class, 'redirectToGoogle']);
// Route::get('/oauth2/callback', [GoogleDriveController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| (Optional) Protect admin routes with AdminMiddleware
|--------------------------------------------------------------------------
*/
// Route::middleware([AdminMiddleware::class])->group(function () {
//     Route::get('/admin/dashboard', function () {
//         return view('admin.dashboard');
//     });
// });
