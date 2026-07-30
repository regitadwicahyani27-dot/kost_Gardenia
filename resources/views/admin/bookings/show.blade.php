@extends('layouts.admin')

@section('title', 'Detail Booking - Admin Kos Putri Gardenia')

@section('content')
<div>
    <a href="{{ route('admin.booking.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2F4538] transition mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Booking
    </a>

    @php
        $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-gray-100 text-gray-700'];
        $statusLabels = ['pending' => 'Menunggu Konfirmasi', 'confirmed' => 'Dikonfirmasi', 'active' => 'Sedang Aktif', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Info Booking --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Header --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="bg-[#2F4538] text-white px-6 py-5 flex items-center justify-between">
                    <div>
                        <h2 class="font-display text-xl font-bold">Detail Booking</h2>
                        <p class="text-white/70 text-sm mt-1 font-mono">{{ $booking->booking_code }}</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-white/20">
                        {{ $statusLabels[$booking->status] ?? $booking->status }}
                    </span>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Info Kamar --}}
                    <div class="flex items-center gap-4 bg-gray-50 rounded-xl p-4">
                        <img src="{{ $booking->room->primaryPhoto ? asset('storage/' . $booking->room->primaryPhoto->photo_path) : 'https://via.placeholder.com/64x64/8B9D83/ffffff?text=Room' }}"
                             class="w-16 h-16 rounded-xl object-cover" />
                        <div>
                            <p class="font-semibold text-gray-900">{{ $booking->room->name }}</p>
                            <p class="text-xs text-gray-400">{{ strtoupper($booking->room->type) }} · Lantai {{ $booking->room->floor }}</p>
                            <p class="text-sm font-bold text-[#2F4538]">Rp {{ number_format($booking->room->price, 0, ',', '.') }}/bulan</p>
                        </div>
                    </div>

                    {{-- Detail Grid --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Tanggal Masuk</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $booking->check_in_date->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Durasi Sewa</p>
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

                    @if($booking->notes)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <p class="text-xs font-semibold text-blue-800 mb-1">Catatan</p>
                        <p class="text-sm text-blue-700">{{ $booking->notes }}</p>
                    </div>
                    @endif

                    {{-- Alasan Pembatalan --}}
                    @if($booking->status === 'cancelled' && $booking->cancelled_reason)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                        <p class="text-xs font-semibold text-red-800 mb-1">Alasan Pembatalan</p>
                        <p class="text-sm text-red-700">{{ $booking->cancelled_reason }}</p>
                        <p class="text-xs text-red-500 mt-2">
                            Dibatalkan oleh: {{ $booking->cancelled_by === 'admin' ? 'Admin' : ($booking->cancelled_by === 'user' ? 'Pengguna' : ($booking->cancelled_by === 'system' ? 'Sistem' : $booking->cancelled_by)) }}
                            &middot; {{ $booking->updated_at->translatedFormat('d F Y H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Riwayat Pembayaran --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Riwayat Pembayaran</h3>
                </div>
                <div class="p-6">
                    @if($booking->payments->count())
                    <div class="space-y-3">
                        @foreach($booking->payments as $payment)
                        @php
                            $payStatusColors = ['pending' => 'bg-amber-100 text-amber-700', 'verified' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
                            $payStatusLabels = ['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'];
                            $typeLabels = ['dp' => 'Uang Muka (DP)', 'monthly' => 'Bulanan', 'full' => 'Penuh'];
                        @endphp
                        <div class="border border-gray-100 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $typeLabels[$payment->payment_type] ?? $payment->payment_type }} · 
                                        {{ $payment->payment_method === 'cash' ? 'Tunai (Offline)' : strtoupper($payment->payment_method) }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $payment->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $payStatusColors[$payment->status] ?? '' }}">
                                        {{ $payStatusLabels[$payment->status] ?? $payment->status }}
                                    </span>
                                </div>
                            </div>

                            {{-- Bukti Bayar --}}
                            @if($payment->proof_path)
                            <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-3">
                                <img src="{{ asset('storage/' . $payment->proof_path) }}" alt="Bukti Bayar"
                                     class="w-16 h-16 rounded-lg object-cover cursor-pointer hover:opacity-80 transition"
                                     onclick="window.open('{{ asset('storage/' . $payment->proof_path) }}', '_blank')" />
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Bukti Pembayaran</p>
                                    <p class="text-[10px] text-gray-400">Klik untuk memperbesar</p>
                                </div>
                            </div>
                            @endif

                            {{-- Catatan untuk pembayaran cash --}}
                            @if($payment->payment_method === 'cash' && $payment->notes)
                            <div class="bg-blue-50 rounded-lg p-3 mt-2">
                                <p class="text-xs font-semibold text-blue-800 mb-1">Catatan</p>
                                <p class="text-xs text-blue-700">{{ $payment->notes }}</p>
                            </div>
                            @endif

                            {{-- Verifikasi Admin --}}
                            @if($payment->status === 'pending')
                            <div class="flex gap-2 mt-3">
                                <form action="{{ route('admin.payment.verify', $payment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="flex items-center gap-1.5 text-xs font-semibold text-white bg-green-500 px-4 py-2 rounded-full hover:bg-green-600 transition">
                                        <span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('check') !!}</span> Verifikasi
                                    </button>
                                </form>
                                <form action="{{ route('admin.payment.reject', $payment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="flex items-center gap-1.5 text-xs font-semibold text-white bg-red-500 px-4 py-2 rounded-full hover:bg-red-600 transition"
                                            onclick="event.preventDefault(); const f=this.closest('form'); showConfirm('Tolak pembayaran ini?', function() { f.submit(); })">
                                        <span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('close') !!}</span> Tolak
                                    </button>
                                </form>
                            </div>
                            @endif

                            @if($payment->verified_at)
                            <p class="text-[10px] text-gray-400 mt-2">Diverifikasi: {{ $payment->verified_at->format('d M Y H:i') }} oleh {{ $payment->verifiedBy->name ?? '-' }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-400 text-center py-6">Belum ada pembayaran dari pengguna ini.</p>
                    @endif
                </div>
            </div>

            {{-- Form Catat Pembayaran Offline --}}
            @if(in_array($booking->status, ['confirmed', 'active']))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-green-50">
                    <h3 class="font-semibold text-green-800">💰 Catat Pembayaran Offline</h3>
                    <p class="text-xs text-green-600 mt-1">Untuk pelunasan tunai yang dibayar di lokasi kos</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.booking.manual-payment', $booking) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nominal Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   name="amount"
                                   value="{{ $booking->total_price - $booking->dp_amount }}"
                                   required
                                   min="0"
                                   step="1"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('amount') border-red-400 @enderror"
                                   placeholder="Masukkan nominal">
                            <p class="text-xs text-gray-400 mt-1">Default: sisa pembayaran (Rp {{ number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') }})</p>
                            @error('amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Catatan (opsional)
                            </label>
                            <textarea name="notes"
                                      rows="2"
                                      class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none @error('notes') border-red-400 @enderror"
                                      placeholder="Contoh: Pelunasan tunai saat check-in"></textarea>
                            @error('notes') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                onclick="return confirm('Yakin ingin mencatat pembayaran offline ini? Booking akan otomatis diselesaikan.')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold text-sm py-3 rounded-xl transition">
                            Simpan Pembayaran Offline
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        {{-- Kolom Kanan: Info Pengguna + Aksi --}}
        <div class="space-y-6">

            {{-- Info Pengguna --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Data Pengguna</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#2F4538] text-white flex items-center justify-center text-sm font-semibold">
                            {{ substr($booking->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $booking->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $booking->user->email }}</p>
                        </div>
                    </div>
                    @if($booking->user->phone)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="w-3.5 h-3.5 text-gray-400">{!! \App\Support\Icons::get('phone') !!}</span> {{ $booking->user->phone }}
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->user->phone) }}" target="_blank"
                           class="text-[10px] font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full ml-auto hover:bg-green-100">
                            WhatsApp
                        </a>
                    </div>
                    @endif
                    @if($booking->user->address)
                    <p class="text-xs text-gray-500 flex items-start gap-1.5"><span class="w-3.5 h-3.5 mt-0.5 text-gray-400 flex-shrink-0">{!! \App\Support\Icons::get('map-pin') !!}</span> {{ $booking->user->address }}</p>
                    @endif
                </div>
            </div>

            {{-- Ubah Status --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Ubah Status</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.booking.status', $booking) }}" method="POST" class="space-y-3" x-data="{ selectedStatus: '{{ $booking->status }}' }">
                        @csrf
                        @method('PATCH')
                        <select name="status" x-model="selectedStatus" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538]">
                            @foreach(['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div x-show="selectedStatus === 'cancelled'" x-transition>
                            <textarea name="cancel_reason" rows="2" placeholder="Alasan pembatalan (opsional)..."
                                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition">
                            Simpan Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="bg-[#2F4538] text-white rounded-2xl p-6">
                <p class="text-xs text-white/60 mb-1">Sisa Pembayaran</p>
                <p class="text-2xl font-bold">Rp {{ number_format($booking->total_price - $booking->dp_amount, 0, ',', '.') }}</p>
                <p class="text-xs text-white/50 mt-2">Dibayar saat check-in di lokasi kos</p>
            </div>
        </div>
    </div>
</div>
@endsection
