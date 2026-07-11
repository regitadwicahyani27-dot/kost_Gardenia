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
                <img src="{{ asset('storage/' . $payment->proof_path) }}" alt="Bukti"
                     class="w-20 h-20 rounded-xl object-cover flex-shrink-0 cursor-pointer hover:opacity-80 transition"
                     onclick="window.open('{{ asset('storage/' . $payment->proof_path) }}', '_blank')" />
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
                        · {{ strtoupper($payment->payment_method) }}
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
                        <form action="{{ route('admin.payment.verify', $payment) }}" method="POST" onsubmit="return confirm('Setujui pembayaran ini? Booking akan dikonfirmasi.')">
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

            openReject(paymentId, actionUrl) {
                this.rejectModal.action = actionUrl;
                this.rejectModal.notes = '';
                this.rejectModal.show = true;
            },
        }));
    });
</script>
@endpush
