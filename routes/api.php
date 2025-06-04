<?php

use App\Http\Controllers\API\APP\CartController;
use App\Http\Controllers\API\APP\CategoryController;
use App\Http\Controllers\API\APP\ContactController;
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


