<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'booking.room'])->latest()->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Payment $payment)
    {
        $payment->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Jika DP diverifikasi, ubah status booking jadi confirmed
        if ($payment->payment_type === 'dp') {
            $payment->booking->update(['status' => 'confirmed']);
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    public function reject(Request $request, Payment $payment)
    {
        $payment->update([
            'status' => 'rejected',
            'notes' => $request->notes,
        ]);
        return back()->with('success', 'Pembayaran ditolak.');
    }
}