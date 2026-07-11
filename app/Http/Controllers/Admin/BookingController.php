<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

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
}
