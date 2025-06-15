<?php

use Illuminate\Support\Facades\Route;
use \App\Enums\UserRoles;
use App\Http\Controllers\API\Dashboard\AdminController;
use \App\Http\Controllers\API\Dashboard\SupplierController;
use \App\Http\Controllers\API\Dashboard\CharityController;
use \App\Http\Controllers\API\Dashboard\ContactController;
use App\Models\Admin;

Route::prefix('admin')
    ->middleware([
        'auth:sanctum',
        'verified',
        'role:' . UserRoles::SuperAdmin->value . '|' . UserRoles::Admin->value
    ])
    ->name('admin.')
    ->group(function () {

        Route::get("/overview" , [AdminController::class, 'overview'])
            ->name('overview');
        Route::get('/admins', [\App\Http\Controllers\API\Dashboard\AdminController::class, 'index'])->name('admins.index');
        Route::resource('suppliers', SupplierController::class);

        Route::resource('charities', CharityController::class);

        // Products Routes
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [\App\Http\Controllers\API\Dashboard\ProductController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\API\Dashboard\ProductController::class, 'show'])->name('show');
            Route::delete('/{id}', [\App\Http\Controllers\API\Dashboard\ProductController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\API\Dashboard\OrderController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\API\Dashboard\OrderController::class, 'show'])->name('show');
            Route::delete('/{id}', [\App\Http\Controllers\API\Dashboard\OrderController::class, 'destroy'])->name('destroy');
        });

        Route::apiResource('admins', \App\Http\Controllers\API\Dashboard\AdminController::class)->middleware('role:' . UserRoles::SuperAdmin->value);

//
        Route::get("settings/me", [\App\Http\Controllers\API\Dashboard\AdminController::class, 'me'])
                    ->name('admins.me');
        Route::get('/settings/{id}' , [\App\Http\Controllers\API\Dashboard\SettingController::class , 'show']);
        Route::put('/settings' , [\App\Http\Controllers\API\Dashboard\SettingController::class , 'update']);
        Route::put('/settings/password' , [\App\Http\Controllers\API\Dashboard\SettingController::class , 'updatePassword']);
        Route::delete('/settings/delete-account' , [\App\Http\Controllers\API\Dashboard\SettingController::class , 'deleteAccount']);
        Route::post('/settings/signout' , [\App\Http\Controllers\API\Dashboard\SettingController::class , 'signOut']);
        Route::post('/settings/signout/all' , [\App\Http\Controllers\API\Dashboard\SettingController::class , 'signOutAll']);
        Route::post('/settings/signout/other' , [\App\Http\Controllers\API\Dashboard\SettingController::class , 'signOutOther']);


        // contact messages
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [ContactController::class, 'index'])->name('index');
            Route::get('/{id}', [ContactController::class, 'show'])->name('show');
            Route::delete('/{id}', [ContactController::class, 'destroy'])->name('destroy');
            Route::post('/{id}', [ContactController::class, 'reply'])->name('reply');
        });

    });
