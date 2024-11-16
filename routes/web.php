<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\LogUserAccess;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\VerifyEmailController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Rute untuk verifikasi email
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [DashboardController::class, 'index'])
    ->name('verification.notice');
    Route::post('/email/resend', [VerifyEmailController::class, 'resend'])
    ->name('verification.resend');
});

// Rute untuk memproses verifikasi email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

// Rute untuk memproses verifikasi email baru
Route::get('/email/change/verify/{id}/{hash}', [ChangeEmailController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('email.change.verify');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::middleware(['web', 'auth', LogUserAccess::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout');
});

Route::middleware(['web', 'auth', 'verified', LogUserAccess::class])->group(function () {
    Route::prefix('admin')->group(function(){
        Route::prefix('role')->middleware(CheckUserRole::class . ':2,3')->group(function () {
            Route::get('/view', [RoleController::class, 'show'])->name('admin.authentication.roles.view');
            Route::post('/store', [RoleController::class, 'store'])->name('admin.authentication.roles.store');
            Route::put('/edit/{id}', [RoleController::class, 'update'])->name('admin.authentication.roles.update');
            Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('admin.authentication.roles.destroy');
        });
        Route::prefix('user')->middleware(CheckUserRole::class . ':2,3')->group(function () {
            Route::get('/view', [UserController::class, 'show'])->name('admin.authentication.users.view');
            Route::get('/create', [UserController::class, 'create'])->name('admin.authentication.users.create');
            Route::post('/store', [UserController::class, 'store'])->name('admin.authentication.users.store');
            Route::put('/edit/{id}', [UserController::class, 'update'])->name('admin.authentication.users.update');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('admin.authentication.users.destroy');
        });
    });
});