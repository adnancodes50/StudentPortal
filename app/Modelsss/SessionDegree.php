<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SessionDegree extends Pivot
{
    protected $table = 'session_degrees';
    protected $primaryKey = 'session_degrees_id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'degree_id',
        'tution_fee',
        'other_fee',
        'admission_fee',
        'registration_fee',
        'security_fee',
        'misc'
    ];

    protected $casts = [
        'tution_fee' => 'integer',
        'other_fee' => 'integer',
        'admission_fee' => 'integer',
        'registration_fee' => 'integer',
        'security_fee' => 'integer',
        'misc' => 'integer'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function degree()
    {
        return $this->belongsTo(Degrees::class, 'degree_id');
    }
}