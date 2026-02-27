<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CoursesModel extends Model
{
    protected $table = "courses";
    protected $primaryKey = "course_id";
    public $timestamps = false;
    protected $fillable = [
        'course_code',
        'course_title',
        'course_credit_hours',
        'course_lab',
        'course_type',
        'course_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function classesCourses()
    {
        return $this->hasMany(ClassesCourses::class, 'course_id');
    }

    public function teacherCourses()
    {
        return $this->hasMany(TeacherClasses::class, 'course_id');
    }

    public function attendances()
    {
        return $this->hasMany(AttendanceModel::class, 'course_id');
    }

    public function timeTables()
{
    return $this->hasMany(\App\Models\TimeTableMOdel::class, 'course_id', 'course_id');
}
public function dateSheets()
{
    return $this->hasMany(\App\Models\DateSheet::class, 'course_id');
}


}



