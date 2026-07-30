<?php
/**
 * Script untuk refresh database + seed data lengkap
 * Jalankan dengan: php refresh-db.php
 */

echo "🔄 DATABASE REFRESH & SEED\n";
echo str_repeat("=", 60) . "\n\n";

echo "⚠️  WARNING: Ini akan menghapus SEMUA data di database!\n";
echo "Apakah Anda yakin? (ketik 'yes' untuk lanjut): ";

$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if ($line !== 'yes') {
    echo "\n❌ Dibatalkan oleh user.\n";
    exit(0);
}

echo "\n🚀 Starting database refresh...\n\n";

$output = [];
$returnCode = 0;

// Change to project directory
chdir(__DIR__);

// Step 1: Migrate fresh
echo "📦 Step 1: Running migrate:fresh...\n";
exec('php artisan migrate:fresh 2>&1', $output, $returnCode);

foreach ($output as $line) {
    echo "  " . $line . "\n";
}

if ($returnCode !== 0) {
    echo "\n❌ Migration failed with exit code: {$returnCode}\n";
    exit(1);
}

echo "\n✅ Migration completed!\n\n";

// Step 2: Seed database
echo "🌱 Step 2: Seeding database...\n";
$output = [];
exec('php artisan db:seed 2>&1', $output, $returnCode);

foreach ($output as $line) {
    echo "  " . $line . "\n";
}

if ($returnCode !== 0) {
    echo "\n❌ Seeding failed with exit code: {$returnCode}\n";
    exit(1);
}

echo "\n✅ Seeding completed!\n\n";

// Step 3: Summary
echo str_repeat("=", 60) . "\n";
echo "🎉 DATABASE REFRESH BERHASIL!\n";
echo str_repeat("=", 60) . "\n\n";

echo "📊 DATA YANG DIBUAT:\n";
echo "- 1 Admin + 3 User\n";
echo "- 3 Kamar (1 available, 2 occupied)\n";
echo "- 7 Fasilitas\n";
echo "- 3 Booking (2 confirmed, 1 pending)\n";
echo "- 3 Payment (2 verified, 1 pending)\n\n";

echo "🔐 LOGIN INFO:\n";
echo "Admin: admin@gardenia.com / admin123\n";
echo "User 1: rere@gmail.com / password\n";
echo "User 2: cihuy@gmail.com / password\n";
echo "User 3: siti@gmail.com / password\n\n";

echo "💰 PENDAPATAN BULAN INI: Rp 500.000\n";
echo "📋 PEMBAYARAN PENDING: 1 (perlu verifikasi)\n\n";

echo "🎯 NEXT STEPS:\n";
echo "1. Login sebagai admin\n";
echo "2. Dashboard akan tampil dengan data lengkap\n";
echo "3. Test fitur pembayaran offline pada booking confirmed\n\n";

echo "✨ Happy testing!\n";
