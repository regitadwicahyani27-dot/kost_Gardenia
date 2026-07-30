@extends('layouts.user')

@section('title', 'Riwayat Booking - Kos Putri Gardenia')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10" x-data="bookingDetail">
    <h1 class="font-display text-2xl font-bold text-gray-900 mb-6">Riwayat Booking</h1>

    @if($bookings->count())
    <div class="space-y-4">
        @foreach($bookings as $booking)
        @php
            $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-gray-100 text-gray-700'];
            $statusLabels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'];
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <img src="{{ $booking->room->primaryPhoto ? asset('storage/' . $booking->room->primaryPhoto->photo_path) : 'https://via.placeholder.com/56x56/8B9D83/ffffff?text=Room' }}"
                     class="w-14 h-14 rounded-xl object-cover" />
                <div>
                    <p class="font-semibold text-gray-900">{{ $booking->room->name }}</p>
                    <p class="text-xs text-gray-400">{{ $booking->booking_code }} · {{ $booking->check_in_date->translatedFormat('d M Y') }}</p>
                    <p class="text-sm font-bold text-[#2F4538] mt-1">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-400">DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$booking->status] ?? $booking->status }}
                </span>
                @if($booking->status === 'cancelled' && $booking->cancelled_reason)
                <p class="text-[10px] text-red-400 mt-1 max-w-[200px] truncate">{{ $booking->cancelled_reason }}</p>
                @endif
                <p class="mt-2">
                    <button @click="open({{ $booking->id }})"
                            class="text-xs text-[#2F4538] font-semibold hover:underline">
                        Lihat Detail
                    </button>
                </p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $bookings->links() }}
    </div>
    @else
    <div class="text-center py-16">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4 p-4 text-gray-500">{!! \App\Support\Icons::get('clipboard') !!}</div>
        <p class="text-gray-400 mb-2">Belum ada riwayat booking</p>
        <a href="{{ route('user.rooms') }}" class="text-sm text-[#2F4538] font-semibold hover:underline">Pesan kamar sekarang</a>
    </div>
    @endif

    {{-- Data JSON untuk modal --}}
    <script>
        window.bookingData = {
            @foreach($bookings as $booking)
            @php
                $totalVerified = $booking->payments->where('status', 'verified')->sum('amount');
                $sisaAmount = max(0, $booking->total_price - $totalVerified);
            @endphp
            {{ $booking->id }}: {
                id: {{ $booking->id }},
                booking_code: '{{ $booking->booking_code }}',
                status: '{{ $booking->status }}',
                statusLabel: '{{ $statusLabels[$booking->status] ?? $booking->status }}',
                cancelledReason: '{{ $booking->cancelled_reason ? addslashes($booking->cancelled_reason) : '' }}',
                cancelledBy: '{{ $booking->cancelled_by }}',
                roomName: '{{ $booking->room->name }}',
                roomType: '{{ strtoupper($booking->room->type) }}',
                roomFloor: '{{ $booking->room->floor }}',
                roomImage: '{{ $booking->room->primaryPhoto ? asset('storage/' . $booking->room->primaryPhoto->photo_path) : '' }}',
                checkInDate: '{{ $booking->check_in_date->translatedFormat('d F Y') }}',
                duration: {{ $booking->duration_months }},
                totalPrice: '{{ number_format($booking->total_price, 0, ',', '.') }}',
                dpAmount: '{{ number_format($booking->dp_amount, 0, ',', '.') }}',
                sisa: '{{ number_format($sisaAmount, 0, ',', '.') }}',
                sisaRaw: {{ $sisaAmount }},
                payments: [
                    @foreach($booking->payments as $payment)
                    {
                        id: {{ $payment->id }},
                        type: '{{ strtoupper($payment->payment_type) }}',
                        method: '{{ strtoupper($payment->payment_method) }}',
                        amount: '{{ number_format($payment->amount, 0, ',', '.') }}',
                        status: '{{ $payment->status }}',
                        statusLabel: '{{ ['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'][$payment->status] ?? $payment->status }}',
                        date: '{{ $payment->created_at->format('d M Y H:i') }}',
                    },
                    @endforeach
                ],
            },
            @endforeach
        };
    </script>

    {{-- MODAL --}}
    <div x-show="show" x-transition.opacity.duration.200ms
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
         style="display: none;"
         @click.self="close()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-y-auto mx-4"
             @click.stop>
            {{-- Header --}}
            <div class="bg-[#2F4538] text-white px-6 py-5 flex items-center justify-between rounded-t-2xl sticky top-0 z-10">
                <div>
                    <h2 class="font-display text-xl font-bold">Detail Booking</h2>
                    <p class="text-white/70 text-sm mt-1" x-text="booking.booking_code"></p>
                </div>
                <span x-text="booking.statusLabel"
                      :class="{
                          'bg-white/20 text-white': true,
                      }"
                      class="text-xs font-bold px-3 py-1.5 rounded-full bg-white/20">
                </span>
            </div>

            <div class="p-6 space-y-4">
                {{-- Info Kamar --}}
                <div class="flex items-center gap-4 bg-gray-50 rounded-xl p-4">
                    <template x-if="booking.roomImage">
                        <img :src="booking.roomImage" class="w-16 h-16 rounded-xl object-cover" />
                    </template>
                    <template x-if="!booking.roomImage">
                        <div class="w-16 h-16 rounded-xl bg-[#2F4538]/10 flex items-center justify-center p-4 text-[#2F4538]">{!! \App\Support\Icons::get('home') !!}</div>
                    </template>
                    <div>
                        <p class="font-semibold text-gray-900" x-text="booking.roomName"></p>
                        <p class="text-xs text-gray-400" x-text="booking.roomType + ' · Lantai ' + booking.roomFloor"></p>
                    </div>
                </div>

                {{-- Detail Grid --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">Tanggal Masuk</p>
                        <p class="text-sm font-semibold text-gray-900" x-text="booking.checkInDate"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">Durasi</p>
                        <p class="text-sm font-semibold text-gray-900" x-text="booking.duration + ' bulan'"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">Total Harga</p>
                        <p class="text-sm font-bold text-[#2F4538]">Rp <span x-text="booking.totalPrice"></span></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">DP Dibayar</p>
                        <p class="text-sm font-semibold text-gray-900">Rp <span x-text="booking.dpAmount"></span></p>
                    </div>
                </div>

                {{-- Alasan Pembatalan --}}
                <template x-if="booking.status === 'cancelled' && booking.cancelledReason">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                        <p class="text-xs font-semibold text-red-800 mb-1">Alasan Pembatalan</p>
                        <p class="text-sm text-red-700" x-text="booking.cancelledReason"></p>
                        <p class="text-xs text-red-500 mt-2">
                            Dibatalkan oleh
                            <span x-text="booking.cancelledBy === 'admin' ? 'Admin' : (booking.cancelledBy === 'user' ? 'Anda' : 'Sistem')"></span>
                        </p>
                    </div>
                </template>

                {{-- Sisa Pembayaran (tampil jika belum lunas) --}}
                <template x-if="['confirmed', 'active'].includes(booking.status) && (booking.sisaRaw > 0 || booking.sisaRaw === undefined)">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <p class="text-xs font-semibold text-amber-800">Sisa Pembayaran</p>
                        <p class="text-lg font-bold text-amber-700">Rp <span x-text="booking.sisa"></span></p>
                        <p class="text-xs text-amber-600 mt-1">Dibayar saat check-in di lokasi</p>
                    </div>
                </template>

                {{-- Status Lunas (tampil jika sudah completed atau sisa 0) --}}
                <template x-if="booking.status === 'completed' || (booking.status !== 'cancelled' && booking.sisaRaw === 0)">
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
                </template>

                {{-- Riwayat Pembayaran --}}
                <template x-if="booking.payments && booking.payments.length">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Riwayat Pembayaran</h3>
                        <div class="space-y-2">
                            <template x-for="pay in booking.payments" :key="pay.id">
                                <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900" x-text="pay.type + ' · ' + pay.method"></p>
                                        <p class="text-xs text-gray-400" x-text="pay.date"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Rp <span x-text="pay.amount"></span></p>
                                        <span x-text="pay.statusLabel"
                                              :class="{
                                                  'bg-amber-100 text-amber-700': pay.status === 'pending',
                                                  'bg-green-100 text-green-700': pay.status === 'verified',
                                                  'bg-red-100 text-red-700': pay.status === 'rejected',
                                              }"
                                              class="text-[10px] font-semibold px-2 py-0.5 rounded-full"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Tombol Tutup --}}
                <button @click="close()"
                        class="w-full mt-2 bg-gray-100 text-gray-700 font-semibold text-sm py-3 rounded-xl hover:bg-gray-200 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingDetail', () => ({
            show: false,
            booking: {},

            open(id) {
                this.booking = window.bookingData[id] || {};
                this.show = true;
            },

            close() {
                this.show = false;
                this.booking = {};
            }
        }));
    });
</script>
@endsection
