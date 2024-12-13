<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\LogUserAccess;
use App\Http\Controllers\Auth\CSRFController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\School\AcademicYear;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Classroom\SubjectController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Classroom\ClassListController;
use App\Http\Controllers\Classroom\ClassListStudentController;
use App\Http\Controllers\Classroom\MasterClassController;
use App\Http\Controllers\Classroom\ClassListTeacherController;
use App\Http\Controllers\Classroom\MasterClassStudentController;

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
    Route::prefix('master-classes')->group(function () {
        Route::get('/', [MasterClassStudentController::class, 'showEnrolled'])
        ->name( 'master-class.enrolled-class');
        Route::get('/archived-class', [MasterClassStudentController::class, 'showArchivedMClass'])
        ->name('master-class.archived-class');
        Route::get('/exited-class', [MasterClassStudentController::class, 'showExitedMClass'])
        ->name('master-class.exited-class');

        Route::get('/detail', [MasterClassStudentController::class, 'detailClass'])
        ->name( 'master-class.detail-class');

        Route::post('/join-class', [MasterClassStudentController::class, 'joinClass'])
        ->name('master-class.join-class');
        Route::put('/exit-class', [MasterClassStudentController::class, 'exitClass'])
        ->name('master-class.exit-class');
        Route::put('/rejoin-class', [MasterClassStudentController::class, 'rejoinClass'])
        ->name('master-class.rejoin-class');
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
    Route::prefix('classroom')->group(function(){
        Route::prefix('masterClass')->middleware(CheckUserRole::class . ':1,2')->group(function () {
            Route::get('/view', [MasterClassController::class, 'show'])->name('classroom.masterClass.view');
            Route::post('/store', [MasterClassController::class, 'store'])->name( 'classroom.masterClass.store');
            Route::put('/edit/{id}', [MasterClassController::class, 'update'])->where(['id' => '[0-9]+'])->name('classroom.masterClass.update');
            Route::delete('/delete/{id}', [MasterClassController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('classroom.masterClass.destroy');
                
            Route::prefix('manage/{masterClass_id}')->where(['masterClass_id' => '[0-9]+'])->group(function () {
    
                // MasterClass Student Management
                Route::prefix('students')->group(function () {
                    Route::get('/', [MasterClassStudentController::class, 'view_students'])->name('master_class_students.view_students');
                    Route::get('/create', [MasterClassStudentController::class, 'create_students'])->name('master_class_students.create_students');
                    Route::post('/store', [MasterClassStudentController::class, 'store_student'])->name('master_class_students.store_student');
                    Route::delete('/delete/{id}', [MasterClassStudentController::class, 'delete_student'])->where(['id' => '[0-9]+'])->name('master_class_students.delete');
                });
            
                // Classlist Management
                Route::prefix('class_lists')->group(function () {
                    Route::get('/', [ClassListController::class, 'index'])->name('classroom.masterClass.manage.index');
                    Route::get('/create', [ClassListController::class, 'create'])->name('class_lists.add');
                    Route::post('/store', [ClassListController::class, 'store'])->name('class_lists.store');
                    Route::put('update/{id}', [ClassListController::class, 'update'])->name('class_lists.update');
                    Route::delete('delete/{id}', [ClassListController::class, 'destroy'])->name('class_lists.destroy');

                    // Classlist Teacher Management
                    Route::prefix('{class_id}/teacher')->group(function () {
                        Route::get('/', [ClassListTeacherController::class, 'index'])->name('class_lists.teacher.index');
                        Route::post('/store', [ClassListTeacherController::class, 'store'])->name('class_lists.teacher.store');
                        Route::delete('delete/{teacher_id}', [ClassListTeacherController::class, 'destroy'])->name('class_lists.teacher.destroy');
                    });
                
                    // Classlist Student Management
                    Route::prefix('{class_id}/student')->group(function () {
                        Route::get('/', [ClassListStudentController::class, 'index'])->name('class_lists.student.index');
                        Route::post('/store', [ClassListStudentController::class, 'store'])->name('class_lists.student.store');
                        Route::delete('/delete/{student_id}', [ClassListStudentController::class, 'destroy'])->name('class_lists.student.destroy');
                    });
                });
            });
        });
    });
    // Route::prefix('classlist')->middleware(CheckUserRole::class . ':1,2')->group(function () {
    //     Route::get('/view', [SubjectController::class, 'show'])->name('classroom.subject.view');
    //     Route::post('/store', [SubjectController::class, 'store'])->name( 'classroom.subject.store');
    //     Route::put('/edit/{id}', [SubjectController::class, 'update'])->name('classroom.subject.update');
    //     Route::delete('/delete/{id}', [SubjectController::class, 'destroy'])->name('classroom.subject.destroy');
    // });
    // Route::prefix('classroom')->middleware(CheckUserRole::class . ':1')->group(function () {
    //     Route::get('/view', [AcademicYear::class, 'show'])->name('school.academicYear.view');
    //     Route::post('/store', [AcademicYear::class, 'store'])->name( 'school.academicYear.store');
    //     Route::put('/edit/{id}', [AcademicYear::class, 'update'])->name('school.academicYear.update');
    //     Route::delete('/delete/{id}', [AcademicYear::class, 'destroy'])->name('school.academicYear.destroy');
    // });
});