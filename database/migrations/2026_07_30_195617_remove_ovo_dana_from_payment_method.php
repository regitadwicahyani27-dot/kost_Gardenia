<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum payment_method: hapus 'ovo' dan 'dana'
        // Hanya tersisa: 'qris', 'bca', 'cash'
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('qris', 'bca', 'cash') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum lengkap
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('qris', 'dana', 'ovo', 'bca', 'cash') NOT NULL");
    }
};
