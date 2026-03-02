<?php
// app/Models/TeacherLectureLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLectureLog extends Model
{
    protected $table = "teacher_lecture_log";
    protected $primaryKey = 'teacher_lecture_log_id';

    public $timestamps = false; // Disable timestamps if not used
    protected $fillable = [
        'session_id',
        'teacher_id',
        'class_id',
        'section',
        'course_id',
        'time_from',
        'time_to',
        'lecture_date',
        'lec_no',
        'type_status',
        'created_by',
        'updated_by'
    ];

    // Relationship with Session
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    // Relationship with Teacher
    public function teacher()
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id');
    }

    // Relationship with Class
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // Relationship with Course
    public function course()
    {
        return $this->belongsTo(CoursesModel::class, 'course_id');
    }

    // Relationship with Creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with Updater
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
