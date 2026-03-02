<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'student_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(TeacherModel::class, 'teacher_id', 'teacher_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(CoursesModel::class, 'course_id', 'course_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class, 'session_id', 'session_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'class_id');
    }
}
