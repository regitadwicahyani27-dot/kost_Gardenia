<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function show(Booking $booking)
    {
        $booking->load(['user', 'room.photos', 'payments']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,active,cancelled,completed',
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        $data = ['status' => $request->status];

        // Kalau dibatalkan, catat alasan dan siapa yang batalkan
        if ($request->status === 'cancelled') {
            $data['cancelled_reason'] = $request->cancel_reason ?: 'Dibatalkan oleh admin.';
            $data['cancelled_by'] = 'admin';
        }

        $booking->update($data);

        // Sinkronkan ketersediaan kamar dengan status booking terbaru.
        // Booking batal/selesai -> kamar dibebaskan lagi. Selain itu -> kamar tetap terisi.
        $isFreed = in_array($request->status, ['cancelled', 'completed']);
        $booking->room->update(['is_available' => $isFreed]);

        return back()->with('success', 'Status booking diperbarui, ketersediaan kamar ikut disesuaikan.');
    }

    public function recordManualPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $booking) {
            // Buat record pembayaran manual (cash)
            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'amount' => $request->amount,
                'payment_method' => 'cash',
                'payment_type' => 'full',
                'proof_path' => null, // Tidak ada bukti upload untuk cash
                'status' => 'verified', // Langsung terverifikasi
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'notes' => $request->notes ?: 'Pelunasan tunai saat check-in',
            ]);

            // Update status booking menjadi completed
            $booking->update(['status' => 'completed']);
        });

        return back()->with('success', 'Pembayaran offline berhasil dicatat.');
    }
}
