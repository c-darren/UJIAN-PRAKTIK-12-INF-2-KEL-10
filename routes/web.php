<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogUserAccess;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Middleware\CheckUserRole;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::middleware(['web', 'auth', 'verified', LogUserAccess::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    })
    ->name('dashboard');

    Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout');
    
    Route::prefix('admin')->group(function(){
        Route::prefix('role')->middleware(CheckUserRole::class . ':2,3')->group(function () {
            Route::get('/view', [RoleController::class, 'show'])->name('admin.authentication.roles.view');
            Route::get('/create', [RoleController::class, 'create'])->name('admin.authentication.roles.create');
            Route::post('/store', [RoleController::class, 'store'])->name('admin.authentication.roles.store');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('admin.authentication.roles.edit');
            Route::post('/edit/{id}', [RoleController::class, 'update'])->name('admin.authentication.roles.update');
            Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('admin.authentication.roles.destroy');
        });
    });
});