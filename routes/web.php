<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\InvoiceController;
use App\Http\Controllers\Frontend\ItemController;
use App\Http\Controllers\Frontend\MidtransController;
use App\Http\Controllers\Frontend\OrderTrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| ITEMS
|--------------------------------------------------------------------------
*/

Route::get('/items', [ItemController::class, 'index'])
    ->name('items.index');

Route::get('/items/{item:slug}', [ItemController::class, 'show'])
    ->name('items.show');

/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/cart/add/{item}', [CartController::class, 'add'])
    ->name('cart.add');

Route::post('/cart/update/{item}', [CartController::class, 'update'])
    ->name('cart.update');

Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])
    ->name('cart.remove');

Route::delete('/cart/clear', [CartController::class, 'clear'])
    ->name('cart.clear');

/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get(
    '/checkout/success/{order:invoice_number}',
    [CheckoutController::class, 'success']
)->name('checkout.success');

/*
|--------------------------------------------------------------------------
| ORDER TRACKING
|--------------------------------------------------------------------------
*/

Route::get('/track-order', [OrderTrackingController::class, 'index'])
    ->name('orders.track');

Route::post('/track-order', [OrderTrackingController::class, 'search'])
    ->name('orders.search');

Route::get(
    '/orders/{order:invoice_number}',
    [OrderTrackingController::class, 'show']
)->name('orders.show');

/*
|--------------------------------------------------------------------------
| MIDTRANS
|--------------------------------------------------------------------------
*/

Route::get(
    '/orders/{order:invoice_number}/pay-midtrans',
    [MidtransController::class, 'pay']
)->name('orders.midtrans.pay');

Route::post(
    '/midtrans/callback',
    [MidtransController::class, 'callback']
)->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| INVOICE
|--------------------------------------------------------------------------
*/

Route::get(
    '/orders/{order:invoice_number}/invoice',
    [InvoiceController::class, 'download']
)->name('orders.invoice.download');

Route::get('/orders/{order:invoice_number}/payment-finish', [MidtransController::class, 'finish'])
    ->name('orders.midtrans.finish');

Route::post('/midtrans/notification', [MidtransController::class, 'notification'])
    ->name('midtrans.notification');

Route::post('/cart/buy-now/{item}', [CartController::class, 'buyNow'])
    ->name('cart.buy-now');

Route::get('/promo', [ItemController::class, 'promo'])
    ->name('promo.index');
    