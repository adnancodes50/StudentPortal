<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLogIn extends Model
{
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

   // In your model
protected $encrypted = [
    'student_login_password' // Encrypt at database level
];

    protected $casts = [
        'password_reset_status' => 'boolean',
        'is_deleted' => 'boolean',
        'blocked' => 'boolean',
        'creation_date' => 'datetime',
        'updation_date' => 'datetime'
    ];

    /**
     * Relationship to StudentModel
     */
    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'student_id');
    }
}
