<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('is_available', true)->count(),
            'occupied_rooms' => Room::where('is_available', false)->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'monthly_income' => Payment::where('status', 'verified')
                ->whereMonth('verified_at', now()->month)
                ->whereYear('verified_at', now()->year)
                ->sum('amount'),
            'monthly_verified_count' => Payment::where('status', 'verified')
                ->whereMonth('verified_at', now()->month)
                ->whereYear('verified_at', now()->year)
                ->count(),
        ];

        $paymentBookingsQuery = Booking::with(['user', 'room', 'payments.verifiedBy'])
            ->has('payments')
            ->latest();

        if ($request->status === 'pending') {
            $paymentBookingsQuery->whereHas('payments', fn($q) => $q->where('status', 'pending'));
        } elseif ($request->status === 'verified') {
            $paymentBookingsQuery->whereDoesntHave('payments', fn($q) => $q->where('status', 'pending'))
                ->whereHas('payments', fn($q) => $q->where('status', 'verified'));
        } elseif ($request->status === 'rejected') {
            $paymentBookingsQuery->whereHas('payments', fn($q) => $q->where('status', 'rejected'));
        }

        $paymentBookings = $paymentBookingsQuery->paginate(5)->appends($request->only('status'));

        return view('admin.dashboard', compact('stats', 'paymentBookings'));
    }

    public function checkNew()
    {
        return response()->json([
            'max_payment_id' => Payment::max('id') ?? 0,
            'max_booking_id' => Booking::max('id') ?? 0,
            'pending_payments_count' => Payment::where('status', 'pending')->count(),
            'pending_bookings_count' => Booking::where('status', 'pending')->count(),
        ]);
    }
}
