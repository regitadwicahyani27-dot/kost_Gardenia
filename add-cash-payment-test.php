<?php
/**
 * Script untuk menambahkan pembayaran cash (test)
 * dan verifikasi dashboard terupdate
 * 
 * Jalankan dengan: php add-cash-payment-test.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

echo "💰 TEST PEMBAYARAN CASH (OFFLINE)\n";
echo str_repeat("=", 60) . "\n\n";

try {
    // Cari booking dengan status confirmed
    $booking = Booking::where('status', 'confirmed')->first();
    
    if (!$booking) {
        echo "❌ Tidak ada booking dengan status 'confirmed'\n";
        echo "Silakan jalankan: php refresh-db.php\n";
        exit(1);
    }

    $admin = User::where('role', 'admin')->first();

    echo "📋 BOOKING INFO:\n";
    echo "- Kode: {$booking->booking_code}\n";
    echo "- User: {$booking->user->name}\n";
    echo "- Kamar: {$booking->room->name}\n";
    echo "- Total: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n";
    echo "- DP: Rp " . number_format($booking->dp_amount, 0, ',', '.') . "\n";
    echo "- Sisa: Rp " . number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') . "\n";
    echo "- Status: {$booking->status}\n\n";

    // Cek pendapatan SEBELUM
    $incomeBefore = Payment::where('status', 'verified')
        ->whereMonth('verified_at', now()->month)
        ->whereYear('verified_at', now()->year)
        ->sum('amount');
    
    $countBefore = Payment::where('status', 'verified')
        ->whereMonth('verified_at', now()->month)
        ->whereYear('verified_at', now()->year)
        ->count();

    echo "💰 DASHBOARD SEBELUM:\n";
    echo "- Pendapatan: Rp " . number_format($incomeBefore, 0, ',', '.') . "\n";
    echo "- Jumlah payment: {$countBefore}\n\n";

    echo "🔄 Menambahkan pembayaran cash...\n";

    DB::beginTransaction();

    // Buat payment cash
    $sisaPembayaran = $booking->total_price - $booking->dp_amount;
    
    $payment = Payment::create([
        'booking_id' => $booking->id,
        'user_id' => $booking->user_id,
        'amount' => $sisaPembayaran,
        'payment_method' => 'cash',
        'payment_type' => 'full',
        'proof_path' => null,
        'status' => 'verified',
        'verified_at' => now(),
        'verified_by' => $admin->id,
        'notes' => 'TEST: Pelunasan tunai saat check-in',
    ]);

    echo "✅ Payment created: ID {$payment->id}\n";
    echo "  - Method: {$payment->payment_method}\n";
    echo "  - Amount: Rp " . number_format($payment->amount, 0, ',', '.') . "\n";
    echo "  - Status: {$payment->status}\n";
    echo "  - Type: {$payment->payment_type}\n\n";

    // Update booking
    $booking->update(['status' => 'completed']);

    echo "✅ Booking updated: status = completed\n\n";

    DB::commit();

    // Cek pendapatan SESUDAH
    $incomeAfter = Payment::where('status', 'verified')
        ->whereMonth('verified_at', now()->month)
        ->whereYear('verified_at', now()->year)
        ->sum('amount');
    
    $countAfter = Payment::where('status', 'verified')
        ->whereMonth('verified_at', now()->month)
        ->whereYear('verified_at', now()->year)
        ->count();

    echo "💰 DASHBOARD SESUDAH:\n";
    echo "- Pendapatan: Rp " . number_format($incomeAfter, 0, ',', '.') . "\n";
    echo "- Jumlah payment: {$countAfter}\n\n";

    $increase = $incomeAfter - $incomeBefore;
    echo "📈 PERUBAHAN:\n";
    echo "- Pendapatan bertambah: Rp " . number_format($increase, 0, ',', '.') . "\n";
    echo "- Payment bertambah: " . ($countAfter - $countBefore) . "\n\n";

    echo str_repeat("=", 60) . "\n";
    
    if ($increase == $sisaPembayaran) {
        echo "✅ SUCCESS! Dashboard terupdate dengan benar!\n";
        echo str_repeat("=", 60) . "\n\n";
        
        echo "🎯 VERIFIKASI:\n";
        echo "1. Login sebagai admin: admin@gardenia.com / admin123\n";
        echo "2. Dashboard akan tampil:\n";
        echo "   - Pendapatan: Rp " . number_format($incomeAfter, 0, ',', '.') . "\n";
        echo "   - {$countAfter} pembayaran terverifikasi\n";
        echo "3. Klik Detail booking {$booking->booking_code}\n";
        echo "4. Lihat riwayat pembayaran:\n";
        echo "   - DP: Rp " . number_format($booking->dp_amount, 0, ',', '.') . "\n";
        echo "   - FULL · Tunai (Offline): Rp " . number_format($sisaPembayaran, 0, ',', '.') . "\n\n";
        
        exit(0);
    } else {
        echo "⚠️  WARNING: Pendapatan tidak bertambah sesuai!\n";
        echo "Expected: Rp " . number_format($sisaPembayaran, 0, ',', '.') . "\n";
        echo "Actual: Rp " . number_format($increase, 0, ',', '.') . "\n";
        exit(1);
    }

} catch (Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
