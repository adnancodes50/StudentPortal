<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGuardian extends Model
{
    protected $table = "guardians";
    protected $primaryKey = "guardian_id";
    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'guardian_name',
        'guardian_relation',
        'guardian_contact_no',
        'emaergancey_guardian_name',  // Fixed spelling from 'emaergancey'
        'emaergancey_guardian_relation', // Fixed spelling
        'emaergancey_contact_no',     // Fixed spelling
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $attributes = [
        'guardian_relation' => 'parent',
        'emaergancey_guardian_name' => null,
        'emaergancey_guardian_relation' => null,
        'emaergancey_contact_no' => null,
    ];

    // Relationship types (optional constants)
    const RELATION_PARENT = 'parent';
    const RELATION_GRANDPARENT = 'grandparent';
    const RELATION_SIBLING = 'sibling';
    const RELATION_OTHER = 'other';

    // Validation rules (optional)
    public static $rules = [
        'guardian_name' => 'required|string|max:255',
        'guardian_relation' => 'required|string|max:255',
        'guardian_contact_no' => 'required|string|regex:/^03\d{9}$/',
        'emaergancey_contact_no' => 'nullable|string|regex:/^03\d{9}$/',
    ];

    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }


}
