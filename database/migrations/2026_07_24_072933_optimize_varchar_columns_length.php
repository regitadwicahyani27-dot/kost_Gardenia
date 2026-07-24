<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Optimasi panjang kolom varchar dengan prinsip:
     * 1. Kolom PATH FILE (avatar, proof_path, photo_path) TETAP varchar(255)
     * 2. Kolom lainnya disesuaikan dengan kebutuhan data real
     * 3. Tidak mengubah tabel bawaan Laravel (cache, jobs, sessions, dll)
     */
    public function up(): void
    {
        // TABEL USERS
        // Kolom avatar TETAP varchar(255) karena menyimpan path file
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 100)->change();           // Nama orang umumnya < 100 karakter
            $table->string('phone', 20)->nullable()->change(); // Nomor telepon max 20 karakter
            $table->string('role', 20)->default('user')->change(); // 'admin' atau 'user'
            // avatar tetap varchar(255) - TIDAK DIUBAH karena path file
        });

        // TABEL ROOMS
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('name', 50)->change(); // Nama kamar seperti "Kamar 17" cukup 50 karakter
            // type sudah enum - tidak perlu diubah
        });

        // TABEL ROOM_PHOTOS
        // photo_path TETAP varchar(255) - TIDAK DIUBAH karena path file

        // TABEL BOOKINGS
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_code', 20)->change(); // Format "GDN-86708609" cukup 20 karakter
            $table->string('cancelled_by', 100)->nullable()->change(); // Nama user yang cancel
            // status sudah enum - tidak perlu diubah
        });

        // TABEL PAYMENTS
        // proof_path TETAP varchar(255) - TIDAK DIUBAH karena path file
        // payment_method, payment_type, status sudah enum - tidak perlu diubah

        // TABEL FACILITIES
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('name', 100)->change(); // Nama fasilitas seperti "AC", "WiFi", dll
            $table->string('icon', 100)->nullable()->change(); // Nama icon/class icon
        });

        // TABEL TESTIMONIALS
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('name', 100)->nullable()->change(); // Nama pemberi testimoni
            $table->string('label', 50)->nullable()->change(); // Label seperti "Alumni", "Penghuni"
            // status sudah enum - tidak perlu diubah
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke varchar(255) default
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('phone')->nullable()->change();
            $table->string('role')->default('user')->change();
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->string('name')->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_code')->change();
            $table->string('cancelled_by')->nullable()->change();
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('icon')->nullable()->change();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('label')->nullable()->change();
        });
    }
};
