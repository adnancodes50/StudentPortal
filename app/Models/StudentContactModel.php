<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentContactModel extends Model
{
    protected $table = 'student_contacts';
    protected $primaryKey = 'student_contact_id';
    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'student_area',
        'student_contact_phone_no',
        'student_contact_mobile_no',
        'student_contact_email',
        'student_contact_permanent_address',
        'student_contact_postal_address',
        'student_contact_domicile_district',
        'student_contact_domicile_province',
        'student_contact_city',
        'student_contact_nantionality',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $attributes = [
        'student_area' => 'rural', // Default to rural or urban based on your form
        'student_contact_nantionality' => 'Pakistani', // Default nationality
        'student_contact_email' => null,
        'student_contact_postal_address' => null,
    ];

    // Validation rules (optional)
    public static $rules = [
        'student_contact_phone_no' => 'required|string|max:20',
        'student_contact_mobile_no' => 'required|string|max:20',
        'student_contact_permanent_address' => 'required|string|max:500',
        'student_contact_domicile_district' => 'required|string|max:255',
        'student_contact_domicile_province' => 'required|string|max:255',
        'student_contact_city' => 'required|string|max:255',
    ];

    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }



}
