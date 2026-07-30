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

            {{-- Sisa Pembayaran (hanya tampil jika belum lunas) --}}
            <template x-if="['confirmed', 'active'].includes($store.bookingModal.booking?.status)">
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-amber-800">Sisa Pembayaran</p>
                    <p class="text-lg font-bold text-amber-700">Rp <span x-text="$store.bookingModal.booking?.sisa"></span></p>
                    <p class="text-xs text-amber-600 mt-1">Dibayar saat check-in di lokasi</p>
                </div>
            </template>

            {{-- Status Lunas (tampil jika sudah completed) --}}
            <template x-if="$store.bookingModal.booking?.status === 'completed'">
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

            {{-- Form Catat Pembayaran Offline (hanya untuk admin) --}}
            <template x-if="['confirmed', 'active'].includes($store.bookingModal.booking?.status)">
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <div x-data="{ showForm: false }">
                        <button @click="showForm = !showForm"
                                class="w-full flex items-center justify-between text-left">
                            <div>
                                <p class="text-sm font-semibold text-green-800">Catat Pembayaran Offline</p>
                                <p class="text-xs text-green-600 mt-0.5">Untuk pelunasan tunai di lokasi</p>
                            </div>
                            <svg :class="{'rotate-180': showForm}" class="w-5 h-5 text-green-700 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <form x-show="showForm" x-transition
                              :action="'{{ url('admin/booking') }}/' + $store.bookingModal.booking?.id + '/manual-payment'"
                              method="POST"
                              class="mt-4 space-y-3"
                              onsubmit="event.preventDefault(); const f=this; showConfirm('Yakin ingin mencatat pembayaran offline ini? Booking akan otomatis berubah status menjadi selesai.', function() { f.submit(); })">
                            @csrf
                            
                            <div>
                                <label class="block text-xs font-semibold text-green-800 mb-1">
                                    Nominal Pembayaran
                                </label>
                                <input type="number"
                                       name="amount"
                                       :value="$store.bookingModal.booking?.sisa?.replace(/[^0-9]/g, '')"
                                       required
                                       min="0"
                                       step="1"
                                       class="w-full px-3 py-2 border border-green-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                                       placeholder="Masukkan nominal">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-green-800 mb-1">
                                    Catatan (opsional)
                                </label>
                                <textarea name="notes"
                                          rows="2"
                                          class="w-full px-3 py-2 border border-green-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                                          placeholder="Contoh: Pelunasan tunai saat check-in"></textarea>
                            </div>

                            <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold text-sm py-2.5 rounded-lg transition">
                                💰 Simpan Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </template>

            {{-- Riwayat Pembayaran --}}
            <template x-if="$store.bookingModal.booking?.payments?.length">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Riwayat Pembayaran</h3>
                    <div class="space-y-3">
                        <template x-for="pay in $store.bookingModal.booking.payments" :key="pay.id">
                            <div class="flex items-start gap-3 bg-gray-50 rounded-xl p-3 hover:bg-gray-100 transition">
                                {{-- Thumbnail / Ikon Metode Pembayaran --}}
                                <template x-if="pay.method === 'CASH'">
                                    {{-- Kotak Hijau Pastel untuk Tunai/Cash --}}
                                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center flex-shrink-0 shadow-sm border border-green-200">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                </template>
                                <template x-if="pay.method !== 'CASH'">
                                    {{-- Foto Bukti Transfer untuk Metode Online --}}
                                    <template x-if="pay.proofUrl">
                                        <img :src="pay.proofUrl" 
                                             alt="Bukti Transfer" 
                                             class="w-16 h-16 rounded-xl object-cover flex-shrink-0 cursor-pointer hover:opacity-80 transition border border-gray-200 hover:border-[#2F4538]"
                                             @click="window.open(pay.proofUrl, '_blank')" />
                                    </template>
                                    <template x-if="!pay.proofUrl">
                                        <div class="w-16 h-16 rounded-xl bg-gray-200 flex items-center justify-center flex-shrink-0 border border-gray-300">
                                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                    </template>
                                </template>

                                {{-- Info Pembayaran --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                <span x-text="pay.type"></span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                <template x-if="pay.method === 'CASH'">
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Tunai (Offline)
                                                    </span>
                                                </template>
                                                <template x-if="pay.method !== 'CASH'">
                                                    <span x-text="pay.method"></span>
                                                </template>
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1" x-text="pay.date"></p>
                                        </div>
                                        <span x-text="pay.statusLabel"
                                              :class="{
                                                  'bg-amber-100 text-amber-700': pay.status === 'pending',
                                                  'bg-green-100 text-green-700': pay.status === 'verified',
                                                  'bg-red-100 text-red-700': pay.status === 'rejected',
                                              }"
                                              class="text-[10px] font-semibold px-2 py-0.5 rounded-full whitespace-nowrap"></span>
                                    </div>
                                    <p class="text-base font-bold text-[#2F4538]">
                                        Rp <span x-text="pay.amount"></span>
                                    </p>
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
