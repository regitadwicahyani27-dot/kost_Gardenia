{{-- Popup ini di-include dari halaman booking/show atau via controller --}}
@if(session('show_receipt') && session('receipt_data'))
@php $r = session('receipt_data'); @endphp
<div id="popup-bukti" class="fixed inset-0 z-[999] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('popup-bukti').classList.add('hidden');document.body.style.overflow=''"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md z-10 max-h-[90vh] overflow-y-auto" style="animation: popupIn 0.28s cubic-bezier(0.34, 1.46, 0.64, 1) forwards">

        {{-- Header gradient sukses --}}
        <div class="relative bg-gradient-to-br from-[#2F4538] to-[#4a7a5a] text-white text-center px-6 pt-8 pb-10 overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-4 -left-4 w-20 h-20 rounded-full bg-white/5"></div>
            <div class="flex items-center justify-center gap-2 mb-5">
                <span class="font-display font-bold text-white/90 tracking-widest text-xs uppercase">Kos Putri Gardenia</span>
            </div>
            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4 ring-4 ring-white/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="font-display text-2xl font-bold">Booking Berhasil!</h2>
            <p class="text-sm text-white/70 mt-1">Silakan upload bukti pembayaran DP</p>
            <div class="mt-5 bg-white/15 rounded-2xl px-6 py-3 inline-block">
                <p class="text-xs text-white/70 mb-0.5">DP yang Harus Dibayar</p>
                <p class="text-3xl font-bold tracking-tight">Rp 250.000</p>
            </div>
        </div>

        {{-- Garis putus-putus --}}
        <div class="relative flex items-center px-0 -mt-1">
            <div class="w-6 h-6 rounded-full bg-gray-100 flex-shrink-0 -ml-3 shadow-inner"></div>
            <div class="flex-1 border-t-2 border-dashed border-gray-200 mx-2"></div>
            <div class="w-6 h-6 rounded-full bg-gray-100 flex-shrink-0 -mr-3 shadow-inner"></div>
        </div>

        {{-- Detail --}}
        <div class="px-6 pt-5 pb-6">
            <div class="flex items-center justify-between mb-5">
                <span class="text-sm font-bold text-gray-900">Rincian Booking</span>
                <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-3 py-1 rounded-full flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span> Menunggu Pembayaran
                </span>
            </div>

            <div class="space-y-0 rounded-2xl overflow-hidden border border-gray-100">
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('user') !!}</span> Nama</div>
                    <span class="text-sm font-semibold text-gray-900">{{ $r['nama'] ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('home') !!}</span> Kamar</div>
                    <span class="text-sm font-semibold text-gray-900">{{ $r['kamar'] ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('calendar') !!}</span> Tanggal Masuk</div>
                    <span class="text-sm font-semibold text-gray-900">{{ $r['tanggal'] ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('tag') !!}</span> Kode Booking</div>
                    <span class="text-xs font-mono font-semibold text-gray-700 bg-gray-100 px-2 py-0.5 rounded">{{ $r['kode'] ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('wallet') !!}</span> Total Sewa</div>
                    <span class="text-sm font-bold text-[#2F4538]">Rp {{ number_format($r['total'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3 flex items-start gap-3">
                <span class="w-5 h-5 mt-0.5 text-blue-600 flex-shrink-0">{!! \App\Support\Icons::get('map-pin') !!}</span>
                <div>
                    <p class="text-xs font-semibold text-blue-800">Langkah Selanjutnya</p>
                    <p class="text-xs text-blue-700 mt-0.5">Bayar DP <strong>Rp 250.000</strong> lalu upload bukti pembayaran di halaman detail booking.</p>
                </div>
            </div>

            <div class="flex gap-3 mt-5">
                <a href="{{ route('user.booking.show', $r['booking_id'] ?? 0) }}"
                   class="flex-1 bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition text-center">
                    Upload Bukti Bayar
                </a>
                <button onclick="document.getElementById('popup-bukti').classList.add('hidden');document.body.style.overflow=''"
                        class="flex-1 border-2 border-gray-200 text-gray-700 font-bold text-sm py-3 rounded-xl hover:bg-gray-50 transition">
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes popupIn { from { opacity: 0; transform: scale(0.88) translateY(16px); } to { opacity: 1; transform: scale(1) translateY(0); } }
</style>
@endif
