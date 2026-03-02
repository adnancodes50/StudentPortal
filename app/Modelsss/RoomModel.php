<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomModel extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $primaryKey = 'room_id';
    public $timestamps = true;

    protected $fillable = [
        'room_no',
        'status',
        'created_by',
        'updated_by',
    ];

    // Relationship to User who created the room
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship to User who last updated the room
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusTextAttribute()
{
    switch ($this->status) {
        case 0:
            return 'Available';
        case 1:
            return 'Occupied';
        case 2:
            return 'Maintenance';
        default:
            return 'Unknown';
    }
}


public function timeTables()
{
    return $this->hasMany(\App\Models\TimeTableMOdel::class, 'room_id', 'room_id');
}


}
