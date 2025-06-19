<?php

use App\Http\Controllers\API\APP\CartController;
use App\Http\Controllers\API\APP\CategoryController;
use App\Http\Controllers\API\APP\ContactController;
use App\Http\Controllers\API\APP\NotificationSettingController;
use App\Http\Controllers\API\APP\PaymentController;
use App\Http\Controllers\API\APP\StripeWebhookController;
use App\Http\Controllers\API\APP\SupplierRatingController;
use App\Http\Controllers\API\APP\SurplusController;
use Illuminate\Support\Facades\Route;

include __DIR__ . '/auth.php'; // Keep the auth routes
include __DIR__ . '/supplier_dashboard.php';
include __DIR__ . '/admin_dashboard.php';

Route::resource('surpluses', SurplusController::class)->only(['index', 'show']);

// Contact us Route
Route::post('contact', [ContactController::class, 'store']);

Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'index');
    Route::get('categories/{id}', 'show');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('surpluses', SurplusController::class)
        ->except(['index', 'show']);
});

// for cart
Route::get('/cart', [CartController::class, 'show']);
Route::post('/cart/items', [CartController::class, 'addItem']);
Route::put('/cart/items/{item}', [CartController::class, 'updateItem']);
Route::delete('/cart/items/{item}', [CartController::class, 'removeItem']);
Route::post('/cart/merge', [CartController::class, 'mergeCart'])->middleware('auth:sanctum');

// Checkout route
Route::post('/checkout', [\App\Http\Controllers\API\APP\CheckoutController::class, 'checkout'])->middleware('auth:sanctum');

// payment
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/payment/intent', [PaymentController::class, 'createStripeIntent']);
});
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

// profile
Route::post('/profile', [\App\Http\Controllers\API\APP\ProfileController::class, 'update'])->middleware('auth:sanctum');

// notification settings
Route::prefix('notification-settings')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [NotificationSettingController::class, 'show']);
    Route::put('/', [NotificationSettingController::class, 'update']);
});

// delete account
Route::delete('delete-account', [App\Http\Controllers\API\APP\DeleteAccountController::class, 'destroy'])->middleware('auth:sanctum');


// rating
Route::middleware(['auth:sanctum', 'role:charity'])->prefix('charity')->group(function () {
    Route::post('/supplier-ratings', [SupplierRatingController::class, 'store']);
    Route::get('/supplier-ratings', [SupplierRatingController::class, 'index']);
});
