<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;

// ─── HALAMAN PUBLIK ────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kamar', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/kamar/{room}', [RoomController::class, 'show'])->name('rooms.show');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');

// ─── ROUTE BREEZE (Login, Register, dll) ──────────────────
require __DIR__.'/auth.php';

// ─── DASHBOARD: Redirect setelah login ────────────────────
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

// ─── DASHBOARD USER ────────────────────────────────────────
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [BookingController::class, 'dashboard'])->name('dashboard');
    Route::get('/kamar', [RoomController::class, 'indexUser'])->name('rooms');
    Route::get('/kamar/{room}', [RoomController::class, 'showUser'])->name('rooms.show');
    Route::get('/booking/{room}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/riwayat', [BookingController::class, 'history'])->name('booking.history');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/pembayaran/{booking}', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
});

// ─── DASHBOARD ADMIN ───────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Kamar
    Route::resource('kamar', \App\Http\Controllers\Admin\RoomController::class);
    Route::post('/kamar/{kamar}/foto', [\App\Http\Controllers\Admin\RoomController::class, 'uploadPhoto'])->name('kamar.foto');
    Route::delete('/foto/{photo}', [\App\Http\Controllers\Admin\RoomController::class, 'deletePhoto'])->name('foto.delete');
    Route::patch('/kamar/{kamar}/toggle', [\App\Http\Controllers\Admin\RoomController::class, 'toggleAvailability'])->name('kamar.toggle'); // ← TAMBAH INI

    // Booking (hanya detail & update status — index dihapus)
    Route::get('/booking/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{booking}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('booking.status');
    Route::post('/booking/{booking}/manual-payment', [\App\Http\Controllers\Admin\BookingController::class, 'recordManualPayment'])->name('booking.manual-payment');

    // Pembayaran
    Route::get('/pembayaran', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payment.index');
    Route::patch('/pembayaran/{payment}/verify', [\App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payment.verify');
    Route::patch('/pembayaran/{payment}/reject', [\App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('payment.reject');

    // Testimoni (dikelola langsung oleh admin, tidak ada pengajuan dari user)
    Route::resource('testimoni', \App\Http\Controllers\Admin\TestimonialController::class)
        ->parameters(['testimoni' => 'testimonial'])
        ->names('testimonial')
        ->except(['show']);
});
