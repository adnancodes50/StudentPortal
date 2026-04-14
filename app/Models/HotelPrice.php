<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPrice extends Model
{
    use HasFactory;

    protected $table = 'hotel_prices';

    protected $fillable = [
        'hotel_id',
        'room_id',
        'price',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'valid_from'  => 'date',
        'valid_to'    => 'date',
    ];

    /**
     * Get the hotel for this price.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the room for this price.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}