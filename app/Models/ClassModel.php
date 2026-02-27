<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'class_id';
    public $timestamps = false;

    protected $fillable = [
        'class_name',
        'class_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    // Relationship to get the user who created the class
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship to get the user who last updated the class
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sessions()
    {
        return $this->belongsToMany(\App\Models\Session::class, 'session_classes', 'class_id', 'session_id')
            ->withPivot(['section']);
    }

    public function classesCourses()
{
    return $this->hasMany(ClassesCourses::class, 'class_id');
}

public function teacherCourses()
{
    return $this->hasMany(TeacherClasses::class, 'class_id');
}

public function attendances()
{
    return $this->hasMany(AttendanceModel::class, 'class_id');
}


public function timeTables()
{
    return $this->hasMany(\App\Models\TimeTableMOdel::class, 'class_id', 'class_id');
}

public function dateSheets()
{
    return $this->hasMany(\App\Models\DateSheet::class, 'class_id');
}



}
