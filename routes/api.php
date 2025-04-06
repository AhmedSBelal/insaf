<?php

use App\Http\Controllers\API\APP\CategoryController;
use App\Http\Controllers\API\APP\SurplusController;
use Illuminate\Support\Facades\Route;

include __DIR__ . '/auth.php';
include __DIR__ . '/supplier.php';

Route::resource('surpluses', SurplusController::class)->only(['index', 'show']);

Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'index');
    Route::get('categories/{id}', 'show');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('surpluses', SurplusController::class)
        ->except(['index', 'show']);
});


