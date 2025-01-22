<?php

use App\Http\Controllers\API\Auth\ForgetPasswordController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\Orders\OrderController;
use App\Http\Controllers\API\Products\ProductController;
use App\Http\Controllers\API\User\ProfileController;
use App\Http\Controllers\API\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('login', [LoginController::class, 'login']);
Route::post('forget', [ForgetPasswordController::class, 'forget'])->name('forget');
Route::post('verify-otp', [ForgetPasswordController::class, 'verify'])->name('verify');
Route::post('reset', [ResetPasswordController::class, 'reset'])->name('reset');

Route::middleware(['auth:sanctum'])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */
//    Route::delete('users/delete-all', [UserController::class, 'destroyAll']);
    Route::post('users/{id}/restore', [UserController::class, 'restore']);
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete']);
    Route::apiResource('users', UserController::class);

    //Profile Routes
    Route::get('me', [ProfileController::class, 'user']);
    Route::post('update-profile', [ProfileController::class, 'updateProfile']);
    Route::post('destroy-avatar', [ProfileController::class, 'destroyAvatar']);
    Route::post('logout', [LoginController::class, 'logout']);

    // Product routes
    Route::apiResource('products', ProductController::class);
    Route::post('products/{id}/restore', [ProductController::class, 'restore']);
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete']);

    // Order routes
    Route::apiResource('orders', OrderController::class);
    Route::post('orders/{id}/restore', [OrderController::class, 'restore']);
    Route::delete('orders/{id}/force-delete', [OrderController::class, 'forceDelete'])->name('orders.force-delete');
    Route::put('orders/change-status/{order}', [OrderController::class, 'changeStatus']);

});
