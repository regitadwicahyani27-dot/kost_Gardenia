<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['user', 'booking.room'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->appends($request->only('status'));

        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);

            // Update booking jadi confirmed
            $payment->booking->update(['status' => 'confirmed']);

            // Tandai kamar sudah tidak tersedia
            $payment->booking->room()->update(['is_available' => false]);
        });

        return back()->with('success', 'Pembayaran berhasil diverifikasi! Booking dikonfirmasi.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'reject_notes' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($payment, $request) {
            $payment->update([
                'status' => 'rejected',
                'notes' => $request->reject_notes,
            ]);

            // Batalkan booking dengan alasan dari admin
            $payment->booking->update([
                'status' => 'cancelled',
                'cancelled_reason' => $request->reject_notes ?: 'Pembayaran ditolak oleh admin.',
                'cancelled_by' => 'admin',
            ]);
        });

        return back()->with('success', 'Pembayaran ditolak. Booking dibatalkan.');
    }
}
