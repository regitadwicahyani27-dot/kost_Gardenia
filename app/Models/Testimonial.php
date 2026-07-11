<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'user_id', 'booking_id', 'name', 'label', 'rating', 'content', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Nama yang ditampilkan: pakai nama manual admin, atau nama akun user jika ada.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: ($this->user->name ?? 'Penghuni');
    }
}
