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
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">Total Kamar</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_rooms'] }}</p>
            <p class="text-xs text-gray-400 mt-1"><span class="text-green-600 font-semibold">{{ $stats['available_rooms'] }} tersedia</span> &middot; {{ $stats['occupied_rooms'] }} terisi</p>
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

    {{-- ============================================= --}}
    {{-- SEKSI VERIFIKASI & STATUS PEMBAYARAN --}}
    {{-- ============================================= --}}
    <div x-data="paymentActions" class="mt-8 relative">
        {{-- Toast Notification Real-Time --}}
        <div x-show="newNotification.show" x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             class="fixed top-6 right-6 z-50 bg-[#2F4538] text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3.5 border border-emerald-500/40 backdrop-blur-md"
             style="display: none;">
            <div class="relative flex items-center justify-center">
                <span class="absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75 animate-ping"></span>
                <div class="relative w-10 h-10 rounded-xl bg-amber-400/20 text-amber-300 border border-amber-400/40 flex items-center justify-center shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-emerald-400">Notifikasi Real-Time</p>
                <p class="text-xs font-semibold text-white mt-0.5" x-text="newNotification.message"></p>
            </div>
        </div>

        <div class="flex items-center justify-between mb-4">
            <h2 class="font-display text-xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#2F4538]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Verifikasi & Status Pembayaran
            </h2>
            @if($stats['pending_payments'] > 0)
            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full border border-amber-200 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                {{ $stats['pending_payments'] }} Menunggu Verifikasi
            </span>
            @endif
        </div>

        {{-- Filter Tabs --}}
        <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
            <a href="{{ route('admin.dashboard') }}"
               class="px-5 py-2 rounded-full text-xs font-semibold transition {{ !request('status') ? 'bg-[#2F4538] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}"
               class="px-5 py-2 rounded-full text-xs font-semibold transition {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Pending
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'verified']) }}"
               class="px-5 py-2 rounded-full text-xs font-semibold transition {{ request('status') === 'verified' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Terverifikasi
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'rejected']) }}"
               class="px-5 py-2 rounded-full text-xs font-semibold transition {{ request('status') === 'rejected' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Ditolak
            </a>
        </div>

        @if($paymentBookings->count())
        <div class="max-h-[520px] overflow-y-auto pr-1.5 space-y-4 mb-4 border border-gray-100/80 rounded-2xl p-2 bg-gray-50/20" style="scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;">
            @foreach($paymentBookings as $booking)
            @php
                $totalVerified = $booking->payments->where('status', 'verified')->sum('amount');
                $hasPendingPayment = $booking->payments->where('status', 'pending')->first();
                $hasRejectedPayment = $booking->payments->where('status', 'rejected')->first();
                $sisaAmount = max(0, $booking->total_price - $totalVerified);
                $isLunas = ($totalVerified >= $booking->total_price) || ($booking->status === 'completed');
                $isRejected = ($booking->status === 'cancelled') || ($hasRejectedPayment && !$hasPendingPayment && $totalVerified == 0);
            @endphp
            <div x-data="{ openHistory: {{ ($isLunas || $isRejected) ? 'false' : 'true' }} }"
                 class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition">
                {{-- Header Card (1 Booking) --}}
                <div class="p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gradient-to-r from-gray-50/50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#2F4538]/10 text-[#2F4538] flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-gray-900">{{ $booking->user->name }}</p>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $isLunas ? 'bg-green-100 text-green-800' : ($hasPendingPayment ? 'bg-amber-100 text-amber-800' : ($isRejected ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ $isLunas ? 'LUNAS' : ($hasPendingPayment ? 'MENUNGGU VERIFIKASI' : ($isRejected ? 'DITOLAK' : 'DP TERBAYAR')) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5 flex-wrap">
                                <span>Kode: <span class="font-semibold text-gray-700">{{ $booking->booking_code }}</span></span>
                                <span>&middot;</span>
                                <span class="font-semibold text-gray-700">{{ $booking->room->name ?? '-' }}</span>
                                <span>&middot;</span>
                                <button type="button"
                                        @click="$store.bookingModal.show(window._bookingDetailData[{{ $booking->id }}])"
                                        class="text-[#2F4538] font-bold hover:underline inline-flex items-center gap-0.5 text-xs bg-[#2F4538]/10 px-2 py-0.5 rounded-full hover:bg-[#2F4538]/20 transition">
                                    <span>Detail Booking</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </p>
                        </div>
                    </div>

                    <div class="text-right flex flex-col items-end gap-1">
                        <p class="text-xs text-gray-400">Total Akumulasi Pembayaran</p>
                        <p class="text-lg font-extrabold text-[#2F4538]">
                            Rp {{ number_format($totalVerified, 0, ',', '.') }}
                            <span class="text-xs font-normal text-gray-400">/ Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </p>

                        @if($sisaAmount > 0 && !$hasPendingPayment && !$isRejected)
                        <button @click="openManualPaymentModal('{{ route('admin.booking.manual-payment', $booking) }}', '{{ addslashes($booking->user->name) }}', '{{ $booking->booking_code }}', '{{ addslashes($booking->room->name ?? '-') }}', {{ $sisaAmount }}, '{{ number_format($sisaAmount, 0, ',', '.') }}')"
                                class="mt-1 inline-flex items-center gap-1.5 bg-[#2F4538] text-white text-xs font-bold px-3 py-1.5 rounded-full hover:bg-[#26392E] transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Catat Pelunasan</span>
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Payments List Inside Card --}}
                <div class="p-4 bg-gray-50/30">
                    @if($isLunas || $isRejected)
                    {{-- DROPDOWN TOGGLE HEADER (AKTIF JIKA LUNAS / DITOLAK) --}}
                    <button type="button"
                            @click="openHistory = !openHistory"
                            class="w-full flex items-center justify-between px-1 py-1 text-[11px] font-bold text-gray-500 hover:text-gray-800 uppercase tracking-wider transition group cursor-pointer">
                        <span>Rincian Riwayat Pembayaran ({{ $booking->payments->count() }})</span>
                        <div class="flex items-center gap-1 text-xs text-[#2F4538] font-semibold normal-case">
                            <span x-text="openHistory ? 'Sembunyikan' : 'Tampilkan Rincian'"></span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="openHistory ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>
                    @else
                    {{-- ALWAYS OPEN HEADER (JIKA DP TERBAYAR / MENUNGGU VERIFIKASI) --}}
                    <div class="px-1 py-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        <span>Rincian Riwayat Pembayaran ({{ $booking->payments->count() }})</span>
                    </div>
                    @endif

                    {{-- LIST RINCIAN PEMBAYARAN --}}
                    <div x-show="openHistory" x-transition.opacity.duration.200ms class="space-y-2 mt-2">
                        @foreach($booking->payments as $payment)
                        @php
                            $payStatusColors = ['pending' => 'bg-amber-100 text-amber-700', 'verified' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
                            $payStatusLabels = ['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'];
                            $typeLabels = ['dp' => 'Uang Muka (DP)', 'monthly' => 'Bulanan', 'full' => 'Pelunasan Tunai'];
                        @endphp
                        <div class="p-3 bg-white rounded-xl border border-gray-100 flex items-start gap-4">
                            {{-- Bukti Bayar --}}
                            @if($payment->proof_path)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $payment->proof_path) }}" alt="Bukti"
                                     @click="openProofModal('{{ asset('storage/' . $payment->proof_path) }}', '{{ $booking->user->name }}', '{{ $booking->booking_code }}', '{{ number_format($payment->amount, 0, ',', '.') }}', '{{ $payment->created_at->translatedFormat('d F Y H:i') }}')"
                                     class="w-12 h-12 rounded-lg object-cover flex-shrink-0 cursor-pointer hover:opacity-80 transition hover:ring-2 hover:ring-[#2F4538] hover:ring-offset-1"
                                     data-testid="payment-proof-image" />
                            </div>
                            @else
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-emerald-100 text-green-700 border border-green-200/80 flex items-center justify-center flex-shrink-0 shadow-sm" title="Pembayaran Tunai">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            @endif

                            {{-- Payment Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-xs font-bold text-gray-800">{{ $typeLabels[$payment->payment_type] ?? $payment->payment_type }}</p>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $payStatusColors[$payment->status] ?? '' }}">
                                        {{ $payStatusLabels[$payment->status] ?? $payment->status }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $payment->payment_method === 'cash' ? 'Tunai (Offline)' : strtoupper($payment->payment_method) }} &middot; {{ $payment->created_at->translatedFormat('d M Y H:i') }}
                                </p>
                                @if($payment->notes)
                                <p class="text-xs text-gray-500 mt-0.5 italic">"{{ $payment->notes }}"</p>
                                @endif
                            </div>

                            {{-- Payment Amount & Action --}}
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-bold text-[#2F4538]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>

                                @if($payment->status === 'pending')
                                <div class="flex gap-1.5 justify-end mt-1.5">
                                    <form action="{{ route('admin.payment.verify', $payment) }}" method="POST" onsubmit="event.preventDefault(); const f=this; showConfirm('Setujui pembayaran ini?', function() { f.submit(); })">
                                        @csrf
                                        @method('PATCH')
                                        <button class="flex items-center gap-1 text-[11px] font-semibold text-white bg-[#2F4538] px-2.5 py-1 rounded-lg hover:bg-[#26392E] transition">
                                            <span class="w-3 h-3">{!! \App\Support\Icons::get('check') !!}</span> Setujui
                                        </button>
                                    </form>

                                    <button @click="openReject({{ $payment->id }}, '{{ route('admin.payment.reject', $payment) }}')"
                                            class="flex items-center gap-1 text-[11px] font-semibold text-red-500 bg-red-50 px-2.5 py-1 rounded-lg hover:bg-red-100 transition">
                                        <span class="w-3 h-3">{!! \App\Support\Icons::get('close') !!}</span> Tolak
                                    </button>
                                </div>
                                @endif

                                @if($payment->verified_at)
                                <p class="text-[10px] text-gray-400 mt-1">
                                    Verified {{ $payment->verified_at->format('d M H:i') }} oleh {{ $payment->verifiedBy?->name ?? 'Admin' }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 mb-6">
            {{ $paymentBookings->links() }}
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 mb-6">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3 text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <p class="text-gray-400 text-sm">Tidak ada transaksi pembayaran ditemukan.</p>
        </div>
        @endif

        {{-- MODAL: Alasan Penolakan --}}
        <div x-show="rejectModal.show" x-transition.opacity.duration.200ms
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
             style="display: none;"
             @click.self="rejectModal.show = false">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6" @click.stop>
                <h2 class="font-display text-lg font-bold text-gray-900 mb-1">Tolak Pembayaran</h2>
                <p class="text-sm text-gray-500 mb-4">Pembayaran akan ditolak dan booking akan dibatalkan.</p>

                <form :action="rejectModal.action" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alasan Penolakan <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea name="reject_notes" rows="3" x-model="rejectModal.notes"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-red-400 focus:outline-none resize-none"
                                  placeholder="Tulis alasan penolakan..."></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="rejectModal.show = false"
                                class="flex-1 bg-gray-100 text-gray-700 font-semibold text-sm py-3 rounded-xl hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 bg-red-500 text-white font-bold text-sm py-3 rounded-xl hover:bg-red-600 transition">
                            Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL: Lihat Bukti Pembayaran --}}
        <div x-show="proofModal.show" 
             x-transition.opacity.duration.300ms
             @keydown.escape.window="closeProofModal()"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
             style="display: none;"
             @click.self="closeProofModal()">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl mx-auto overflow-hidden" @click.stop>
                <div class="bg-gradient-to-r from-[#2F4538] to-[#3d5a49] p-6 text-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="font-display text-2xl font-bold mb-2">Bukti Pembayaran</h2>
                            <div class="space-y-1 text-sm opacity-90">
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span x-text="proofModal.userName"></span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    <span x-text="proofModal.bookingCode"></span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="font-bold text-yellow-300">Rp <span x-text="proofModal.amount"></span></span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span x-text="proofModal.date"></span>
                                </p>
                            </div>
                        </div>
                        <button @click="closeProofModal()" class="text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 bg-gray-50">
                    <div class="bg-white rounded-2xl p-4 shadow-inner">
                        <img :src="proofModal.imageUrl" alt="Bukti Pembayaran" class="w-full h-auto max-h-[60vh] object-contain rounded-xl" />
                    </div>
                </div>
                <div class="p-6 bg-white border-t border-gray-100 flex gap-3">
                    <button @click="downloadProof()" class="flex-1 flex items-center justify-center gap-2 bg-[#2F4538] text-white font-semibold py-3.5 rounded-xl hover:bg-[#1f2e26] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Buka di Tab Baru
                    </button>
                    <button @click="closeProofModal()" class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl hover:bg-gray-200 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- MODAL: Catat Pelunasan / Pembayaran Offline --}}
        <div x-show="manualPaymentModal.show" x-transition.opacity.duration.200ms
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             style="display: none;"
             @click.self="manualPaymentModal.show = false">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-auto overflow-hidden" @click.stop>
                <div class="bg-gradient-to-r from-[#2F4538] to-[#3d5a49] p-5 text-white flex items-center justify-between">
                    <div>
                        <h2 class="font-display text-lg font-bold">💰 Catat Pelunasan Tunai</h2>
                        <p class="text-xs text-white/80 mt-0.5" x-text="manualPaymentModal.bookingCode + ' &middot; ' + manualPaymentModal.userName"></p>
                    </div>
                    <button @click="manualPaymentModal.show = false" class="text-white/80 hover:text-white rounded-full p-1.5 hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="manualPaymentModal.action" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
                        <p class="text-xs font-semibold text-amber-800">Sisa Pembayaran Saat Ini</p>
                        <p class="text-xl font-extrabold text-amber-700 mt-0.5">Rp <span x-text="manualPaymentModal.sisaFormatted"></span></p>
                        <p class="text-[11px] text-amber-600 mt-1">Sewa Kamar <span class="font-bold" x-text="manualPaymentModal.roomName"></span></p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nominal Pembayaran (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" x-model="manualPaymentModal.amount" required min="1" step="1"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-[#2F4538] focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Catatan <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="text" name="notes" x-model="manualPaymentModal.notes"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#2F4538] focus:outline-none"
                               placeholder="Contoh: Pelunasan tunai saat check-in" />
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="manualPaymentModal.show = false"
                                class="flex-1 bg-gray-100 text-gray-700 font-semibold text-sm py-3 rounded-xl hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition shadow-md">
                            Simpan Pelunasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Data JSON untuk modal --}}
@php
$statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-gray-100 text-gray-700'];
$statusLabels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'];

$allDashboardBookings = collect($paymentBookings->items())->unique('id');

$mappedBookings = $allDashboardBookings->keyBy('id')->map(function($booking) use ($statusLabels, $statusColors) {
    $totalVerified = $booking->payments->where('status', 'verified')->sum('amount');
    $sisaAmount = max(0, $booking->total_price - $totalVerified);

    return [
        'id' => $booking->id,
        'booking_code' => $booking->booking_code,
        'status' => $booking->status,
        'statusLabel' => $statusLabels[$booking->status] ?? $booking->status,
        'statusBadgeClass' => $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700',
        'createdAt' => $booking->created_at->translatedFormat('d M Y H:i'),
        'userName' => $booking->user->name,
        'userPhone' => $booking->user->phone ?? '',
        'roomName' => $booking->room->name,
        'roomType' => strtoupper($booking->room->type),
        'roomFloor' => $booking->room->floor,
        'roomImage' => $booking->room->primaryPhoto ? asset('storage/' . $booking->room->primaryPhoto->photo_path) : '',
        'checkInDate' => $booking->check_in_date->translatedFormat('d F Y'),
        'duration' => $booking->duration_months,
        'totalPrice' => number_format($booking->total_price, 0, ',', '.'),
        'dpAmount' => number_format($booking->dp_amount, 0, ',', '.'),
        'sisa' => number_format($sisaAmount, 0, ',', '.'),
        'sisaRaw' => $sisaAmount,
        'cancelledReason' => $booking->cancelled_reason,
        'cancelledBy' => $booking->cancelled_by,
        'showUrl' => route('admin.booking.show', $booking),
        'payments' => $booking->payments->map(function($payment) {
            return [
                'id' => $payment->id,
                'type' => ['dp' => 'Uang Muka (DP)', 'monthly' => 'Bulanan', 'full' => 'Pelunasan Tunai'][$payment->payment_type] ?? $payment->payment_type,
                'method' => $payment->payment_method === 'cash' ? 'CASH' : strtoupper($payment->payment_method),
                'amount' => number_format($payment->amount, 0, ',', '.'),
                'status' => $payment->status,
                'statusLabel' => ['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'][$payment->status] ?? $payment->status,
                'date' => $payment->created_at->format('d M Y H:i'),
                'proofUrl' => $payment->proof_path ? asset('storage/' . $payment->proof_path) : '',
            ];
        })->toArray()
    ];
});
@endphp
<script>
    window._bookingDetailData = @json($mappedBookings);
</script>

{{-- Modal --}}
@include('components.booking-detail-modal')
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('paymentActions', () => ({
            rejectModal: {
                show: false,
                action: '',
                notes: '',
            },
            proofModal: {
                show: false,
                imageUrl: '',
                userName: '',
                bookingCode: '',
                amount: '',
                date: '',
            },
            manualPaymentModal: {
                show: false,
                action: '',
                userName: '',
                bookingCode: '',
                roomName: '',
                sisaRaw: 0,
                sisaFormatted: '',
                amount: 0,
                notes: 'Pelunasan tunai saat check-in',
            },
            newNotification: {
                show: false,
                message: '',
            },
            lastMaxPaymentId: null,
            lastMaxBookingId: null,

            init() {
                this.checkNewTransactions(true);
                setInterval(() => {
                    this.checkNewTransactions(false);
                }, 10000);
            },

            async checkNewTransactions(isInitial = false) {
                try {
                    const res = await fetch("{{ route('admin.dashboard.check-new') }}", {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!res.ok) return;
                    const data = await res.json();

                    if (isInitial) {
                        this.lastMaxPaymentId = data.max_payment_id;
                        this.lastMaxBookingId = data.max_booking_id;
                        return;
                    }

                    const hasNewBooking = data.max_booking_id > this.lastMaxBookingId;
                    const hasNewPayment = data.max_payment_id > this.lastMaxPaymentId;

                    if (hasNewBooking || hasNewPayment) {
                        this.lastMaxPaymentId = data.max_payment_id;
                        this.lastMaxBookingId = data.max_booking_id;

                        if (!this.rejectModal.show && !this.manualPaymentModal.show) {
                            this.newNotification.message = 'Booking / Pembayaran Baru Masuk! Memperbarui halaman...';
                            this.newNotification.show = true;
                            setTimeout(() => {
                                window.location.reload();
                            }, 1200);
                        }
                    }
                } catch (e) {
                    // Silent fail
                }
            },

            openReject(paymentId, actionUrl) {
                this.rejectModal.action = actionUrl;
                this.rejectModal.notes = '';
                this.rejectModal.show = true;
            },

            openProofModal(imageUrl, userName, bookingCode, amount, date) {
                this.proofModal.imageUrl = imageUrl;
                this.proofModal.userName = userName;
                this.proofModal.bookingCode = bookingCode;
                this.proofModal.amount = amount;
                this.proofModal.date = date;
                this.proofModal.show = true;
            },

            openManualPaymentModal(actionUrl, userName, bookingCode, roomName, sisaRaw, sisaFormatted) {
                this.manualPaymentModal.action = actionUrl;
                this.manualPaymentModal.userName = userName;
                this.manualPaymentModal.bookingCode = bookingCode;
                this.manualPaymentModal.roomName = roomName;
                this.manualPaymentModal.sisaRaw = sisaRaw;
                this.manualPaymentModal.sisaFormatted = sisaFormatted;
                this.manualPaymentModal.amount = sisaRaw;
                this.manualPaymentModal.notes = 'Pelunasan tunai saat check-in';
                this.manualPaymentModal.show = true;
            },

            closeProofModal() {
                this.proofModal.show = false;
            },

            downloadProof() {
                window.open(this.proofModal.imageUrl, '_blank');
            },
        }));
    });
</script>
@endpush
