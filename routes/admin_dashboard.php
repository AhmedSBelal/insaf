<?php

use Illuminate\Support\Facades\Route;
use \App\Enums\UserRoles;
use \App\Http\Controllers\API\Dashboard\SupplierController;

Route::prefix('admin')
    ->middleware([
        'auth:sanctum',
        'verified',
        'role:' . UserRoles::SuperAdmin->value . '|' . UserRoles::Admin->value
    ])
    ->name('admin.')
    ->group(function () {
        Route::resource('suppliers', SupplierController::class);
    });
