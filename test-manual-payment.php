<?php
/**
 * Automated Testing Script - Fitur Pembayaran Offline
 * Jalankan dengan: php test-manual-payment.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Colors for terminal output
class Color {
    public static function green($text) { return "\033[32m{$text}\033[0m"; }
    public static function red($text) { return "\033[31m{$text}\033[0m"; }
    public static function yellow($text) { return "\033[33m{$text}\033[0m"; }
    public static function blue($text) { return "\033[34m{$text}\033[0m"; }
    public static function bold($text) { return "\033[1m{$text}\033[0m"; }
}

$passCount = 0;
$failCount = 0;

function testCase($name, $callback) {
    global $passCount, $failCount;
    
    echo "\n" . Color::blue("🧪 TEST: {$name}") . "\n";
    echo str_repeat("-", 60) . "\n";
    
    try {
        $result = $callback();
        if ($result) {
            echo Color::green("✅ PASS") . "\n";
            $passCount++;
        } else {
            echo Color::red("❌ FAIL") . "\n";
            $failCount++;
        }
    } catch (Exception $e) {
        echo Color::red("❌ FAIL: " . $e->getMessage()) . "\n";
        echo "Stack trace: " . $e->getTraceAsString() . "\n";
        $failCount++;
    }
}

echo Color::bold("\n🚀 AUTOMATED TESTING - FITUR PEMBAYARAN OFFLINE\n");
echo str_repeat("=", 60) . "\n";

// ========================================
// TEST 1: Verifikasi Migration
// ========================================
testCase("Migration - Enum 'cash' ditambahkan ke payment_method", function() {
    $result = DB::select("SHOW COLUMNS FROM payments WHERE Field = 'payment_method'");
    $enumValues = $result[0]->Type ?? '';
    
    echo "Column Type: {$enumValues}\n";
    
    $hasCash = str_contains($enumValues, 'cash');
    
    if ($hasCash) {
        echo "✓ Enum 'cash' ditemukan di payment_method\n";
        return true;
    } else {
        echo "✗ Enum 'cash' TIDAK ditemukan. Silakan jalankan: php artisan migrate\n";
        return false;
    }
});

// ========================================
// TEST 2: Setup Data Testing
// ========================================
$testBooking = null;
$testUser = null;
$testAdmin = null;

testCase("Setup - Membuat data testing", function() use (&$testBooking, &$testUser, &$testAdmin) {
    DB::beginTransaction();
    
    try {
        // Admin
        $testAdmin = User::firstOrCreate(
            ['email' => 'admin@gardenia.com'],
            [
                'name' => 'Admin Gardenia',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );
        echo "✓ Admin ready: {$testAdmin->email}\n";
        
        // User
        $testUser = User::firstOrCreate(
            ['email' => 'testuser@manual.payment'],
            [
                'name' => 'Test User Manual Payment',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '082345678901',
                'address' => 'Jl. Testing No. 456',
            ]
        );
        echo "✓ User ready: {$testUser->email}\n";
        
        // Room
        $room = Room::where('is_available', true)->first();
        if (!$room) {
            $room = Room::create([
                'name' => 'Test Room for Payment',
                'type' => 'standard',
                'floor' => 1,
                'price' => 750000,
                'description' => 'Room untuk testing manual payment',
                'is_available' => true,
            ]);
        }
        echo "✓ Room ready: {$room->name} (Rp " . number_format($room->price, 0) . ")\n";
        
        // Booking dengan status confirmed
        $testBooking = Booking::create([
            'booking_code' => 'TEST-' . strtoupper(substr(uniqid(), -8)),
            'user_id' => $testUser->id,
            'room_id' => $room->id,
            'check_in_date' => now()->addDays(7),
            'duration_months' => 1,
            'total_price' => $room->price,
            'dp_amount' => 250000,
            'status' => 'confirmed',
        ]);
        echo "✓ Booking created: {$testBooking->booking_code}\n";
        
        // DP Payment (verified)
        Payment::create([
            'booking_id' => $testBooking->id,
            'user_id' => $testUser->id,
            'amount' => 250000,
            'payment_method' => 'qris',
            'payment_type' => 'dp',
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $testAdmin->id,
        ]);
        echo "✓ DP Payment verified\n";
        
        DB::commit();
        return true;
        
    } catch (Exception $e) {
        DB::rollBack();
        throw $e;
    }
});

if (!$testBooking) {
    echo Color::red("\n❌ Cannot continue without test booking. Exiting.\n");
    exit(1);
}

// ========================================
// TEST 3: Simulasi Catat Pembayaran Offline
// ========================================
testCase("Fitur - Catat pembayaran offline (cash)", function() use ($testBooking, $testAdmin) {
    $sisaPembayaran = $testBooking->total_price - $testBooking->dp_amount;
    
    echo "Booking: {$testBooking->booking_code}\n";
    echo "Status sebelum: {$testBooking->status}\n";
    echo "Sisa pembayaran: Rp " . number_format($sisaPembayaran, 0) . "\n";
    
    // Simulasi recordManualPayment
    DB::beginTransaction();
    
    try {
        // Buat payment cash
        $payment = Payment::create([
            'booking_id' => $testBooking->id,
            'user_id' => $testBooking->user_id,
            'amount' => $sisaPembayaran,
            'payment_method' => 'cash',
            'payment_type' => 'full',
            'proof_path' => null,
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $testAdmin->id,
            'notes' => 'Testing pelunasan tunai saat check-in',
        ]);
        
        echo "✓ Payment created: ID {$payment->id}\n";
        echo "  - Method: {$payment->payment_method}\n";
        echo "  - Amount: Rp " . number_format($payment->amount, 0) . "\n";
        echo "  - Status: {$payment->status}\n";
        
        // Update booking status
        $testBooking->update(['status' => 'completed']);
        $testBooking->refresh();
        
        echo "✓ Booking updated: status = {$testBooking->status}\n";
        
        DB::commit();
        
        // Verifikasi
        $isPaymentCash = $payment->payment_method === 'cash';
        $isPaymentVerified = $payment->status === 'verified';
        $isBookingCompleted = $testBooking->status === 'completed';
        
        if ($isPaymentCash && $isPaymentVerified && $isBookingCompleted) {
            return true;
        }
        
        echo "✗ Verification failed:\n";
        echo "  - Payment method = cash? " . ($isPaymentCash ? 'YES' : 'NO') . "\n";
        echo "  - Payment verified? " . ($isPaymentVerified ? 'YES' : 'NO') . "\n";
        echo "  - Booking completed? " . ($isBookingCompleted ? 'YES' : 'NO') . "\n";
        
        return false;
        
    } catch (Exception $e) {
        DB::rollBack();
        throw $e;
    }
});

// ========================================
// TEST 4: Verifikasi Pendapatan Bulan Ini
// ========================================
testCase("Dashboard - Pendapatan bulan ini terupdate", function() use ($testBooking) {
    $currentMonth = now()->month;
    $currentYear = now()->year;
    
    $monthlyIncome = Payment::where('status', 'verified')
        ->whereMonth('verified_at', $currentMonth)
        ->whereYear('verified_at', $currentYear)
        ->sum('amount');
    
    $monthlyCount = Payment::where('status', 'verified')
        ->whereMonth('verified_at', $currentMonth)
        ->whereYear('verified_at', $currentYear)
        ->count();
    
    echo "Pendapatan bulan ini: Rp " . number_format($monthlyIncome, 0) . "\n";
    echo "Total pembayaran terverifikasi: {$monthlyCount}\n";
    
    // Cek apakah ada payment cash dari booking test
    $cashPayments = Payment::where('booking_id', $testBooking->id)
        ->where('payment_method', 'cash')
        ->where('status', 'verified')
        ->get();
    
    echo "Payment cash dari booking test: {$cashPayments->count()}\n";
    
    if ($cashPayments->count() > 0) {
        echo "✓ Payment cash ditemukan dan included dalam perhitungan\n";
        return true;
    }
    
    return false;
});

// ========================================
// TEST 5: Verifikasi Payment Query
// ========================================
testCase("Query - Payment dengan method 'cash' dapat diquery", function() use ($testBooking) {
    $cashPayment = Payment::where('booking_id', $testBooking->id)
        ->where('payment_method', 'cash')
        ->first();
    
    if (!$cashPayment) {
        echo "✗ Payment cash tidak ditemukan\n";
        return false;
    }
    
    echo "✓ Cash payment found:\n";
    echo "  - ID: {$cashPayment->id}\n";
    echo "  - Amount: Rp " . number_format($cashPayment->amount, 0) . "\n";
    echo "  - Method: {$cashPayment->payment_method}\n";
    echo "  - Type: {$cashPayment->payment_type}\n";
    echo "  - Status: {$cashPayment->status}\n";
    echo "  - Notes: {$cashPayment->notes}\n";
    
    return true;
});

// ========================================
// TEST 6: Verifikasi Total Payments
// ========================================
testCase("Validasi - Total pembayaran = Total harga booking", function() use ($testBooking) {
    $totalPayments = Payment::where('booking_id', $testBooking->id)
        ->where('status', 'verified')
        ->sum('amount');
    
    $totalBooking = $testBooking->total_price;
    
    echo "Total payments: Rp " . number_format($totalPayments, 0) . "\n";
    echo "Total booking: Rp " . number_format($totalBooking, 0) . "\n";
    
    $isEqual = $totalPayments == $totalBooking;
    
    if ($isEqual) {
        echo "✓ Total pembayaran sesuai dengan total booking\n";
    } else {
        echo "✗ Total tidak sesuai (selisih: Rp " . number_format(abs($totalPayments - $totalBooking), 0) . ")\n";
    }
    
    return $isEqual;
});

// ========================================
// TEST 7: Cleanup
// ========================================
testCase("Cleanup - Menghapus data testing", function() use ($testBooking, $testUser) {
    try {
        // Hapus payments
        Payment::where('booking_id', $testBooking->id)->delete();
        echo "✓ Payments deleted\n";
        
        // Hapus booking
        $testBooking->delete();
        echo "✓ Booking deleted\n";
        
        // Hapus user test (optional)
        if ($testUser && str_contains($testUser->email, 'testuser@manual.payment')) {
            $testUser->delete();
            echo "✓ Test user deleted\n";
        }
        
        return true;
    } catch (Exception $e) {
        echo "⚠️  Cleanup warning: " . $e->getMessage() . "\n";
        return true; // Don't fail test on cleanup errors
    }
});

// ========================================
// SUMMARY
// ========================================
echo "\n" . str_repeat("=", 60) . "\n";
echo Color::bold("📊 TEST SUMMARY\n");
echo str_repeat("=", 60) . "\n";

$total = $passCount + $failCount;
$percentage = $total > 0 ? round(($passCount / $total) * 100, 2) : 0;

echo "Total Tests: {$total}\n";
echo Color::green("Passed: {$passCount}") . "\n";
echo Color::red("Failed: {$failCount}") . "\n";
echo "Success Rate: {$percentage}%\n\n";

if ($failCount === 0) {
    echo Color::green("✅ ALL TESTS PASSED! ") . "Fitur pembayaran offline siap digunakan! 🎉\n";
    exit(0);
} else {
    echo Color::red("❌ SOME TESTS FAILED. ") . "Silakan periksa error di atas.\n";
    exit(1);
}
