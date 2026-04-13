<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'name', 'city'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function hotelPrices()
    {
        return $this->hasMany(HotelPrice::class);
    }
}
