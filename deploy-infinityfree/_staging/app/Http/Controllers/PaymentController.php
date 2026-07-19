<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        // Pastikan booking milik user yang login
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:qris,dana,ovo,bca',
            'proof' => 'required|image|max:2048', // max 2MB
        ]);

        $path = $request->file('proof')->store('payments', 'public');

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'amount' => $booking->dp_amount,
            'payment_method' => $request->payment_method,
            'payment_type' => 'dp',
            'proof_path' => $path,
            'status' => 'pending',
        ]);
    
        return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }
}