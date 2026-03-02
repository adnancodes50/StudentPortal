<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClassesCoursesModel extends Model
{
    protected $table = "students_classes_courses";
    protected $primaryKey = "students_classe_course_id";

    // Enable timestamps because you have created_at & updated_at
    public $timestamps = true;

    protected $fillable = [
        "student_id",
        "student_section",
        "class_id",
        "session_id",
        "course_id",
        "created_by",
        "updated_by"
    ];

    // Relationships
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function course()
    {
        return $this->belongsTo(CoursesModel::class, 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
