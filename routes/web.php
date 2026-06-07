<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ItemController;
use App\Http\Controllers\Frontend\OrderTrackingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\RefundRequestController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item:slug}', [ItemController::class, 'show'])->name('items.show');

Route::post('/cart/add/{item}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order:invoice_number}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('orders.track');
Route::post('/track-order', [OrderTrackingController::class, 'search'])->name('orders.search');
Route::get('/orders/{order:invoice_number}', [OrderTrackingController::class, 'show'])->name('orders.show');
Route::post('/orders/{order:invoice_number}/upload-proof', [OrderTrackingController::class, 'uploadProof'])->name('orders.upload-proof');

Route::post('/orders/{order:invoice_number}/refund', [RefundRequestController::class, 'store'])
    ->name('orders.refund.store');