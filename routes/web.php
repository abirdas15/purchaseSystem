<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Routes group for authentication-related routes, requiring 'AuthCheck' middleware
Route::group(['prefix' => 'auth', 'middleware' => 'AuthCheck'], function () {
    // Registration routes
    Route::get('register', [AuthController::class, 'registerForm'])->name('auth.register');
    Route::post('register', [AuthController::class, 'register']);

    // Login routes
    Route::get('login', [AuthController::class, 'loginForm'])->name('auth.login');
    Route::post('login', [AuthController::class, 'login']);

    // Email verification success route
    Route::get('email/verify/success/{id}', [AuthController::class, 'emailVerifySuccessForm'])->name('auth.email.verify.success');

    // OTP verification routes
    Route::get('verify/otp/{id}', [AuthController::class, 'verifyOtpForm'])->name('auth.verify.otp');
    Route::post('verify/otp/{id}', [AuthController::class, 'verifyOtp']);

    // Email verification route
    Route::get('email/verify/{id}', [AuthController::class, 'emailVerifyForm'])->name('auth.email.verify');

    // Resend email verification route
    Route::post('email/resend/verification', [AuthController::class, 'emailResendVerification'])->name('auth.resend.verification');
});

// Routes group for authenticated user routes, requiring 'auth' middleware
Route::group(['middleware' => 'auth'], function () {
    // Logout route
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Home route
    Route::get('/', [HomeController::class, 'home'])->name('home');

    // Checkout routes
    Route::get('checkout/{id}', [CheckoutController::class, 'checkoutForm'])->name('checkout');
    Route::post('checkout/{id}', [CheckoutController::class, 'checkout']);

    // Payment routes
    Route::get('payment/{product_id}/{order_id}', [PaymentController::class, 'payment'])->name('payment');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
});
