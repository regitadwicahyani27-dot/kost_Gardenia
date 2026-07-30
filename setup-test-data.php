<?php
/**
 * Script untuk setup data testing fitur pembayaran offline
 * Jalankan dengan: php setup-test-data.php
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

echo "🚀 Setup Data Testing - Fitur Pembayaran Offline\n";
echo str_repeat("=", 60) . "\n\n";

try {
    DB::beginTransaction();

    // 1. Pastikan ada user admin
    $admin = User::where('email', 'admin@gardenia.com')->first();
    if (!$admin) {
        $admin = User::create([
            'name' => 'Admin Gardenia',
            'email' => 'admin@gardenia.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);
        echo "✅ Admin user created: admin@gardenia.com / admin123\n";
    } else {
        echo "✅ Admin user already exists: admin@gardenia.com\n";
    }

    // 2. Pastikan ada user test
    $user = User::where('email', 'user@test.com')->first();
    if (!$user) {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '082345678901',
            'address' => 'Jl. Test No. 123',
        ]);
        echo "✅ Test user created: user@test.com / password\n";
    } else {
        echo "✅ Test user already exists: user@test.com\n";
    }

    // 3. Pastikan ada kamar available
    $room = Room::where('is_available', true)->first();
    if (!$room) {
        echo "⚠️  Tidak ada kamar available. Silakan buat kamar terlebih dahulu.\n";
        DB::rollBack();
        exit(1);
    }
    echo "✅ Found available room: {$room->name} (Rp " . number_format($room->price, 0, ',', '.') . "/bulan)\n";

    // 4. Buat booking dengan status confirmed (siap untuk pelunasan)
    $existingBooking = Booking::where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->first();

    if (!$existingBooking) {
        $booking = Booking::create([
            'booking_code' => 'GDN-' . strtoupper(substr(uniqid(), -8)),
            'user_id' => $user->id,
            'room_id' => $room->id,
            'check_in_date' => now()->addDays(7),
            'duration_months' => 1,
            'total_price' => $room->price,
            'dp_amount' => 250000,
            'status' => 'confirmed',
        ]);
        echo "✅ Booking created: {$booking->booking_code} (Status: Confirmed)\n";

        // 5. Buat payment DP yang sudah verified
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'amount' => 250000,
            'payment_method' => 'qris',
            'payment_type' => 'dp',
            'proof_path' => null, // Skip upload untuk testing
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $admin->id,
            'notes' => 'DP untuk testing pembayaran offline',
        ]);
        echo "✅ DP Payment verified: Rp " . number_format($payment->amount, 0, ',', '.') . "\n";

        // Update room availability
        $room->update(['is_available' => false]);
        echo "✅ Room marked as occupied\n";
    } else {
        $booking = $existingBooking;
        echo "✅ Using existing confirmed booking: {$booking->booking_code}\n";
    }

    DB::commit();

    echo "\n" . str_repeat("=", 60) . "\n";
    echo "✅ SETUP BERHASIL!\n\n";
    echo "📋 INFORMASI TESTING:\n";
    echo "- Booking Code: {$booking->booking_code}\n";
    echo "- Room: {$booking->room->name}\n";
    echo "- Total Price: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n";
    echo "- DP Paid: Rp " . number_format($booking->dp_amount, 0, ',', '.') . "\n";
    echo "- SISA PEMBAYARAN: Rp " . number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') . "\n";
    echo "- Status: {$booking->status}\n\n";
    
    echo "🔐 LOGIN INFO:\n";
    echo "Admin: admin@gardenia.com / admin123\n";
    echo "User: user@test.com / password\n\n";
    
    echo "🎯 NEXT STEPS:\n";
    echo "1. Login sebagai admin\n";
    echo "2. Buka Dashboard → klik Detail pada booking {$booking->booking_code}\n";
    echo "3. Catat pembayaran offline sebesar Rp " . number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') . "\n";
    echo "4. Verifikasi pendapatan bulan ini bertambah!\n\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
