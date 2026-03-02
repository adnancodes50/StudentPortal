<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeTableMOdel extends Model
{
    // Table & keys
    protected $table = 'time_table';
    protected $primaryKey = 'id';

    // If you want Laravel to write creation_date automatically and you have no updated_at
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = null;

    // Mass-assignable columns
    protected $fillable = [
        'class_id',
        'course_id',
        'section',
        'lec_no',
        'room_id',
        'day',
        'session_id',
        'time_from',
        'time_to',
        'makeup_date',
        'status',       // 1 = done, 2 = dismiss
        'created_by',
        'creation_date',
    ];

    // Casts (treat time_* as strings if columns are TIME; change if DATETIME)
    protected $casts = [
        'lec_no'        => 'integer',
        'room_id'       => 'integer',
        'session_id'    => 'integer',
        'class_id'      => 'integer',
        'course_id'     => 'integer',
        'status'        => 'integer',
        'makeup_date'   => 'datetime',
        'creation_date' => 'datetime',
        'time_from'     => 'string',
        'time_to'       => 'string',
    ];

    // ---- Relationships ----

    public function session(): BelongsTo
    {
        // Your Session model uses key session_id in DB
        return $this->belongsTo(\App\Models\Session::class, 'session_id', 'session_id');
    }

    public function class(): BelongsTo
    {
        // Your ClassModel uses key class_id in DB
        return $this->belongsTo(\App\Models\ClassModel::class, 'class_id', 'class_id');
    }

    public function course(): BelongsTo
    {
        // Your CoursesModel uses key course_id in DB
        return $this->belongsTo(\App\Models\CoursesModel::class, 'course_id', 'course_id');
    }

    public function room(): BelongsTo
    {
        // If you have a Room model/table with PK room_id
        return $this->belongsTo(\App\Models\RoomModel::class, 'room_id', 'room_id');
    }

    public function creator(): BelongsTo
    {
        // Typical users table with id
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    // ---- Helpers / Scopes ----

    public const STATUS_DONE = 1;
    public const STATUS_DISMISS = 2;

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DONE    => 'Done',
            self::STATUS_DISMISS => 'Dismiss',
            default              => 'Unknown',
        };
    }

    public function scopeForDay($q, string $day)
    {
        return $q->where('day', $day);
    }

    public function scopeForSection($q, $section)
    {
        return $q->where('section', $section);
    }
}
