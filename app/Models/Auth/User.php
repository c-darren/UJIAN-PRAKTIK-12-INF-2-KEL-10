<?php

namespace App\Models\Auth;

use App\Http\Controllers\Classroom\ClassListTeacherController;
use App\Models\Classroom\ClassList;
use Illuminate\Notifications\Notifiable;
use App\Models\Classroom\MasterClassStudents;
use App\Notifications\SendEmailCreateAccount;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\CustomVerifyEmailNotification;
use App\Notifications\SuccessVerifyEmailNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'avatar',
        'role_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'email_verified_at'];

    public function role(): BelongsTo
    {
        //1 User memiliki 1 Role
        return $this->belongsTo(Role::class);
    }
    
    public function classes()
    {
        return $this->belongsToMany(ClassList::class, 'class_teachers', 'teacher_id', 'class_id');
    }

    public function masterClassEnrollments(){
        return $this->hasMany(MasterClassStudents::class);
    }

    public function masterClassStudents()
    {
        return $this->hasMany(MasterClassStudents::class, 'user_id');
    }

    // Relasi dengan ClassTeacher
    public function classTeachers()
    {
        return $this->hasMany(ClassListTeacherController::class, 'teacher_id');
    }

    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }

    public function sendEmailVerificationNotificationCustom(){
        $this->notify(new CustomVerifyEmailNotification($this->username));
    }

    public function successVerifyEmailNotification()
    {
        $this->notify(new SuccessVerifyEmailNotification($this->username));
    }

    public function sendEmailCreateAccount(){
        $this->notify(new SendEmailCreateAccount($this->username));
    }
}
