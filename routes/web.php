<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogUserAccess;
use App\Http\Controllers\Access\RouteAccess;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Access\RoutePrefixAccess;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::middleware(['auth', 'verified', LogUserAccess::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    })
    ->name('dashboard');

    Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout');
    
    //Original Template
    Route::middleware(['auth', 'verified'])->get('/original', function () {
        return view('dashboard.original');
    })->name('original');

    //Admin Settings
    Route::prefix('admin')->group(function(){
        Route::prefix('page_access')->group(function () {
            Route::prefix('route')->group(function () {
                Route::get('/view', [RouteAccess::class, 'view'])->name('admin.page_access.route.view');
                Route::get('/create', [RouteAccess::class, 'create'])->name('admin.page_access.route.create');
                // Route::post('/add', [RouteAccess::class, 'store'])->name('admin.page_access.route.store');
                Route::get('/edit/{id}', [RouteAccess::class, 'edit'])->name('admin.page_access.route.edit');
                Route::post('/edit/{id}', [RouteAccess::class, 'update'])->name('admin.page_access.route.update');
                Route::get('/setaccess/{id}', [RouteAccess::class, 'setAccess'])->name('admin.page_access.route.setaccess');
                Route::post('/setaccess/{id}', [RouteAccess::class, 'saveAccess'])->name('admin.page_access.route.saveaccess');    
            });
            Route::prefix('route_prefix')->group(function () {
                Route::get('/view', [RoutePrefixAccess::class, 'show'])->name('admin.page_access.route_prefix.view');
                Route::get('/create', [RoutePrefixAccess::class, 'create'])->name('admin.page_access.route_prefix.create');
                Route::post('/store', [RoutePrefixAccess::class, 'store'])->name('admin.page_access.route_prefix.store');
                Route::get('/edit/{id}', [RoutePrefixAccess::class, 'edit'])->name('admin.page_access.route_prefix.edit');
                Route::post('/edit/{id}', [RoutePrefixAccess::class, 'update'])->name('admin.page_access.route_prefix.update');
                Route::delete('/delete/{id}', [RoutePrefixAccess::class, 'destroy'])->name('admin.page_access.route_prefix.destroy');
            });
        });
    });
});


