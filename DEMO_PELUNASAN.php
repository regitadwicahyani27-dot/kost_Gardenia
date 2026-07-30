<?php
/**
 * DEMO: Pelunasan 500k otomatis update dashboard
 * Jalankan dengan: php DEMO_PELUNASAN.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

function formatRupiah($amount) {
    return "Rp " . number_format($amount, 0, ',', '.');
}

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║          DEMO: PELUNASAN 500K OTOMATIS UPDATE DASHBOARD          ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n";
echo "\n";

try {
    // Cari booking dengan status confirmed
    $booking = Booking::where('status', 'confirmed')
        ->with(['user', 'room', 'payments'])
        ->first();
    
    if (!$booking) {
        echo "❌ Tidak ada booking dengan status 'confirmed'\n";
        echo "💡 Jalankan: php refresh-db.php untuk setup data\n";
        exit(1);
    }

    $admin = User::where('role', 'admin')->first();

    // ========================================
    // STEP 1: TAMPILKAN SITUASI AWAL
    // ========================================
    echo "📊 SITUASI AWAL\n";
    echo "═══════════════════════════════════════════════════════════════\n\n";

    echo "🏠 Booking: {$booking->booking_code}\n";
    echo "   User: {$booking->user->name}\n";
    echo "   Kamar: {$booking->room->name}\n";
    echo "   Total Harga: " . formatRupiah($booking->total_price) . "\n";
    echo "   Status: {$booking->status}\n\n";

    echo "💰 Pembayaran yang sudah ada:\n";
    $totalPaid = 0;
    foreach ($booking->payments as $payment) {
        $type = $payment->payment_type === 'dp' ? 'DP' : 'PELUNASAN';
        $method = $payment->payment_method === 'cash' ? 'Tunai' : strtoupper($payment->payment_method);
        $status = $payment->status === 'verified' ? '✅ Verified' : ($payment->status === 'pending' ? '⏳ Pending' : '❌ Rejected');
        
        echo "   - {$type} via {$method}: " . formatRupiah($payment->amount) . " {$status}\n";
        
        if ($payment->status === 'verified') {
            $totalPaid += $payment->amount;
        }
    }
    
    echo "\n   Total sudah dibayar (verified): " . formatRupiah($totalPaid) . "\n";
    
    $sisa = $booking->total_price - $totalPaid;
    echo "   💵 Sisa pembayaran: " . formatRupiah($sisa) . "\n\n";

    // Dashboard BEFORE
    $dashboardBefore = Payment::where('status', 'verified')
        ->whereMonth('verified_at', now()->month)
        ->whereYear('verified_at', now()->year)
        ->sum('amount');
    
    $countBefore = Payment::where('status', 'verified')
        ->whereMonth('verified_at', now()->month)
        ->whereYear('verified_at', now()->year)
        ->count();

    echo "📈 DASHBOARD SEBELUM PELUNASAN:\n";
    echo "   Pendapatan Bulan Ini: " . formatRupiah($dashboardBefore) . "\n";
    echo "   {$countBefore} pembayaran terverifikasi\n\n";

    // ========================================
    // STEP 2: PROSES PELUNASAN
    // ========================================
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "🔄 PROSES: Admin mencatat pelunasan tunai...\n";
    echo "═══════════════════════════════════════════════════════════════\n\n";

    sleep(1);

    DB::beginTransaction();

    // Simulasi recordManualPayment
    $pelunasan = Payment::create([
        'booking_id' => $booking->id,
        'user_id' => $booking->user_id,
        'amount' => $sisa,
        'payment_method' => 'cash',
        'payment_type' => 'full',
        'proof_path' => null,
        'status' => 'verified',
        'verified_at' => now(),
        'verified_by' => $admin->id,
        'notes' => 'Pelunasan tunai saat check-in',
    ]);

    echo "✅ Payment baru dibuat:\n";
    echo "   ID: {$pelunasan->id}\n";
    echo "   Metode: cash (Tunai Offline)\n";
    echo "   Tipe: full (Pelunasan)\n";
    echo "   Amount: " . formatRupiah($pelunasan->amount) . "\n";
    echo "   Status: verified ✅\n";
    echo "   Verified at: " . $pelunasan->verified_at->format('d M Y H:i:s') . "\n";
    echo "   Verified by: {$admin->name}\n\n";

    // Update booking
    $booking->update(['status' => 'completed']);
    echo "✅ Booking status updated: completed\n\n";

    DB::commit();

    sleep(1);

    // ========================================
    // STEP 3: TAMPILKAN HASIL
    // ========================================
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "📊 HASIL SETELAH PELUNASAN\n";
    echo "═══════════════════════════════════════════════════════════════\n\n";

    // Refresh data
    $booking->refresh();
    $booking->load('payments');

    echo "🏠 Booking: {$booking->booking_code}\n";
    echo "   Status: {$booking->status} (completed)\n\n";

    echo "💰 Riwayat Pembayaran Lengkap:\n";
    $totalPaidAfter = 0;
    foreach ($booking->payments as $payment) {
        $type = $payment->payment_type === 'dp' ? 'DP' : 'PELUNASAN';
        $method = $payment->payment_method === 'cash' ? 'Tunai (Offline)' : strtoupper($payment->payment_method);
        $status = $payment->status === 'verified' ? '✅' : ($payment->status === 'pending' ? '⏳' : '❌');
        
        echo "   {$status} {$type} via {$method}: " . formatRupiah($payment->amount);
        
        if ($payment->id === $pelunasan->id) {
            echo " ← BARU!";
        }
        
        echo "\n";
        
        if ($payment->status === 'verified') {
            $totalPaidAfter += $payment->amount;
        }
    }
    
    echo "\n   Total terbayar: " . formatRupiah($totalPaidAfter) . " ✅ LUNAS!\n\n";

    // Dashboard AFTER
    $dashboardAfter = Payment::where('status', 'verified')
        ->whereMonth('verified_at', now()->month)
        ->whereYear('verified_at', now()->year)
        ->sum('amount');
    
    $countAfter = Payment::where('status', 'verified')
        ->whereMonth('verified_at', now()->month)
        ->whereYear('verified_at', now()->year)
        ->count();

    echo "📈 DASHBOARD SETELAH PELUNASAN:\n";
    echo "   Pendapatan Bulan Ini: " . formatRupiah($dashboardAfter);
    
    $increase = $dashboardAfter - $dashboardBefore;
    if ($increase > 0) {
        echo " (+".formatRupiah($increase).")";
    }
    
    echo "\n";
    echo "   {$countAfter} pembayaran terverifikasi (+".($countAfter - $countBefore).")\n\n";

    // ========================================
    // STEP 4: VERIFIKASI
    // ========================================
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "✅ VERIFIKASI\n";
    echo "═══════════════════════════════════════════════════════════════\n\n";

    if ($increase == $sisa) {
        echo "🎉 SUCCESS! Dashboard terupdate dengan benar!\n\n";
        
        echo "📊 PERUBAHAN:\n";
        echo "   Pendapatan SEBELUM: " . formatRupiah($dashboardBefore) . "\n";
        echo "   Pelunasan dicatat:  +" . formatRupiah($sisa) . "\n";
        echo "   Pendapatan SESUDAH: " . formatRupiah($dashboardAfter) . "\n";
        echo "   ────────────────────────────────\n";
        echo "   Selisih: " . formatRupiah($increase) . " ✅ MATCH!\n\n";
        
        echo "💡 KESIMPULAN:\n";
        echo "   • DP 250k (verified) → dashboard +250k\n";
        echo "   • Pelunasan 500k (verified) → dashboard +500k\n";
        echo "   • TOTAL untuk 1 booking: 750k ✅\n\n";
        
    } else {
        echo "⚠️  WARNING: Perubahan tidak sesuai!\n";
        echo "   Expected: +" . formatRupiah($sisa) . "\n";
        echo "   Actual: +" . formatRupiah($increase) . "\n\n";
    }

    echo "═══════════════════════════════════════════════════════════════\n";
    echo "🎯 CARA VERIFIKASI DI BROWSER:\n";
    echo "═══════════════════════════════════════════════════════════════\n\n";
    
    echo "1. Login sebagai admin:\n";
    echo "   Email: admin@gardenia.com\n";
    echo "   Password: admin123\n\n";
    
    echo "2. Dashboard akan menampilkan:\n";
    echo "   📊 Pendapatan Bulan Ini: " . formatRupiah($dashboardAfter) . "\n";
    echo "   📋 {$countAfter} pembayaran terverifikasi\n\n";
    
    echo "3. Klik 'Detail' pada booking {$booking->booking_code}\n\n";
    
    echo "4. Lihat riwayat pembayaran:\n";
    echo "   ✅ DP · QRIS/DANA/BCA: " . formatRupiah($booking->dp_amount) . "\n";
    echo "   ✅ FULL · Tunai (Offline): " . formatRupiah($sisa) . " ← BARU\n\n";
    
    echo "5. Status booking: Selesai (Completed) ✅\n\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
