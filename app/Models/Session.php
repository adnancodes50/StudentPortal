<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

// use  App\Models\ClassModel;

class Session extends Model
{
    protected $table = 'ums_sessions';

    protected $primaryKey = 'session_id';
    public $timestamps = true;



    protected $fillable = [
        'session_type',
        'session_year',
        'session_timing',
        'is_current',
        'is_evaluation_session',
        'is_admission_session',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    // app/Models/Session.php

public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function updatedBy()
{
    return $this->belongsTo(User::class, 'updated_by');
}

public function degrees()
{
    return $this->belongsToMany(Degrees::class, 'session_degrees', 'session_id', 'degree_id')
        ->using(\App\Models\SessionDegree::class)
        ->withPivot([
            'tution_fee',
            'other_fee',
            'admission_fee',
            'registration_fee',
            'security_fee',
            'misc'
        ]);
}

public function classes()
{
    return $this->belongsToMany(ClassModel::class, 'session_classes', 'session_id', 'class_id')
        ->withPivot(['section']);
}

public function classesCourses()
{
    return $this->hasMany(ClassesCourses::class, 'session_id');
}


public function teacherCourses()
{
    return $this->hasMany(TeacherClasses::class, 'session_id');
}

public function attendances()
{
    return $this->hasMany(AttendanceModel::class, 'session_id');
    }

    // Accessor for session year as 'year'
    public function getYearAttribute()
    {
        return $this->session_year;
    }



    public function timeTables()
{
    return $this->hasMany(\App\Models\TimeTableMOdel::class, 'session_id', 'session_id');
}


// public function session()
// {
//     return $this->belongsTo(Session::class, 'session_id');
// }


public function admissionInquiries(): HasMany
{
    return $this->hasMany(
        \App\Models\AdmissionInquiry::class,
        'session_id',
        'session_id'
    );
}



}
