<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentHunt extends Model
{
    use HasFactory;

    protected $table = 'talenthunt';

    protected $fillable = [
        'image_path',
        'test_type',
        'name',
        'date_of_birth',
        'father_name',
        'gender',
        'cnic',
        'mobile_number_1',
        'email',
        'mobile_number_2',
        'address',
        'religion',
        'institute_name',
        'city',
        'first_year_marks',
        'declaration_signature_name',
        'declaration_signature',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
