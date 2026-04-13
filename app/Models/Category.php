<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'status',
                'image'   // 👈 new field

    ];

    // 🔗 Relationship: Category has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // 🔗 Relationship: Category has many flights
    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    // 🔗 Relationship: Category has many packages
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
