<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
    protected $table = 'parents';
    protected $primaryKey = 'parent_id';
    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'parent_name',
        'parent_cnic',
        'parent_occupation',
        'parent_annual_income',
    ];

    protected $casts = [
        'parent_annual_income' => 'integer',
        'created_at' => 'datetime',
    ];

    protected $attributes = [
        'parent_annual_income' => 0,
        'parent_occupation' => 'Not Specified',
    ];

    // Relationship constants (optional)
    const RELATIONSHIP_FATHER = 'father';
    const RELATIONSHIP_MOTHER = 'mother';
    const RELATIONSHIP_GUARDIAN = 'guardian';

    // Validation rules (optional)
    public static $rules = [
        'parent_name' => 'required|string|max:255',
        'parent_cnic' => 'required|string|regex:/^\d{5}-\d{7}-\d{1}$/',
        'parent_occupation' => 'required|string|max:255',
        'parent_annual_income' => 'required|integer|min:0',
    ];

    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }




}
