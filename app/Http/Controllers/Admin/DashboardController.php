<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
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

        $recentBookings = Booking::with(['user', 'room'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
}
