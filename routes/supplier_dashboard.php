<?php

use Illuminate\Support\Facades\Route;

// use role middleware in spatie when install it
Route::prefix('supplier')->name('supplier.')->group(function () {
    Route::resource('surpluses', \App\Http\Controllers\API\SupplierDashboard\SurplusController::class);
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    });
});

