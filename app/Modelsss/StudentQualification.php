<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQualification extends Model
{
    protected $table = 'students_qualifications';
    protected $primaryKey = 'qualification_id';
    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'qualification_level',
        'qualification_degree_or_certificate',
        'qualification_year_of_passing',
        'borad_univeristy', // Note: Typo here (should be 'board_university')
        'qualification_marks_total',
        'qualification_marks_obtained',
        'qualification_grade',
        'qualification_cgpa',
        'qualification_major_subjects',
    ];

    protected $casts = [
        'qualification_year_of_passing' => 'integer',
        'qualification_marks_total' => 'integer',
        'qualification_cgpa' => 'float',
        'created_at' => 'datetime',
    ];

    protected $attributes = [
        'qualification_grade' => null,
        'qualification_cgpa' => 0.0,
        'qualification_major_subjects' => null,
        'qualification_marks_total' => 0,
    ];

    public function students()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }
}
