@extends('layouts.user')

@section('title', 'Detail Pemesanan - Kos Putri Gardenia')

@section('content')
<div class="max-w-lg mx-auto px-6 py-10">
    <a href="{{ route('user.rooms.show', $room) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2F4538] transition mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    <div class="text-center mb-6">
        <h1 class="font-display text-xl font-bold text-gray-900">Detail Pemesanan</h1>
        <p class="text-sm text-gray-500 mt-1">Lengkapi data untuk mengamankan unit pilihan Anda.</p>
    </div>

    {{-- Info Kamar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
        <div class="flex items-start justify-between mb-3">
            <h2 class="text-base font-semibold text-gray-900">{{ $room->name }}</h2>
            @if($room->is_available)
            <span class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full uppercase">Tersedia</span>
            @else
            <span class="text-xs font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full uppercase">Terisi</span>
            @endif
        </div>
        <p class="text-xs text-gray-500 mb-1">Kost Putri Gardenia</p>
        <div class="flex gap-3 text-xs text-gray-400 mt-2">
            <span>Lantai {{ $room->floor }}</span>
            <span>&middot;</span>
            <span>{{ ucfirst($room->type) }}</span>
        </div>
    </div>

    {{-- Form --}}
    <form id="form-booking" action="{{ route('user.booking.store') }}" method="POST">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->id }}">

        {{-- Tanggal Masuk --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <label for="check_in_date" class="block text-sm font-semibold text-gray-900 mb-2">Tanggal Masuk</label>
            <input id="check_in_date" name="check_in_date" type="date" min="{{ date('Y-m-d') }}"
                   value="{{ old('check_in_date') }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('check_in_date') border-red-400 @enderror" />
            @error('check_in_date')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-400 mt-2">*Sewa akan dimulai terhitung sejak tanggal yang Anda pilih</p>
        </div>

        {{-- Rincian Pembayaran --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Rincian Pembayaran</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Harga sewa/bulan</span>
                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Uang Muka/DP (kunci kamar)</span>
                    <span class="text-sm font-semibold text-gray-900">Rp 250.000</span>
                </div>
                <div class="border-t border-gray-100 pt-3 flex justify-between items-center">
                    <span class="text-sm text-gray-500">Sisa Pembayaran di Kos</span>
                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($room->price - 250000, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- DP Info --}}
        <div class="bg-[#2F4538] text-white rounded-2xl p-5 mb-5 flex items-center justify-between">
            <span class="text-sm text-white/80">Pembayaran uang muka / DP sekarang</span>
            <span class="text-xl font-bold">Rp 250.000</span>
        </div>

        {{-- Hidden payment method --}}
        <input type="hidden" name="payment_method" id="payment_method_input" value="qris">

        {{-- Tombol --}}
        <button type="button" onclick="bukaPopupPembayaran()"
                class="w-full bg-[#2F4538] text-white font-bold text-sm py-3.5 rounded-xl hover:bg-[#26392E] transition mb-4">
            Lanjut ke Pembayaran
        </button>

        {{-- Syarat & Ketentuan --}}
        <p class="text-xs text-center text-gray-400 leading-relaxed mb-4">
            Dengan menekan tombol di atas, Anda menyetujui
            <a href="#" onclick="bukaSyaratKetentuan(); return false;" class="text-[#2F4538] font-semibold hover:underline">Syarat &amp; Ketentuan</a>
            sewa di Kos Putri Gardenia
        </p>

        {{-- Jaminan --}}
        <div class="flex items-center justify-center gap-2 text-xs text-gray-400">
            <svg class="w-4 h-4 text-[#2F4538] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span>Jaminan Transaksi Aman — Pembayaran Anda dilindungi oleh sistem enkripsi tingkat tinggi Kos Putri Gardenia.</span>
        </div>
    </form>
</div>

{{-- ===== POPUP: SYARAT & KETENTUAN ===== --}}
<div id="popup-snk" class="hidden fixed inset-0 z-[1000] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/50" style="animation: fadeIn 0.22s ease forwards" onclick="tutupSnK()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 max-h-[85vh] overflow-y-auto" style="animation: popupIn 0.28s cubic-bezier(0.34, 1.46, 0.64, 1) forwards">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 rounded-t-2xl flex items-center justify-between z-10">
            <div>
                <h2 class="font-display text-lg font-bold text-gray-900">Syarat &amp; Ketentuan</h2>
                <p class="text-xs text-gray-400 mt-0.5">Kos Putri Gardenia — Berlaku sejak 2024</p>
            </div>
            <button onclick="tutupSnK()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-6 py-5 space-y-6 text-sm text-gray-600 leading-relaxed">
            <p class="text-gray-500">Harap baca syarat dan ketentuan berikut dengan seksama sebelum melakukan pemesanan. Dengan melanjutkan pembayaran, Anda dianggap telah memahami dan menyetujui seluruh ketentuan di bawah ini.</p>
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">1. Ketentuan Umum Sewa</h3>
                <ul class="space-y-2">
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Kos Putri Gardenia khusus untuk penghuni perempuan (putri). Calon penghuni laki-laki tidak diperkenankan mendaftar.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Masa sewa dihitung per bulan kalender, terhitung sejak tanggal masuk yang tertera pada bukti pemesanan.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Perpanjangan sewa harus dikonfirmasi kepada pemilik kos paling lambat <strong>7 hari sebelum</strong> masa sewa berakhir.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Pemilik berhak menolak perpanjangan sewa apabila penghuni terbukti melanggar peraturan kos.</span></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">2. Pembayaran &amp; Uang Muka</h3>
                <ul class="space-y-2">
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Pemesanan kamar dianggap sah setelah uang muka (DP) sebesar <strong>Rp 250.000</strong> berhasil dibayarkan.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Sisa pembayaran sewa bulan pertama sebesar <strong>Rp 500.000</strong> wajib dilunasi pada hari pertama check-in di lokasi.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Pembayaran sewa bulan berikutnya paling lambat dilakukan pada <strong>tanggal 5</strong> setiap bulannya.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Keterlambatan pembayaran lebih dari 7 hari dapat dikenakan denda atau pemutusan sewa.</span></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">3. Kebijakan Pembatalan</h3>
                <ul class="space-y-2">
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Pembatalan pemesanan yang dilakukan <strong>lebih dari 3 hari</strong> sebelum tanggal masuk akan mendapat pengembalian DP sebesar 50%.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Pembatalan yang dilakukan <strong>kurang dari 3 hari</strong> sebelum tanggal masuk, DP tidak dapat dikembalikan.</span></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">4. Peraturan Penghuni</h3>
                <ul class="space-y-2">
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Tamu laki-laki <strong>dilarang masuk</strong> ke area kamar dan diperbolehkan hanya di area ruang tamu hingga pukul 21.00 WIB.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Dilarang membawa hewan peliharaan ke dalam area kos.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Penghuni wajib menjaga kebersihan kamar dan fasilitas bersama.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Jam malam berlaku pukul <strong>22.00 WIB</strong>. Penghuni diharapkan sudah berada di dalam kos.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Dilarang membuat keributan atau kebisingan yang mengganggu penghuni lain.</span></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">5. Fasilitas &amp; Tanggung Jawab</h3>
                <ul class="space-y-2">
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Kerusakan fasilitas kos akibat kelalaian penghuni menjadi tanggung jawab penghuni yang bersangkutan.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Penghuni wajib melaporkan kerusakan fasilitas bersama kepada pemilik kos sesegera mungkin.</span></li>
                    <li class="flex gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#2F4538] flex-shrink-0 mt-1.5"></span><span>Kos Putri Gardenia tidak bertanggung jawab atas kehilangan barang berharga penghuni akibat kelalaian penghuni itu sendiri.</span></li>
                </ul>
            </div>
            <p class="text-xs text-gray-400 italic border-t border-gray-100 pt-4 mt-2">Syarat &amp; Ketentuan ini dapat berubah sewaktu-waktu. Perubahan akan diinformasikan langsung kepada penghuni aktif.</p>
        </div>
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4 rounded-b-2xl">
            <button onclick="tutupSnK()" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition">Saya Mengerti</button>
        </div>
    </div>
</div>

{{-- ===== POPUP: PILIH METODE PEMBAYARAN (Gambar 1) ===== --}}
<div id="popup-bayar" class="hidden fixed inset-0 z-[999] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/50" style="animation: fadeIn 0.22s ease forwards" onclick="tutupPopupBayar()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10 max-h-[90vh] overflow-y-auto" style="animation: popupIn 0.28s cubic-bezier(0.34, 1.46, 0.64, 1) forwards">
        <button onclick="tutupPopupBayar()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <h2 class="font-display text-xl font-bold text-gray-900 mb-1">Pilih Metode Pembayaran</h2>
        <p class="text-sm text-gray-500 mb-5">Total yang harus dibayar sekarang:</p>
        <div class="bg-[#2F4538] text-white rounded-xl px-5 py-4 mb-6 flex items-center justify-between">
            <span class="text-sm text-white/80">Uang Muka / DP</span>
            <span class="text-xl font-bold">Rp 250.000</span>
        </div>
        <div class="flex gap-2 mb-5">
            <button type="button" onclick="pilihTabMetode('qris')" id="tab-qris" class="flex-1 text-xs font-semibold py-2.5 rounded-full bg-[#2F4538] text-white transition">QRIS</button>
            <button type="button" onclick="pilihTabMetode('bank')" id="tab-bank" class="flex-1 text-xs font-semibold py-2.5 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">Transfer BCA</button>
        </div>
        <div id="konten-qris" class="text-center">
            <div class="bg-white border-2 border-gray-100 rounded-2xl p-5 inline-block mb-3">
                <img src="{{ asset('images/qr-payment.png') }}" alt="QR Code Pembayaran" class="w-48 h-48 object-contain rounded-lg" />
            </div>
            <p class="text-sm font-semibold text-gray-900">Scan QRIS via aplikasi apa saja</p>
            <p class="text-xs text-gray-500 mt-1 mb-4">GoPay, OVO, DANA, ShopeePay, Mobile Banking — semua bisa scan kode ini.</p>
            <div class="flex items-center justify-center gap-3 opacity-80">
                <span class="text-[10px] font-bold border border-gray-200 rounded px-2 py-1">GoPay</span>
                <span class="text-[10px] font-bold border border-gray-200 rounded px-2 py-1">OVO</span>
                <span class="text-[10px] font-bold border border-gray-200 rounded px-2 py-1">DANA</span>
                <span class="text-[10px] font-bold border border-gray-200 rounded px-2 py-1">ShopeePay</span>
            </div>
        </div>
        <div id="konten-bank" class="hidden">
            <div class="border-2 border-gray-100 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs">BCA</div>
                    <div><p class="text-xs text-gray-400">Transfer Bank ke</p><p class="text-sm font-semibold text-gray-900">Bank Central Asia (BCA)</p></div>
                </div>
                <div class="bg-gray-50 rounded-lg px-4 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400">Nomor Rekening</p>
                        <p class="text-base font-bold text-gray-900 tracking-wide">1234567890</p>
                        <p class="text-xs text-gray-500 mt-0.5">a.n. Kos Putri Gardenia</p>
                    </div>
                    <button type="button" onclick="salinRekening()" class="text-xs font-semibold text-[#2F4538] border border-[#2F4538] rounded-full px-3 py-1.5 hover:bg-[#2F4538] hover:text-white transition flex-shrink-0">Salin</button>
                </div>
                <p class="text-xs text-gray-400 mt-3">*Pastikan transfer sesuai nominal agar verifikasi lebih cepat.</p>
            </div>
        </div>
        {{-- Upload Bukti Transfer --}}
        <div class="mt-5 border-t border-gray-100 pt-5">
            <label class="block text-sm font-semibold text-gray-900 mb-2">
                📎 Upload Bukti Transfer <span class="text-red-500">*</span>
            </label>

            {{-- Zona klik --}}
            <div id="proof-upload-zone"
                 onclick="document.getElementById('proof-input').click()"
                 class="cursor-pointer border-2 border-dashed border-gray-200 rounded-xl p-5 text-center hover:border-[#2F4538] hover:bg-green-50 transition">
                <div class="flex flex-col items-center gap-2 text-gray-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs font-medium text-gray-500">Klik untuk pilih foto bukti transfer</p>
                    <p class="text-[10px] text-gray-400">JPG / PNG · Maks. 2MB</p>
                </div>
            </div>

            {{-- Input file (tersembunyi) --}}
            <input type="file" id="proof-input" accept="image/jpeg,image/jpg,image/png"
                   class="hidden" onchange="previewProof(this)">

            {{-- Preview gambar --}}
            <div id="proof-preview" class="hidden mt-3 relative">
                <img id="proof-img" src="" alt="Preview bukti" class="w-full rounded-xl max-h-44 object-contain border border-gray-100">
                <button type="button" onclick="hapusProof()"
                        class="absolute top-2 right-2 w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition shadow">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <p class="text-xs text-gray-400 mt-1 text-center" id="proof-filename"></p>
            </div>

            {{-- Pesan error --}}
            <p id="error-proof" class="text-xs text-red-500 mt-1.5 hidden">Bukti transfer wajib diupload.</p>
        </div>

        <button type="button" onclick="konfirmasiBayar()" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3.5 rounded-xl hover:bg-[#26392E] transition mt-6">Bayar Sekarang</button>
    </div>
</div>

{{-- ===== POPUP: BUKTI PEMBAYARAN BERHASIL (Gambar 2) ===== --}}
<div id="popup-bukti" class="hidden fixed inset-0 z-[1001] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" style="animation: fadeIn 0.22s ease forwards"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md z-10 max-h-[90vh] overflow-y-auto" style="animation: popupIn 0.28s cubic-bezier(0.34, 1.46, 0.64, 1) forwards" id="area-cetak-bukti">

        {{-- Header gradient sukses --}}
        <div class="relative bg-gradient-to-br from-[#2F4538] to-[#4a7a5a] text-white text-center px-6 pt-8 pb-10 overflow-hidden rounded-t-3xl">
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
            <h2 class="font-display text-2xl font-bold">Pembayaran Berhasil!</h2>
            <p class="text-sm text-white/70 mt-1">Uang muka telah kami terima dengan sukses</p>
            <div class="mt-5 bg-white/15 rounded-2xl px-6 py-3 inline-block">
                <p class="text-xs text-white/70 mb-0.5">Total Dibayar</p>
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
                <span class="text-sm font-bold text-gray-900">Rincian Transaksi</span>
                <span class="text-xs font-semibold bg-green-100 text-green-700 px-3 py-1 rounded-full flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Terverifikasi
                </span>
            </div>

            <div class="space-y-0 rounded-2xl overflow-hidden border border-gray-100">
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('user') !!}</span> Nama Pemesan</div>
                    <span id="bukti-nama" class="text-sm font-semibold text-gray-900 text-right max-w-[55%] truncate">-</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('phone') !!}</span> Nomor Telepon</div>
                    <span id="bukti-telepon" class="text-sm font-semibold text-gray-900">-</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('home') !!}</span> Unit Kamar</div>
                    <span id="bukti-kamar" class="text-sm font-semibold text-gray-900 text-right max-w-[55%]">-</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('calendar') !!}</span> Tanggal Masuk</div>
                    <span id="bukti-tanggal-masuk" class="text-sm font-semibold text-gray-900 text-right max-w-[55%]">-</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('credit-card') !!}</span> Metode Bayar</div>
                    <span id="bukti-metode" class="text-sm font-semibold text-[#2F4538]">-</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('tag') !!}</span> ID Transaksi</div>
                    <span id="bukti-id" class="text-xs font-mono font-semibold text-gray-700 bg-gray-100 px-2 py-0.5 rounded">-</span>
                </div>
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('calendar') !!}</span> Tanggal Transaksi</div>
                    <span id="bukti-tanggal-transaksi" class="text-sm font-semibold text-gray-900 text-right max-w-[55%]">-</span>
                </div>
            </div>

            {{-- Sisa Pembayaran --}}
            <div class="mt-4 bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 flex items-start gap-3">
                <span class="w-5 h-5 mt-0.5 text-amber-600 flex-shrink-0">{!! \App\Support\Icons::get('warning') !!}</span>
                <div>
                    <p class="text-xs font-semibold text-amber-800">Sisa Pembayaran di Lokasi</p>
                    <p class="text-xs text-amber-700 mt-0.5">Harap siapkan sisa <strong id="bukti-sisa">-</strong> saat check-in di Kos Putri Gardenia.</p>
                </div>
            </div>

            <p class="text-xs text-gray-400 text-center mt-4 leading-relaxed">Tim kami akan menghubungi Anda melalui nomor telepon terdaftar untuk konfirmasi lebih lanjut.</p>

            {{-- Tombol --}}
            <div class="flex gap-3 mt-5">
                <button onclick="cetakBukti()" class="flex-1 flex items-center justify-center gap-2 border-2 border-[#2F4538] text-[#2F4538] font-bold text-sm py-3 rounded-xl hover:bg-[#2F4538] hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Bukti
                </button>
                <a href="{{ route('user.booking.history') }}" class="flex-1 bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition text-center">Selesai</a>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes popupIn { from { opacity: 0; transform: scale(0.88) translateY(16px); } to { opacity: 1; transform: scale(1) translateY(0); } }
</style>

@push('scripts')
<script>
    let selectedMetode = 'qris';

    function bukaPopupPembayaran() {
        const tanggal = document.getElementById('check_in_date').value;
        if (!tanggal) { showToast('Pilih tanggal masuk terlebih dahulu!', 'warning'); return; }
        document.getElementById('popup-bayar').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function tutupPopupBayar() {
        document.getElementById('popup-bayar').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function bukaSyaratKetentuan() {
        document.getElementById('popup-snk').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function tutupSnK() {
        document.getElementById('popup-snk').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function pilihTabMetode(tab) {
        selectedMetode = tab;
        ['qris', 'bank'].forEach(t => {
            document.getElementById('tab-' + t).className = 'flex-1 text-xs font-semibold py-2.5 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition';
            document.getElementById('konten-' + t).classList.add('hidden');
        });
        document.getElementById('tab-' + tab).className = 'flex-1 text-xs font-semibold py-2.5 rounded-full bg-[#2F4538] text-white transition';
        document.getElementById('konten-' + tab).classList.remove('hidden');
    }

    function pilihEwallet(ewallet) {
        selectedMetode = ewallet;
        document.getElementById('opsi-dana').classList.toggle('border-[#2F4538]', ewallet === 'dana');
        document.getElementById('opsi-dana').classList.toggle('border-gray-100', ewallet !== 'dana');
        document.getElementById('opsi-ovo').classList.toggle('border-[#2F4538]', ewallet === 'ovo');
        document.getElementById('opsi-ovo').classList.toggle('border-gray-100', ewallet !== 'ovo');

        document.getElementById('label-ewallet-phone').textContent = 'Nomor ' + ewallet.toUpperCase() + ' Anda';
        document.getElementById('error-ewallet-phone').classList.add('hidden');
    }

    function salinRekening() {
        navigator.clipboard.writeText('1234567890').then(() => { showToast('Nomor rekening berhasil disalin!', 'success'); });
    }

    function konfirmasiBayar() {
        // Validasi bukti transfer wajib ada
        const proofInput = document.getElementById('proof-input');
        if (!proofInput.files || proofInput.files.length === 0) {
            document.getElementById('error-proof').classList.remove('hidden');
            document.getElementById('proof-upload-zone').scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        document.getElementById('error-proof').classList.add('hidden');

        // Kirim form via AJAX (multipart/form-data — jangan set Content-Type manual)
        const form = document.getElementById('form-booking');
        const formData = new FormData(form);

        let metode = selectedMetode;
        if (metode === 'bank') metode = 'bca';
        formData.set('payment_method', metode);

        // Append file bukti transfer
        formData.set('proof', proofInput.files[0]);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                // Content-Type TIDAK diset manual — browser set multipart boundary otomatis
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reset bukti setelah berhasil
                hapusProof();

                // Tutup popup bayar
                tutupPopupBayar();

                // Isi data bukti
                document.getElementById('bukti-nama').textContent = data.nama;
                document.getElementById('bukti-telepon').textContent = data.telepon;
                document.getElementById('bukti-kamar').textContent = data.kamar + ' · Lantai ' + data.lantai;
                document.getElementById('bukti-tanggal-masuk').textContent = data.tanggal_masuk;
                document.getElementById('bukti-metode').textContent = data.metode.toUpperCase();
                document.getElementById('bukti-id').textContent = data.booking_code;
                document.getElementById('bukti-tanggal-transaksi').textContent = data.tanggal_transaksi;
                document.getElementById('bukti-sisa').textContent = 'Rp ' + Number(data.sisa).toLocaleString('id-ID');

                // Tampilkan popup bukti
                document.getElementById('popup-bukti').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                showToast(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error(error);
            // Fallback: submit form biasa
            form.submit();
        });
    }

    function cetakBukti() {
        window.print();
    }

    function previewProof(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];

        // Validasi ukuran di sisi klien (maks 2MB)
        if (file.size > 2 * 1024 * 1024) {
            showToast('Ukuran file maksimal 2MB.', 'error');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('proof-img').src = e.target.result;
            document.getElementById('proof-filename').textContent = file.name;
            document.getElementById('proof-preview').classList.remove('hidden');
            document.getElementById('proof-upload-zone').classList.add('hidden');
            document.getElementById('error-proof').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    function hapusProof() {
        const input = document.getElementById('proof-input');
        input.value = '';
        document.getElementById('proof-img').src = '';
        document.getElementById('proof-filename').textContent = '';
        document.getElementById('proof-preview').classList.add('hidden');
        document.getElementById('proof-upload-zone').classList.remove('hidden');
    }
</script>
@endpush
@endsection
