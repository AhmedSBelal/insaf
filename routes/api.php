<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('supplier/register', [\App\Http\Controllers\API\SupplierController::class, 'supplierRegister']);
