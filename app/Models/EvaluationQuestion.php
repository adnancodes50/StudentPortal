<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    use HasFactory;

    protected $table = 'evaluation_questions';

    protected $primaryKey = 'evaluation_question_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false; // no created_at / updated_at

    protected $fillable = [
        'evaluation_question_title',
    ];
}
