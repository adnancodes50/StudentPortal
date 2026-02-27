<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StudentLogIn extends Authenticatable
{
    use Notifiable;

    protected $table = "students_logins";
    protected $primaryKey = "student_login_id";
    public $timestamps = false;

    protected $fillable = [
        'student_login_name',
        'student_login_password',
        'password_reset_status',
        'student_id',
        'is_deleted',
        'blocked',
        'creation_date',
        'updation_date'
    ];

    protected $casts = [
        'password_reset_status' => 'boolean',
        'is_deleted' => 'boolean',
        'blocked' => 'boolean',
        'creation_date' => 'datetime',
        'updation_date' => 'datetime'
    ];

    protected $hidden = [
        'student_login_password',
    ];

    public function getAuthPassword()
    {
        return $this->student_login_password;
    }

    /**
     * Relationship to StudentModel
     */
    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'student_id');
    }
}
