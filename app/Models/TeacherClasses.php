<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherClasses extends Model
{
    protected $table = "teachers_classes_courses";
    protected $primaryKey = "teacher_course_id";
    public $timestamps = true;

    protected $fillable = [
        'teacher_id',
        'classs_id',
        'course_id',
        'class_section',  // Fixed typo from 'class_secction'
        'session_id',
        'rate',
        'course_details',
        'created_by',
        'updated_by',
        'created_at',     // Added since timestamps is true
        'updated_at',      // Added since timestamps is true
    ];

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'rate' => 'float',  // Added casting for rate
    ];


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor for creator (teacher) name
    public function getCreatorNameAttribute()
    {
        return $this->creator ? $this->creator->name : null;
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function teacherClasses()
{
    return $this->hasMany(TeacherClasses::class, 'teacher_id');
}

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'classs_id');
    }

    public function course()
    {
        return $this->belongsTo(CoursesModel::class, 'course_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function teacher()
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id', 'teacher_id');
    }

    public function degree()
    {
        return $this->belongsTo(Degrees::class, 'degree_id');
    }
}
