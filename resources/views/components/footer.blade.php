<footer class="bg-[#2F4538] text-white">
    <div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">

        {{-- Kolom 1: Tagline --}}
        <div>
            <h3 class="font-display text-lg font-bold leading-snug">
                Pastinya Dekat<br />Semua Jangkauan
            </h3>
            <p class="text-xs text-white/60 mt-3">
                © {{ date('Y') }} Kos Putri Gardenia. All rights reserved.
            </p>
        </div>

        {{-- Kolom 2: Info Kontak --}}
        <div>
            <h4 class="font-semibold text-sm mb-3">Info Kontak</h4>
            <p class="text-sm text-white/70 leading-relaxed">
                Jl. H. M Tohir No 09 RT/RT 01/02<br />
                Pondok Cina, Kecamatan Beji,<br />
                Kota Depok Jawa Barat 16424
            </p>
            <p class="text-sm text-white/70 mt-2">+62 859 5618 1427</p>
        </div>

        {{-- Kolom 3: Jam Operasional --}}
        <div>
            <h4 class="font-semibold text-sm mb-3">Jam Operasional</h4>
            <p class="text-sm text-white/70">Setiap Hari Jam 08:00 - 20:00</p>
        </div>

        {{-- Kolom 4: Navigasi --}}
        <div>
            <h4 class="font-semibold text-sm mb-3">Explore Gardenia</h4>
            <ul class="text-sm text-white/70 space-y-2">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                </li>
                <li>
                    {{-- TODO: aktifkan setelah route('rooms.index') dibuat --}}
                    {{-- <a href="{{ route('rooms.index') }}" class="hover:text-white transition">Kamar</a> --}}
                    <a href="#" class="hover:text-white transition">Kamar</a>
                </li>
                <li>
                    {{-- TODO: aktifkan setelah route('tentang') dibuat --}}
                    {{-- <a href="{{ route('tentang') }}" class="hover:text-white transition">Tentang Kami</a> --}}
                    <a href="#" class="hover:text-white transition">Tentang Kami</a>
                </li>
                <li>
                    <a href="https://wa.me/6285956181427" target="_blank" rel="noopener"
                       class="hover:text-white transition">
                        Kontak
                    </a>
                </li>
            </ul>
        </div>

    </div>
</footer>