<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Degrees extends Model
{
    use HasFactory;

    protected $primaryKey = 'degree_id';
    public $incrementing = true;

    protected $fillable = [
        'degree_name',
        'degree_subject_name',
        'full_title',
        'total_semester',
        'degree_deleted',
        'eligibility_criteria',
        'type',
        'extra_notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'degree_deleted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'b' => 'Bachelor',
            'm' => 'Master',
            default => 'Unknown',
        };
    }


    public function sessions()
    {
        return $this->belongsToMany(\App\Models\Session::class, 'session_degrees', 'degree_id', 'session_id')
            ->using(\App\Models\SessionDegree::class)
            ->withPivot([
                'tution_fee',
                'other_fee',
                'admission_fee',
                'registration_fee',
                'security_fee',
                'misc'
            ]);
    }


    public function classesCourses()
{
    return $this->hasMany(ClassesCourses::class, 'degree_id');
}

public function admissionInquiries(): HasMany
{
    return $this->hasMany(
        AdmissionInquiry::class,
        'apply_course',
        'degree_id'
    );
}

}
