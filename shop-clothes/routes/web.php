<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);

        Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AdminAuthController::class, 'register']);

        Route::get('forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('forgot-password', [AdminAuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('reset-password/{token}', [AdminAuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [AdminAuthController::class, 'reset'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    });
});

Route::name('client.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [ClientAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [ClientAuthController::class, 'login']);

        Route::get('register', [ClientAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [ClientAuthController::class, 'register']);

        Route::get('forgot-password', [ClientAuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('forgot-password', [ClientAuthController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('reset-password/{token}', [ClientAuthController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [ClientAuthController::class, 'reset'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [ClientAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [ClientAuthController::class, 'dashboard'])->name('dashboard');
    });
});
