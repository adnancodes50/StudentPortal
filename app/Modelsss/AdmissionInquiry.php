<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class AdmissionInquiry extends Model
{
    use HasFactory;

    public const STATUS_LABELS = [
        1  => 'Inquiry',
        2  => 'Prospectus',
        3  => 'Form',
        4  => 'Admitted',
        5  => 'Admitted Dropped',
        6  => 'Admitted Freezed',
        7  => 'Admitted Refund',
        8  => 'Admitted Supply',
        9  => 'Admitted Form Dropped',
        10 => 'Form Not Interested',
        11 => 'Not Interested',
        12 => 'Waiting Fee',
        13 => 'Verifying Documents',
        14 => 'Documents Verified',
        15=> 'Student Admitted',
    ];

    protected $table = 'admission_inquries';

    protected $primaryKey = 'admission_inqurie_id';

    public $timestamps = true;

    protected $fillable = [
        // Personal Info
        'applied_name',
        'applied_fname',
        'applied_age',
        'applied_ph',
        'applied_cell',
        'applied_email',
        'applied_address',

        // Previous Education
        'pre_institute_name',
        'pre_course_start_month',
        'pre_course_start_year',
        'pre_course_end_month',
        'pre_course_end_year',
        'pre_course',
        'pre_course_grade',

        // Course Applying
        'apply_course',
        'apply_course_type',

        // Office Use
        'prospectus_number',
        'apply_ref',
        'apply_date_time',
        'remarks',
        'status',
        'session_id',

        // Audit
        'created_by',
        'update_by',
    ];

protected $casts = [
    'applied_age' => 'integer',
    'apply_course' => 'integer',
    'apply_course_type' => 'integer',
    'prospectus_number' => 'integer',
    'status' => 'integer',
    'session_id' => 'integer',
    'apply_date_time' => 'date',
];

    public static function statusOptions(): array
    {
        return self::STATUS_LABELS;
    }

    public static function triggerForStatus(int $status): string
    {
        $label = self::STATUS_LABELS[$status] ?? (string) $status;
        return 'status_' . $label;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[(int) $this->status] ?? 'Unknown';
    }



 public function degree(): BelongsTo
    {
        return $this->belongsTo(Degrees::class, 'apply_course', 'degree_id');
    }

    public function session(): BelongsTo
{
    return $this->belongsTo(Session::class, 'session_id', 'session_id');
}

}
