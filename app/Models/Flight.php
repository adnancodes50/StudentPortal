<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'airline_name', 'flight_number', 'departure_city', 'arrival_city',
        'departure_time', 'arrival_time', 'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function flightPrices()
    {
        return $this->hasMany(FlightPrice::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_flights');
    }
}
