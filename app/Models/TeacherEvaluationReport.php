<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherEvaluationReport extends Model
{
    use HasFactory;

    protected $table = 'teachers_evaluations_reports';

    protected $primaryKey = 'teacher_evaluation_report_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false; // because you only have created_at

    protected $fillable = [
        'student_id',
        'teacher_id',
        'session_id',
        'class_id',
        'course_id',
        'question_id',
        'selected_option_id',
    ];
}
