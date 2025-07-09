<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderHistoryController;

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

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post'); 
Route::prefix('admin')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('shops', ShopController::class);
    route::resource('orders', OrderController::class);
    Route::resource('orders/history', OrderHistoryController::class); 
    route::resource('reviews', ReviewController::class);
});

// use App\Http\Middleware\AdminMiddleware;

// Route::middleware([AdminMiddleware::class])->group(function () {
//     Route::get('/admin/dashboard', function () {
//         return view('admin.dashboard');
//     });
// });
