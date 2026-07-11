<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'room_id', 'booking_code',
        'check_in_date', 'duration_months',
        'total_price', 'dp_amount', 'status', 'notes',
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

    // Generate kode booking otomatis
    public static function generateCode(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'GRD-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
