<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEvaluationQuestion extends Model
{
    use HasFactory;

    protected $table = 'course_evaluation_questions';

    protected $primaryKey = 'evaluation_question_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false; // change if you have timestamps

    protected $fillable = [
        'evaluation_question_title',
    ];
}
