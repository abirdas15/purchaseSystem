<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PaymentGatewayController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Routes requiring 'AdminAuthCheck' middleware
Route::group(['middleware' => 'AdminAuthCheck'], function () {
    // Login routes
    Route::get('auth/login', [AuthController::class, 'loginForm'])->name('admin.auth.login');
    Route::post('auth/login', [AuthController::class, 'login']);
});

// Routes requiring 'AdminAuthReqCheck' middleware
Route::group(['middleware' => 'AdminAuthReqCheck'], function () {
    // Logout route
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Admin home route
    Route::get('/', [HomeController::class, 'home'])->name('admin.home');

    // Payment gateway routes
    Route::get('payment/gateway', [PaymentGatewayController::class, 'paymentGatewayForm'])->name('admin.payment.gateway');
    Route::post('payment/gateway', [PaymentGatewayController::class, 'paymentGatewaySave']);
});

