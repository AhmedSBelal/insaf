<?php

use App\Http\Controllers\API\VerifyEmailController;
use App\Http\Controllers\Auth\AuthController;

use Illuminate\Support\Facades\Route;

// register
Route::post('supplier/register', [\App\Http\Controllers\API\SupplierController::class, 'supplierRegister']);
Route::post('charity/register', [\App\Http\Controllers\API\CharityController::class, 'charityRegister']);

// verify email
Route::post('/email/resend', [VerifyEmailController::class, 'resend'])->name('verification.resend');
Route::post('/email/verify', [VerifyEmailController::class, 'verify'])->name('verification.verify');


// login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
