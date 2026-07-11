<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /** DP tetap untuk semua booking */
    public const DP_AMOUNT = 250_000;

    protected $fillable = [
        'user_id', 'room_id', 'booking_code',
        'check_in_date', 'duration_months',
        'total_price', 'dp_amount', 'status', 'notes', 'cancelled_reason', 'cancelled_by',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'total_price' => 'decimal:2',
        'dp_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function testimonial()
    {
        return $this->hasOne(Testimonial::class);
    }

}
