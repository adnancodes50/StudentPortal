<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'airline_name',
        'flight_number',
        'departure_city',
        'arrival_city',
        'departure_date',
        'return_date',
        'departure_time',
        'arrival_time',
        'baggage_kg',
        'origin_airport_code',
        'destination_airport_code',
        'duration_minutes',
        'status',
    ];

    /**
     * Relationship: Flight has many prices
     */
    public function flightPrices()
    {
        return $this->hasMany(FlightPrice::class);
    }

    /**
     * Relationship: Many-to-many with bookings
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_flights');
    }

    /**
     * 🔥 Helper: Get route (Lahore → Islamabad)
     */
    public function getRouteAttribute()
    {
        return $this->departure_city . ' → ' . $this->arrival_city;
    }

    /**
     * 🔥 Helper: Get return route (Islamabad → Lahore)
     */
    public function getReturnRouteAttribute()
    {
        return $this->arrival_city . ' → ' . $this->departure_city;
    }
}