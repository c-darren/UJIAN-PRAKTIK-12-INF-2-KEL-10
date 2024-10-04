<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\User_log\UserLogController;
use App\Http\Controllers\User_log\LogCategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

//Original Template
Route::middleware(['auth', 'verified'])->get('/original', function () {
    return view('dashboard.original');
})->name('original');

Route::resource('log-categories', LogCategoryController::class);
Route::get('/user/logs', [UserLogController::class, 'index'])->middleware('auth');
