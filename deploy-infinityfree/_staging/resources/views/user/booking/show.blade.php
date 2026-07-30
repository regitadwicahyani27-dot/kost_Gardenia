@extends('layouts.user')

@section('title', 'Detail Booking - Kos Putri Gardenia')

@section('content')
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

            {{-- DEBUG INFO (TEMPORARY - HAPUS SETELAH TESTING) --}}
            <div class="bg-red-50 border-2 border-red-500 rounded-xl p-4 mb-4">
                <p class="font-bold text-red-800 mb-2">🔍 DEBUG INFO - FILE VERSION: STAGING-V3</p>
                <p class="text-sm text-red-700">Status Booking: <strong>{{ $booking->status }}</strong></p>
                <p class="text-sm text-red-700">Apakah status = 'completed'? <strong>{{ $booking->status === 'completed' ? 'YA' : 'TIDAK' }}</strong></p>
                <p class="text-sm text-red-700">in_array confirmed/active? <strong>{{ in_array($booking->status, ['confirmed', 'active']) ? 'YA' : 'TIDAK' }}</strong></p>
                <p class="text-sm text-red-700">Total Payments: <strong>{{ $booking->payments->count() }}</strong></p>
                <p class="text-xs text-red-600 mt-2">Jika kotak ini tidak muncul, berarti file tidak terupdate!</p>
            </div>

            {{-- Sisa Pembayaran (hanya tampil jika belum lunas) --}}
            @if(in_array($booking->status, ['confirmed', 'active']))
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-amber-800">Sisa Pembayaran</p>
                <p class="text-lg font-bold text-amber-700">Rp {{ number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') }}</p>
                <p class="text-xs text-amber-600 mt-1">Dibayar saat check-in di lokasi</p>
            </div>
            @endif

            {{-- Status Lunas (tampil jika sudah completed) --}}
            @if($booking->status === 'completed')
            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-green-800">Pembayaran Lunas</p>
                        <p class="text-xs text-green-600 mt-0.5">Semua pembayaran telah diselesaikan</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Riwayat Pembayaran --}}
            @if($booking->payments->count())
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Riwayat Pembayaran</h3>
                <div class="space-y-3">
                    @foreach($booking->payments as $payment)
                    @php
                        $payStatusColors = ['pending' => 'bg-amber-100 text-amber-700', 'verified' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
                        $payStatusLabels = ['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'];
                        $payTypeLabels = ['dp' => 'Uang Muka (DP)', 'monthly' => 'Bulanan', 'full' => 'Penuh'];
                    @endphp
                    <div class="flex items-start gap-3 bg-gray-50 rounded-xl p-3 hover:bg-gray-100 transition">
                        {{-- Thumbnail / Ikon Metode Pembayaran --}}
                        @if($payment->payment_method === 'cash')
                            {{-- Kotak Hijau Pastel untuk Tunai/Cash --}}
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-green-200">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        @else
                            {{-- Foto Bukti Transfer untuk Metode Online --}}
                            @if($payment->proof_path)
                                <img src="{{ asset('storage/' . $payment->proof_path) }}" 
                                     alt="Bukti Transfer" 
                                     class="w-16 h-16 rounded-xl object-cover flex-shrink-0 cursor-pointer hover:opacity-80 transition border border-gray-200 hover:border-[#2F4538]"
                                     onclick="window.open('{{ asset('storage/' . $payment->proof_path) }}', '_blank')" />
                            @else
                                <div class="w-16 h-16 rounded-xl bg-gray-200 flex items-center justify-center flex-shrink-0 border border-gray-300">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                            @endif
                        @endif

                        {{-- Info Pembayaran --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $payTypeLabels[$payment->payment_type] ?? strtoupper($payment->payment_type) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        @if($payment->payment_method === 'cash')
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Tunai (Offline)
                                            </span>
                                        @else
                                            {{ strtoupper($payment->payment_method) }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $payment->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full whitespace-nowrap {{ $payStatusColors[$payment->status] ?? '' }}">
                                    {{ $payStatusLabels[$payment->status] ?? $payment->status }}
                                </span>
                            </div>
                            <p class="text-base font-bold text-[#2F4538]">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
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
