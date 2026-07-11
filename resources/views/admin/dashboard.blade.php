@extends('layouts.admin')

@section('title', 'Dashboard Admin - Kos Putri Gardenia')

@section('content')
<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-2xl md:text-3xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola Kos Putri Gardenia</p>
        </div>
        <span class="text-xs font-semibold bg-[#2F4538] text-white px-3 py-1.5 rounded-full">Admin</span>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">Total Kamar</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_rooms'] }}</p>
            <p class="text-xs text-gray-400 mt-1"><span class="text-green-600 font-semibold">{{ $stats['available_rooms'] }} tersedia</span> &middot; {{ $stats['occupied_rooms'] }} terisi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">Booking Pending</p>
            <p class="text-2xl font-bold text-amber-500">{{ $stats['pending_bookings'] }}</p>
            <p class="text-xs text-gray-400 mt-1">dari {{ $stats['total_bookings'] }} total</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">Pembayaran Pending</p>
            <p class="text-2xl font-bold text-blue-500">{{ $stats['pending_payments'] }}</p>
            <p class="text-xs text-gray-400 mt-1">perlu verifikasi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">Pendapatan Bulan Ini</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['monthly_income'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['monthly_verified_count'] }} pembayaran terverifikasi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">Total Pengguna</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['total_users'] }}</p>
            <p class="text-xs text-gray-400 mt-1">akun terdaftar</p>
        </div>
    </div>

    {{-- Akses Cepat --}}
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('admin.testimonial.index') }}" class="inline-flex items-center gap-2 bg-white border border-gray-100 shadow-sm rounded-full px-5 py-2.5 text-sm font-semibold text-gray-700 hover:border-[#2F4538]/40 hover:text-[#2F4538] transition">
            <span class="w-4 h-4">{!! \App\Support\Icons::get('chat') !!}</span>
            Kelola Testimoni Beranda
        </a>
    </div>

    {{-- Booking Terbaru --}}
    <h2 class="font-semibold text-gray-900 mb-4">Booking Terbaru</h2>
    @if($recentBookings->count())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Kode</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Pengguna</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Kamar</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Total</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                    @php
                        $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-gray-100 text-gray-700'];
                        $statusLabels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'];
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-mono text-xs font-semibold text-gray-700">{{ $booking->booking_code }}</td>
                        <td class="px-5 py-3 text-gray-900">{{ $booking->user->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $booking->room->name }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColors[$booking->status] ?? '' }}">{{ $statusLabels[$booking->status] ?? $booking->status }}</span>
                        </td>
                        <td class="px-5 py-3 font-semibold text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.booking.show', $booking) }}" class="text-[#2F4538] text-xs font-semibold hover:underline">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <p class="text-center text-gray-400 py-8 bg-white rounded-2xl border border-gray-100">Belum ada booking masuk.</p>
    @endif
</div>
@endsection
