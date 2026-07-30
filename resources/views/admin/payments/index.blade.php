@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran - Admin Kos Putri Gardenia')

@section('content')
<div x-data="paymentActions">
    <h1 class="font-display text-2xl font-bold text-gray-900 mb-6">Verifikasi Pembayaran</h1>

    {{-- Filter Tabs --}}
    <div class="flex gap-2 mb-6">
        <a href="{{ route('admin.payment.index') }}"
           class="px-5 py-2 rounded-full text-xs font-semibold transition {{ !request('status') ? 'bg-[#2F4538] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Semua
        </a>
        <a href="{{ route('admin.payment.index', ['status' => 'pending']) }}"
           class="px-5 py-2 rounded-full text-xs font-semibold transition {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Pending
        </a>
        <a href="{{ route('admin.payment.index', ['status' => 'verified']) }}"
           class="px-5 py-2 rounded-full text-xs font-semibold transition {{ request('status') === 'verified' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Terverifikasi
        </a>
        <a href="{{ route('admin.payment.index', ['status' => 'rejected']) }}"
           class="px-5 py-2 rounded-full text-xs font-semibold transition {{ request('status') === 'rejected' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Ditolak
        </a>
    </div>

    @if($payments->count())
    <div class="space-y-3">
        @foreach($payments as $payment)
        @php
            $payStatusColors = ['pending' => 'bg-amber-100 text-amber-700', 'verified' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
            $payStatusLabels = ['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'];
            $typeLabels = ['dp' => 'DP', 'monthly' => 'Bulanan', 'full' => 'Penuh'];
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
            <div class="flex items-start gap-4">
                {{-- Bukti Bayar --}}
                @if($payment->proof_path)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $payment->proof_path) }}" alt="Bukti"
                         @click="openProofModal('{{ asset('storage/' . $payment->proof_path) }}', '{{ $payment->user->name }}', '{{ $payment->booking->booking_code ?? '-' }}', '{{ number_format($payment->amount, 0, ',', '.') }}', '{{ $payment->created_at->translatedFormat('d F Y H:i') }}')"
                         class="w-20 h-20 rounded-xl object-cover flex-shrink-0 cursor-pointer hover:opacity-80 transition hover:ring-2 hover:ring-[#2F4538] hover:ring-offset-2"
                         data-testid="payment-proof-image" />
                    <div class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                    </div>
                </div>
                @else
                <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0 p-6 text-gray-400">{!! \App\Support\Icons::get('credit-card') !!}</div>
                @endif

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="font-semibold text-gray-900">{{ $payment->user->name }}</p>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $payStatusColors[$payment->status] ?? '' }}">
                            {{ $payStatusLabels[$payment->status] ?? $payment->status }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mb-2">
                        {{ $payment->booking->booking_code ?? '-' }}
                        · {{ $payment->booking->room->name ?? '-' }}
                        · {{ $payment->payment_method === 'cash' ? 'Tunai (Offline)' : strtoupper($payment->payment_method) }}
                        · {{ $typeLabels[$payment->payment_type] ?? $payment->payment_type }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $payment->created_at->translatedFormat('d F Y H:i') }}</p>
                    @if($payment->notes)
                    <p class="text-xs text-gray-500 mt-1 italic">"{{ $payment->notes }}"</p>
                    @endif
                </div>

                {{-- Amount + Actions --}}
                <div class="text-right flex-shrink-0">
                    <p class="text-lg font-bold text-[#2F4538] mb-2">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>

                    @if($payment->status === 'pending')
                    <div class="flex gap-2">
                        {{-- Setujui --}}
                        <form action="{{ route('admin.payment.verify', $payment) }}" method="POST" onsubmit="event.preventDefault(); const f=this; showConfirm('Setujui pembayaran ini? Booking akan dikonfirmasi.', function() { f.submit(); })">
                            @csrf
                            @method('PATCH')
                            <button class="flex items-center gap-1.5 text-xs font-semibold text-white bg-green-500 px-3 py-1.5 rounded-full hover:bg-green-600 transition">
                                <span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('check') !!}</span> Setujui
                            </button>
                        </form>

                        {{-- Tolak --}}
                        <button @click="openReject({{ $payment->id }}, '{{ route('admin.payment.reject', $payment) }}')"
                                class="flex items-center gap-1.5 text-xs font-semibold text-red-500 bg-red-50 px-3 py-1.5 rounded-full hover:bg-red-100 transition">
                            <span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('close') !!}</span> Tolak
                        </button>
                    </div>
                    @endif

                    @if($payment->verified_at)
                    <p class="text-[10px] text-gray-400 mt-1 flex items-center justify-end gap-1">
                        <span class="w-2.5 h-2.5">{!! \App\Support\Icons::get('check') !!}</span>
                        {{ $payment->verified_at->format('d M Y H:i') }}
                        · oleh {{ $payment->verifiedBy?->name ?? 'Admin' }}
                    </p>
                    @endif
                    @if($payment->status === 'rejected' && $payment->notes)
                    <p class="text-[10px] text-red-400 mt-1">Alasan: {{ $payment->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $payments->links() }}
    </div>
    @else
    <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4 p-4 text-gray-500">{!! \App\Support\Icons::get('credit-card') !!}</div>
        <p class="text-gray-400">Belum ada pembayaran masuk.</p>
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
            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#2F4538] to-[#3d5a49] p-6 text-white">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="font-display text-2xl font-bold mb-2">Bukti Pembayaran</h2>
                        <div class="space-y-1 text-sm opacity-90">
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span x-text="proofModal.userName"></span>
                            </p>
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span x-text="proofModal.bookingCode"></span>
                            </p>
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-bold text-yellow-300">Rp <span x-text="proofModal.amount"></span></span>
                            </p>
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span x-text="proofModal.date"></span>
                            </p>
                        </div>
                    </div>
                    <button @click="closeProofModal()" 
                            class="text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition"
                            data-testid="close-proof-modal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Image Preview --}}
            <div class="p-6 bg-gray-50">
                <div class="bg-white rounded-2xl p-4 shadow-inner">
                    <img :src="proofModal.imageUrl" 
                         alt="Bukti Pembayaran" 
                         class="w-full h-auto max-h-[60vh] object-contain rounded-xl"
                         data-testid="proof-modal-image" />
                </div>
            </div>

            {{-- Actions --}}
            <div class="p-6 bg-white border-t border-gray-100 flex gap-3">
                <button @click="downloadProof()" 
                        class="flex-1 flex items-center justify-center gap-2 bg-[#2F4538] text-white font-semibold py-3.5 rounded-xl hover:bg-[#1f2e26] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Buka di Tab Baru
                </button>
                <button @click="closeProofModal()" 
                        class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl hover:bg-gray-200 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
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
