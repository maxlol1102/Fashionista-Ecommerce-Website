<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;

// Authentication routes
Auth::routes();

// Home page route
Route::get('/', function () {
    return view('welcome');
});

// Application index route
Route::get('/', [AppController::class, 'index'])->name('app.index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}',[ShopController::class,'productDetails'])->name('shop.product.details');
Route::get('/cart',[CartController::class,'index'])->name('cart.index');
Route::post('/cart/store', [CartController::class, 'addToCart'])->name('cart.store');
Route::put('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');

Route::delete('/cart/remove', [CartController::class, 'removeCart'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');


// Authenticated user routes
Route::middleware('auth')->group(function(){
    Route::get('/my-account', [UserController::class, 'index'])->name('user.index');
});

// Authenticated admin routes
Route::middleware(['auth', 'auth.admin'])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});
