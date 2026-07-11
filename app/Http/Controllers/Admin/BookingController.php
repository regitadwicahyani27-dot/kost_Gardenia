<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'room'])->latest()->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'room.photos', 'payments']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,active,cancelled,completed']);
        $booking->update(['status' => $request->status]);

        // Sinkronkan ketersediaan kamar dengan status booking terbaru.
        // Booking batal/selesai -> kamar dibebaskan lagi. Selain itu -> kamar tetap terisi.
        $isFreed = in_array($request->status, ['cancelled', 'completed']);
        $booking->room->update(['is_available' => $isFreed]);

        return back()->with('success', 'Status booking diperbarui, ketersediaan kamar ikut disesuaikan.');
    }
}
