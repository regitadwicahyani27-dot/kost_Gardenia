<div x-data x-show="$store.bookingModal.open" x-transition.opacity.duration.200ms
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
     style="display: none;"
     @click.self="$store.bookingModal.close()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-y-auto mx-4"
         @click.stop>

        {{-- Header --}}
        <div class="bg-[#2F4538] text-white px-6 py-5 flex items-center justify-between rounded-t-2xl sticky top-0 z-10">
            <div>
                <h2 class="font-display text-xl font-bold">Detail Booking</h2>
                <p class="text-white/70 text-sm mt-1" x-text="$store.bookingModal.booking?.booking_code"></p>
            </div>
            <span x-text="$store.bookingModal.booking?.statusLabel"
                  class="text-xs font-bold px-3 py-1.5 rounded-full bg-white/20">
            </span>
        </div>

        <div class="p-6 space-y-4" x-show="$store.bookingModal.booking?.id">
            {{-- Nama & No HP --}}
            <template x-if="$store.bookingModal.booking?.userName">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">Nama Penyewa</p>
                        <p class="text-sm font-semibold text-gray-900" x-text="$store.bookingModal.booking.userName"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">No HP / WhatsApp</p>
                        <p class="text-sm font-semibold text-gray-900" x-text="$store.bookingModal.booking.userPhone || '-'"></p>
                    </div>
                </div>
            </template>

            {{-- Kamar --}}
            <div class="flex items-center gap-4 bg-gray-50 rounded-xl p-4">
                <template x-if="$store.bookingModal.booking?.roomImage">
                    <img :src="$store.bookingModal.booking.roomImage" class="w-16 h-16 rounded-xl object-cover" />
                </template>
                <template x-if="!$store.bookingModal.booking?.roomImage">
                    <div class="w-16 h-16 rounded-xl bg-[#2F4538]/10 flex items-center justify-center p-4 text-[#2F4538]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                </template>
                <div>
                    <p class="font-semibold text-gray-900" x-text="$store.bookingModal.booking?.roomName"></p>
                    <p class="text-xs text-gray-400" x-text="($store.bookingModal.booking?.roomType || '') + ' · Lantai ' + ($store.bookingModal.booking?.roomFloor || '')"></p>
                </div>
            </div>

            {{-- Detail --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Tanggal Masuk</p>
                    <p class="text-sm font-semibold text-gray-900" x-text="$store.bookingModal.booking?.checkInDate"></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Durasi</p>
                    <p class="text-sm font-semibold text-gray-900" x-text="($store.bookingModal.booking?.duration || '') + ' bulan'"></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">Total Harga</p>
                    <p class="text-sm font-bold text-[#2F4538]">Rp <span x-text="$store.bookingModal.booking?.totalPrice"></span></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 mb-1">DP Dibayar</p>
                    <p class="text-sm font-semibold text-gray-900">Rp <span x-text="$store.bookingModal.booking?.dpAmount"></span></p>
                </div>
            </div>

            {{-- Alasan Pembatalan --}}
            <template x-if="$store.bookingModal.booking?.status === 'cancelled' && $store.bookingModal.booking?.cancelledReason">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-red-800 mb-1">Alasan Pembatalan</p>
                    <p class="text-sm text-red-700" x-text="$store.bookingModal.booking.cancelledReason"></p>
                    <p class="text-xs text-red-500 mt-2">
                        Dibatalkan oleh
                        <span x-text="$store.bookingModal.booking.cancelledBy === 'admin' ? 'Admin' : ($store.bookingModal.booking.cancelledBy === 'user' ? 'Pengguna' : 'Sistem')"></span>
                    </p>
                </div>
            </template>

            {{-- Sisa Pembayaran --}}
            <div x-show="$store.bookingModal.booking?.status !== 'cancelled'" class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-amber-800">Sisa Pembayaran</p>
                <p class="text-lg font-bold text-amber-700">Rp <span x-text="$store.bookingModal.booking?.sisa"></span></p>
                <p class="text-xs text-amber-600 mt-1">Dibayar saat check-in di lokasi</p>
            </div>

            {{-- Riwayat Pembayaran --}}
            <template x-if="$store.bookingModal.booking?.payments?.length">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Riwayat Pembayaran</h3>
                    <div class="space-y-2">
                        <template x-for="pay in $store.bookingModal.booking.payments" :key="pay.id">
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

            {{-- Tutup --}}
            <button @click="$store.bookingModal.close()"
                    class="w-full mt-2 bg-gray-100 text-gray-700 font-semibold text-sm py-3 rounded-xl hover:bg-gray-200 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('bookingModal', {
            open: false,
            booking: {},

            show(data) {
                this.booking = data;
                this.open = true;
            },

            close() {
                this.open = false;
                this.booking = {};
            }
        });
    });
</script>
@endpush
