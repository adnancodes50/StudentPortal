<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightPrice extends Model
{
    protected $fillable = [
        'flight_id',
        'seat_class',
        'price',
        'valid_from',
        'valid_to',
        'status',
        'currency',
        'is_refundable',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}
