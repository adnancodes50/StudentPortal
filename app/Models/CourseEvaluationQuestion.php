<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function reports(): HasMany
    {
        return $this->hasMany(CourseEvaluationReport::class, 'question_id', 'evaluation_question_id');
    }
}
