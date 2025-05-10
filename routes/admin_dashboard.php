<?php

use Illuminate\Support\Facades\Route;
use \App\Enums\UserRoles;
use \App\Http\Controllers\API\Dashboard\SupplierController;
use \App\Http\Controllers\API\Dashboard\CharityController;
use \App\Http\Controllers\API\Dashboard\ContactController;

Route::prefix('admin')
    ->middleware([
        'auth:sanctum',
        'verified',
        'role:' . UserRoles::SuperAdmin->value . '|' . UserRoles::Admin->value
    ])
    ->name('admin.')
    ->group(function () {

        Route::resource('suppliers', SupplierController::class);

        Route::resource('charities', CharityController::class);

        // contact messages
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [ContactController::class, 'index'])->name('index');
            Route::get('/{id}', [ContactController::class, 'show'])->name('show');
            Route::delete('/{id}', [ContactController::class, 'destroy'])->name('destroy');
            Route::post('/{id}', [ContactController::class, 'reply'])->name('reply');
        });

    });
