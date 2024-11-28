<?php

use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Classroom\SubjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\LogUserAccess;
use App\Http\Controllers\Auth\CSRFController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Middleware\ClearCookiesOnCSRFError;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\School\AcademicYear;

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
    // Route::get('/email/cancel-change', [ProfileController::class, 'cancelChangeEmail'])
    // ->name('email.cancel-change');
});
Route::get('/email/verify-new/{user}/{email}', [ProfileController::class, 'verifyNewEmail'])
->name('email.verify-new');

// Rute untuk memproses verifikasi email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::group(['middleware' => 'guest'], function () {
    // Route::get('/signup', [SignupController::class, 'showRegistrationForm'])->name('register');
    // Route::post('/signup_process', [SignupController::class, 'register'])->name('register.submit');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login_process', [LoginController::class, 'login'])->name('login.submit');

    Route::prefix('password')->group(function () {
        Route::get('/', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.index');
        Route::get('/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::patch('/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->middleware('throttle:1,1')
        ->name('password.email');
        Route::get('/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->middleware('signed')
        ->name('password.reset');
        Route::patch('/resetpassword', [ResetPasswordController::class, 'reset'])->name('password.update');
    });
});

Route::post('/refresh-csrf-token', [CSRFController::class, 'refresh'])->name('csrf.refresh');

Route::middleware(['web', 'auth', LogUserAccess::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout');
    Route::get('/logout', [LogoutController::class, 'logout'])
    ->name('logout.get');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.index');
        Route::get('/view', [ProfileController::class, 'show'])->name('profile.view');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/change_password', [ProfileController::class, 'editPassword'])->name('profile.changepassword.edit');
        Route::patch('/change_password', [ProfileController::class, 'updatePassword'])->name('profile.changepassword.update');
    });
});

Route::middleware(['web', 'auth', 'verified', LogUserAccess::class])->group(function () {
    Route::prefix('admin')->group(function(){
        Route::prefix('role')->middleware(CheckUserRole::class . ':1')->group(function () {
            Route::get('/view', [RoleController::class, 'show'])->name('admin.authentication.roles.view');
            Route::post('/store', [RoleController::class, 'store'])->name('admin.authentication.roles.store');
            Route::put('/edit/{id}', [RoleController::class, 'update'])->name('admin.authentication.roles.update');
            Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('admin.authentication.roles.destroy');
        });
        Route::prefix('user')->middleware(CheckUserRole::class . ':1')->group(function () {
            Route::get('/view', [UserController::class, 'show'])->name('admin.authentication.users.view');
            Route::get('/create', [UserController::class, 'create'])->name('admin.authentication.users.create');
            Route::post('/store', [UserController::class, 'store'])->name('admin.authentication.users.store');
            Route::put('/edit/{id}', [UserController::class, 'update'])->name('admin.authentication.users.update');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('admin.authentication.users.destroy');
        });
    });
    Route::prefix('school')->group(function(){
        Route::prefix('academic_year')->middleware(CheckUserRole::class . ':1')->group(function () {
            Route::get('/view', [AcademicYear::class, 'show'])->name('school.academicYear.view');
            Route::post('/store', [AcademicYear::class, 'store'])->name( 'school.academicYear.store');
            Route::put('/edit/{id}', [AcademicYear::class, 'update'])->name('school.academicYear.update');
            Route::delete('/delete/{id}', [AcademicYear::class, 'destroy'])->name('school.academicYear.destroy');
        });
        Route::prefix('subject')->middleware(CheckUserRole::class . ':1,2')->group(function () {
            Route::get('/view', [SubjectController::class, 'show'])->name('classroom.subject.view');
            Route::post('/store', [SubjectController::class, 'store'])->name( 'classroom.subject.store');
            Route::put('/edit/{id}', [SubjectController::class, 'update'])->name('classroom.subject.update');
            Route::delete('/delete/{id}', [SubjectController::class, 'destroy'])->name('classroom.subject.destroy');
        });
    });
    // Route::prefix('classroom')->middleware(CheckUserRole::class . ':1')->group(function () {
    //     Route::get('/view', [AcademicYear::class, 'show'])->name('school.academicYear.view');
    //     Route::post('/store', [AcademicYear::class, 'store'])->name( 'school.academicYear.store');
    //     Route::put('/edit/{id}', [AcademicYear::class, 'update'])->name('school.academicYear.update');
    //     Route::delete('/delete/{id}', [AcademicYear::class, 'destroy'])->name('school.academicYear.destroy');
    // });
});