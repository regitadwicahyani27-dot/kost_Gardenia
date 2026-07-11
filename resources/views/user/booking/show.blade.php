@extends('layouts.user')

@section('title', 'Detail Booking - Kos Putri Gardenia')

@section('content')

@include('components.payment-receipt')
<div class="max-w-2xl mx-auto px-6 py-10">
    <a href="{{ route('user.booking.history') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2F4538] transition mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Riwayat
    </a>

    @php
        $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-gray-100 text-gray-700'];
        $statusLabels = ['pending' => 'Menunggu Konfirmasi', 'confirmed' => 'Dikonfirmasi', 'active' => 'Sedang Aktif', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'];
    @endphp

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="bg-[#2F4538] text-white px-6 py-5 flex items-center justify-between">
            <div>
                <h2 class="font-display text-xl font-bold">Detail Booking</h2>
                <p class="text-white/70 text-sm mt-1">{{ $booking->booking_code }}</p>
            </div>
            <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-white/20">
                {{ $statusLabels[$booking->status] ?? $booking->status }}
            </span>
        </div>

        <div class="p-6 space-y-4">
            {{-- Info Kamar --}}
            <div class="flex items-center gap-4 bg-gray-50 rounded-xl p-4">
                <div class="w-16 h-16 rounded-xl bg-[#2F4538]/10 flex items-center justify-center p-4 text-[#2F4538]">{!! \App\Support\Icons::get('home') !!}</div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $booking->room->name }}</p>
                    <p class="text-xs text-gray-400">{{ strtoupper($booking->room->type) }} · Lantai {{ $booking->room->floor }}</p>
                </div>
            </div>

            {{-- Detail Grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Tanggal Masuk</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $booking->check_in_date->translatedFormat('d F Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Durasi</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $booking->duration_months }} bulan</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Total Harga</p>
                    <p class="text-sm font-bold text-[#2F4538]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">DP Dibayar</p>
                    <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Sisa Pembayaran --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-amber-800">Sisa Pembayaran</p>
                <p class="text-lg font-bold text-amber-700">Rp {{ number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') }}</p>
                <p class="text-xs text-amber-600 mt-1">Dibayar saat check-in di lokasi</p>
            </div>

            {{-- Riwayat Pembayaran --}}
            @if($booking->payments->count())
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Riwayat Pembayaran</h3>
                <div class="space-y-2">
                    @foreach($booking->payments as $payment)
                    @php
                        $payStatusColors = ['pending' => 'bg-amber-100 text-amber-700', 'verified' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
                        $payStatusLabels = ['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'];
                    @endphp
                    <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ strtoupper($payment->payment_type) }} · {{ strtoupper($payment->payment_method) }}</p>
                            <p class="text-xs text-gray-400">{{ $payment->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $payStatusColors[$payment->status] ?? '' }}">{{ $payStatusLabels[$payment->status] ?? $payment->status }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Upload Bukti Bayar --}}
            @if(in_array($booking->status, ['pending', 'confirmed']))
            <div class="border-t border-gray-100 pt-5">
                <h3 class="font-semibold text-gray-900 mb-3">Upload Bukti Pembayaran</h3>
                <form action="{{ route('user.payment.store', $booking) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <select name="payment_method" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('payment_method') border-red-400 @enderror">
                            <option value="qris">QRIS</option>
                            <option value="dana">DANA</option>
                            <option value="ovo">OVO</option>
                            <option value="bca">Transfer BCA</option>
                        </select>
                        @error('payment_method') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Bukti Bayar <span class="text-red-500">*</span></label>
                        <input type="file" name="proof" accept="image/*"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('proof') border-red-400 @enderror" />
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG · Maks. 2MB</p>
                        @error('proof') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition">
                        Upload Bukti Pembayaran
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
