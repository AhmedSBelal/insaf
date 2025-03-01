<?php

use App\Http\Controllers\API\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('supplier/register', [\App\Http\Controllers\API\SupplierController::class, 'supplierRegister']);
Route::post('/email/resend', [VerifyEmailController::class, 'resend'])->name('verification.resend');
Route::post('/email/verify', [VerifyEmailController::class, 'verify'])->name('verification.verify');