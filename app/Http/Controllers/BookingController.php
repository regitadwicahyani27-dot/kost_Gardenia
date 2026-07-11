<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $activeBooking = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->with(['room.primaryPhoto', 'payments'])
            ->latest()->first();
        $totalBookings = Booking::where('user_id', $user->id)->count();

        return view('user.dashboard', compact('activeBooking', 'totalBookings'));
    }

    public function create(Room $room)
    {
        return view('user.booking.create', compact('room'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'payment_method' => 'required|in:qris,dana,ovo,bca',
            'ewallet_phone' => ['required_if:payment_method,dana,ovo', 'nullable', 'string', 'max:20'],
        ]);

        $room = Room::findOrFail($request->room_id);

        if (!$room->is_available) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Kamar sudah terisi.']);
            }
            return back()->with('error', 'Kamar sudah terisi.');
        }

        $total = $room->price;
        $dpAmount = 250000;
        $sisa = $total - $dpAmount;

        // Generate booking code
        $year = date('Y');
        $count = Booking::whereYear('created_at', $year)->count() + 1;
        $bookingCode = 'GDN-' . str_pad(mt_rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);

        // Buat booking
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $room->id,
            'booking_code' => $bookingCode,
            'check_in_date' => $request->check_in_date,
            'duration_months' => 1,
            'total_price' => $total,
            'dp_amount' => $dpAmount,
            'status' => 'confirmed',
            'notes' => null,
        ]);

        // Buat pembayaran DP (langsung verified karena simulasi)
        $catatanBayar = 'Pembayaran DP otomatis (simulasi)';
        if (in_array($request->payment_method, ['dana', 'ovo']) && $request->ewallet_phone) {
            $catatanBayar .= ' — No. ' . strtoupper($request->payment_method) . ': ' . $request->ewallet_phone;
        }

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'amount' => $dpAmount,
            'payment_method' => $request->payment_method,
            'payment_type' => 'dp',
            'proof_path' => null,
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'notes' => $catatanBayar,
        ]);

        // Ubah status kamar jadi TIDAK TERSEDIA
        $room->update(['is_available' => false]);

        // Format tanggal Indonesia
        $checkInFormatted = \Carbon\Carbon::parse($request->check_in_date)
            ->locale('id')
            ->translatedFormat('l, d F Y');

        $nowFormatted = now()->locale('id')->translatedFormat('l, d F Y');

        // Response JSON untuk AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'nama' => auth()->user()->name,
                'telepon' => auth()->user()->phone ?? '-',
                'kamar' => $room->name,
                'lantai' => $room->floor,
                'tanggal_masuk' => $checkInFormatted,
                'metode' => $request->payment_method,
                'booking_code' => $bookingCode,
                'tanggal_transaksi' => $nowFormatted,
                'sisa' => $sisa,
            ]);
        }

        // Fallback non-AJAX
        return redirect()->route('user.booking.show', $booking)
            ->with('success', 'Booking berhasil! Pembayaran DP telah terverifikasi.');
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }
        $booking->load(['room.photos', 'payments']);
        return view('user.booking.show', compact('booking'));
    }

    public function history()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['room.primaryPhoto', 'payments'])
            ->latest()->paginate(10);

        return view('user.booking.history', compact('bookings'));
    }
}
