<?php
/**
 * Script untuk menjalankan migration
 * Jalankan dengan: php run-migration.php
 */

echo "🔄 Running migration...\n\n";

$output = [];
$returnCode = 0;

// Change to project directory
chdir(__DIR__);

// Run migration command
exec('php artisan migrate --force 2>&1', $output, $returnCode);

// Display output
foreach ($output as $line) {
    echo $line . "\n";
}

echo "\n";

if ($returnCode === 0) {
    echo "✅ Migration berhasil!\n";
    exit(0);
} else {
    echo "❌ Migration gagal dengan exit code: {$returnCode}\n";
    exit(1);
}
