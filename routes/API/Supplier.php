<?php

use App\Http\Controllers\API\Supplier\CategoriesController;
use App\Http\Controllers\API\Supplier\HomeController;
use App\Http\Controllers\API\Supplier\Orderscontroller;
use App\Http\Controllers\API\Supplier\ProductsController;
use App\Http\Controllers\API\Supplier\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('me' , [ProfileController::class, 'me']);
Route::put('me' , [ProfileController::class, 'update']);
Route::put('update-password' , [ProfileController::class, 'updatePassword']);
Route::post('delete-account' , [ProfileController::class, 'deleteAccount']);
Route::group(['prefix' => 'signout' , 'controller' => ProfileController::class] , function () {
    Route::post('curret-device' , 'signOutCurrentDevice');
    Route::post('other-devices' , 'signOutOtherDevices');
    Route::post('all-devices' , 'signOutAllDevices');
});

//Home Route
Route::get('home' , HomeController::class);

//Products Route
Route::apiResource('products' , ProductsController::class);

//Categories Route
Route::get('categories' , CategoriesController::class);

//Orders Controller
Route::get('orders' , [Orderscontroller::class, 'index']);
Route::put('orders/{id}/status' , [Orderscontroller::class, 'updateStatus']);



