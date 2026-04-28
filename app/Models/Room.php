<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_name',
        'room_type',
        'bed_capacity',
        'availability',
    ];

    /**
     * Get the hotel that owns this room.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get all price records for this room.
     */
    public function hotelPrices()
    {
        return $this->hasMany(HotelPrice::class);
    }
}