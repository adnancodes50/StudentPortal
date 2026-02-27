<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentModel extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $timestamps = false;

    protected $fillable = [
        'arid_reg_no',
        'student_first_name',
        'student_last_name',
        'student_cnic',
        'student_date_of_birth',
        'student_religion',
        'student_form_no',
        'student_catagorey',
        'categorey_of_reserved_seats',
        'student_joining_session',
        'joining_session_id',
        'degree_id',
        'student_picture_path',
        'student_section',
        'student_current_session',
        'student_gender',
        'student_timing_shift',
        'student_currunt_semester',
        'is_deleted',
        'evaluation_status',
        'crs_eval_status',
        'primary_email',
        'voucher_flag',
        'voucher_reason',
        'updated_by',
        'updated_date'
    ];

    protected $casts = [
        'student_date_of_birth' => 'date',
        'updated_date' => 'datetime',
        'is_deleted' => 'boolean',
        'evaluation_status' => 'integer',
        'crs_eval_status' => 'integer',
        'voucher_flag' => 'integer',
        'joining_session_id' => 'integer',
        'student_currunt_semester' => 'integer'
    ];

    protected $attributes = [
        'joining_session_id' => 0,
        'student_currunt_semester' => 1,
        'student_religion' => 'Islam',
        'is_deleted' => false,
        'arid_reg_no' => null,
        'evaluation_status' => 0,
        'crs_eval_status' => 0,
        'voucher_flag' => 0,
        'voucher_reason' => null,
        'categorey_of_reserved_seats' => null,
        'primary_email' => null,
        'updated_by' => null
    ];

       protected $appends = ['name'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set default values for dates
        $this->attributes['updated_date'] = now();
    }

    public function qualification()
    {
        return $this->hasMany(StudentQualification::class, 'student_id');
    }

    public function contacts()
    {
        return $this->hasOne(StudentContactModel::class, 'student_id');
    }

    public function parents()
    {
        return $this->hasOne(StudentParent::class, 'student_id');
    }

    public function guardians()
    {
        return $this->hasMany(StudentGuardian::class, 'student_id');
    }

    public function degree()
    {
        return $this->belongsTo(Degrees::class, 'degree_id');
    }
    public function session()
    {
        return $this->belongsTo(Session::class, 'student_current_session', 'session_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function joiningSession()
    {
        return $this->belongsTo(Session::class, 'joining_session_id', 'session_id');
    }


    public function login()
    {
        return $this->hasOne(StudentLogIn::class, 'student_id', 'student_id');
    }


    public function attendances()
{
    return $this->hasMany(AttendanceModel::class, 'student_id');
}

public function assignedCourses()
{
    return $this->hasMany(StudentClassesCoursesModel::class, 'student_id', 'student_id');
}

// In StudentModel.php
public function getNameAttribute()
{
    return $this->student_first_name . ' ' . $this->student_last_name;
}




}
