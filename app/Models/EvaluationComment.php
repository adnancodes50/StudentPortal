<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationComment extends Model
{
    use HasFactory;

    protected $table = 'evaluation_comments';

    protected $primaryKey = 'evaluation_comment_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false; // no created_at / updated_at columns

    protected $fillable = [
        'evaluation_comment_course',
        'evaluation_comment_instructer',
        'student_id',
        'teacher_id',
        'session_id',
        'class_id',
        'course_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (Optional but Recommended)
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
