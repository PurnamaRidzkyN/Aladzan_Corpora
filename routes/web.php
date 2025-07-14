<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get('/cart', function () {
    return view('cart');
});
Route::get('/admin/reseller', function () {
    return view('admin.reseller.index');
})->name('resellers.index');

Route::get('/staff-only', [AuthController::class, 'showLoginAdminForm'])->name('login.admin');
Route::post('/staff-only', [AuthController::class, 'loginAdmin'])->name('login.admin.post');


Route::get('/login', [AuthController::class, 'showLoginResellerForm'])->name('login.reseller');
Route::post('/login', [AuthController::class, 'loginReseller'])->name('login.reseller.post');
Route::get('/forgot-password/{ir}', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}/{email}', [AuthController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/register/verify/{name}/{email}/{password}', [AuthController::class, 'verifyLink'])->name('register.verify');
Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('change.password');
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change.password.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('staff-only')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('shops.products', ProductController::class)->shallow();
    Route::resource('shops', ShopController::class);
    route::resource('orders', OrderController::class);
    Route::resource('orders/history', OrderHistoryController::class); 
    route::resource('reviews', ReviewController::class);
    Route::resource('admins', AdminController::class);
});

// use App\Http\Controllers\GoogleDriveController;
// use App\Http\Controllers\OrderHistoryController;

// Route::get('/google/authorize', [GoogleDriveController::class, 'redirectToGoogle']);
// Route::get('/oauth2/callback', [GoogleDriveController::class, 'handleGoogleCallback']);


// use App\Http\Middleware\AdminMiddleware;

// Route::middleware([AdminMiddleware::class])->group(function () {
//     Route::get('/admin/dashboard', function () {
//         return view('admin.dashboard');
//     });
// });
