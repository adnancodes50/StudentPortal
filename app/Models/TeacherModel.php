<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\TeacherClasses;
use App\Models\ClassModel;
use App\Models\CoursesModel;
use App\Models\Session;

class TeacherModel extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    protected $primaryKey = 'teacher_id';
    public $timestamps = true;

    protected $fillable = [
        'teacher_first_name',
        'teacher_last_name',
        'email',
        'teacher_date_of_birth',
        'teacher_gender',
        'teacher_designation',
        'teacher_date_of_joining',
        'teacher_CNIC',
        'teacher_type',
        'qualifications',
        'experiences',
        'about',
        'teacher_deleted',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'teacher_date_of_birth' => 'date',
        'teacher_date_of_joining' => 'datetime',
        'teacher_deleted' => 'boolean',
        'teacher_type' => 'integer',
    ];

    // Created by user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Updated by user
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // All assigned class/course/session records
    public function teacherClasses()
    {
        return $this->hasMany(TeacherClasses::class, 'teacher_id');
    }

    // Relationship to classes via pivot
    public function classes()
    {
        return $this->belongsToMany(
            ClassModel::class,
            'teachers_classes_courses',
            'teacher_id',
            'classs_id'
        )->withPivot(['course_id', 'class_section', 'session_id', 'rate', 'course_details']);
    }

    // Relationship to courses via pivot
    public function courses()
    {
        return $this->belongsToMany(
            CoursesModel::class,
            'teachers_classes_courses',
            'teacher_id',
            'course_id'
        )->withPivot(['classs_id', 'class_section', 'session_id', 'rate', 'course_details']);
    }

    // Relationship to sessions via pivot
    public function sessions()
    {
        return $this->belongsToMany(
            Session::class,
            'teachers_classes_courses',
            'teacher_id',
            'session_id'
        )->withPivot(['classs_id', 'course_id', 'class_section', 'rate', 'course_details']);
    }



// Relationship to kin details
    public function kinDetails()
    {
        return $this->hasMany(TeacherKin::class, 'teacher_id');
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return trim($this->teacher_first_name . ' ' . $this->teacher_last_name);
    }

    // Alias accessor so code can safely use $teacher->teacher_name
    public function getTeacherNameAttribute()
    {
        return $this->getFullNameAttribute();
    }







    public function dateSheetsAsTeacher1()
{
    return $this->hasMany(\App\Models\DateSheet::class, 'teacher_id');
}

public function dateSheetsAsTeacher2()
{
    return $this->hasMany(\App\Models\DateSheet::class, 'teacher_id2');
}

public function dateSheetsAsTeacher3()
{
    return $this->hasMany(\App\Models\DateSheet::class, 'teacher_id3');
}

public function dateSheetsAsTeacher4()
{
    return $this->hasMany(\App\Models\DateSheet::class, 'teacher_id4');
}

}




