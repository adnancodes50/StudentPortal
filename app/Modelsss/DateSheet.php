<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DateSheet extends Model
{
    protected $table = 'date_sheets';
    protected $primaryKey = 'date_sheet_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Enable timestamps and define custom column names
    public $timestamps = true;
    const CREATED_AT = 'created_at';
const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'session_id',
        'class_id',
        'class_section',
        'slip_term',
        'course_id',
        'paper_code',
        'paper_title',
        'paper_type',
        'date',
        'time_from',
        'time_to',
        'room',
        'teacher_id',
        'teacher_id2',
        'teacher_id3',
        'teacher_id4',
        'invigilators',
        'created_by',
        'updated_by'
    ];

    /* ------------------------------------
    | RELATIONSHIPS
    |-------------------------------------*/

    // SESSION Relationship
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    // CLASS Relationship
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // COURSE Relationship
    public function course()
    {
        return $this->belongsTo(CoursesModel::class, 'course_id');
    }

    // TEACHERS Relationships
    public function teacher1()
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id');
    }

    public function teacher2()
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id2');
    }

    public function teacher3()
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id3');
    }

    public function teacher4()
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id4');
    }

    /* ------------------------------------
    | MODEL EVENTS FOR CREATED_BY / UPDATED_BY
    |-------------------------------------*/
    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }





    // inside the DateSheet model class

/**
 * Return readable label for slip_term (exam type)
 */
public function getSlipTermLabelAttribute()
{
    if ($this->slip_term === 1) return 'Mid Term';
    if ($this->slip_term === 2) return 'Final Term';
    return 'N/A';
}

/**
 * Return readable label for paper_type
 * Assumes: 1 => Theory, 2 => Practical
 */
public function getPaperTypeLabelAttribute()
{
    if ($this->paper_type === 1 || $this->paper_type === '1') return 'Theory';
    if ($this->paper_type === 2 || $this->paper_type === '2') return 'Practical';
    return 'N/A';
}

}
