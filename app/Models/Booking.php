<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'agent_id',
        'user_id',
        'category_id',
        'booking_no',
        'reference_no',
        'booking_type', // umrah, visa, insurance, group, package
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function passengers()
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function flights()
    {
        return $this->belongsToMany(Flight::class, 'booking_flights')->withPivot('price');
    }

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'booking_hotels')->withPivot('price');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'booking_packages')->withPivot('price');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(Ledger::class);
    }
}
?>
