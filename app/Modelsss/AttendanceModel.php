<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceModel extends Model
{
    use HasFactory;

    protected $table = 'student_attendance';

    // Correct primary key settings
    protected $primaryKey = 'student_attendance_id';
    public $incrementing = true;
    protected $keyType = 'int';         

    // Disable default timestamps if you manage them manually
    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'student_id',
        'class_id',
        'section',
        'course_id',
        'status',
        'created_by',
        'updated_by',
        'lec_no',
        'type_status',
        'creation_date',
        'updated_date',
    ];

    // ---------------- Relationships ----------------

    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function course()
    {
        return $this->belongsTo(CoursesModel::class, 'course_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
