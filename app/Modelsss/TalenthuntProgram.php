<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalenthuntProgram extends Model
{
    use HasFactory;

    /**
     * Table name
     */
    protected $table = 'talenthunt_program';

    /**
     * Primary key
     */
    protected $primaryKey = 'talenthunt_program_id';

    /**
     * Enable timestamps
     */
    public $timestamps = true;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'talenthunt_year',
        'start_date',
        'end_date',
        'test_date',
        'first_prize',
        'second_prize',
        'third_prize',
        'scholarship_note',
        'created_by',
        'updated_by',
    ];

    /**
     * Type casting
     */
    protected $casts = [
        'talenthunt_year' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'test_date' => 'date',
        'first_prize' => 'integer',
        'second_prize' => 'integer',
        'third_prize' => 'integer',
    ];

    /* =====================================================
     | RELATIONSHIPS (OPTIONAL)
     ===================================================== */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* =====================================================
     | SCOPES (OPTIONAL)
     ===================================================== */

    /**
     * Get current active program
     */
    public function scopeCurrent($query)
    {
        return $query->whereDate('start_date', '<=', now())
                     ->whereDate('end_date', '>=', now());
    }

    /* =====================================================
     | ACCESSORS (OPTIONAL)
     ===================================================== */

    public function getFormattedFirstPrizeAttribute()
    {
        return number_format($this->first_prize);
    }

    public function getFormattedSecondPrizeAttribute()
    {
        return number_format($this->second_prize);
    }

    public function getFormattedThirdPrizeAttribute()
    {
        return number_format($this->third_prize);
    }
}
