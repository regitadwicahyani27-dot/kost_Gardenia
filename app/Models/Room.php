<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name', 'type', 'price', 'description', 'is_available', 'floor',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function photos()
    {
        return $this->hasMany(RoomPhoto::class);
    }

    public function primaryPhoto()
    {
        return $this->hasOne(RoomPhoto::class)->where('is_primary', true);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'room_facility');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ponytail: exclude rooms with pending/confirmed/active bookings
    public function scopeNoActiveBooking($query)
    {
        return $query->whereDoesntHave('bookings', fn($q) => $q->whereIn('status', ['pending', 'confirmed', 'active']));
    }
}
