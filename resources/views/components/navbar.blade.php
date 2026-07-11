<header class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Gardenia" class="w-7 h-7"
                 onerror="this.src='https://via.placeholder.com/28x28.png?text=L'" />
            <span class="font-display font-bold text-[#2F4538] tracking-wide">GARDENIA</span>
        </a>

        {{-- Menu Navigasi Desktop --}}
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
            <a href="{{ route('home') }}"
               class="hover:text-[#2F4538] {{ request()->routeIs('home') ? 'text-[#2F4538] border-b-2 border-[#2F4538] pb-1' : '' }}">
                Beranda
            </a>

            {{-- TODO: aktifkan setelah RoomController dibuat --}}
            {{-- <a href="{{ route('rooms.index') }}" --}}
            <a href="#"
               class="hover:text-[#2F4538] {{ request()->routeIs('rooms.*') ? 'text-[#2F4538] border-b-2 border-[#2F4538] pb-1' : '' }}">
                Kamar
            </a>

            {{-- TODO: aktifkan setelah route 'tentang' dibuat --}}
            {{-- <a href="{{ route('tentang') }}" --}}
            <a href="#"
               class="hover:text-[#2F4538] {{ request()->routeIs('tentang') ? 'text-[#2F4538] border-b-2 border-[#2F4538] pb-1' : '' }}">
                Tentang Kami
            </a>

            {{-- Kontak → link langsung WhatsApp --}}
            <a href="https://wa.me/6285956181427" target="_blank" rel="noopener"
               class="hover:text-[#2F4538]">
                Kontak
            </a>
        </nav>

        {{-- Kanan: Tombol Auth --}}
        <div class="flex items-center gap-3 relative">

            @guest
                {{-- Belum login: tampil tombol Login & Register --}}
                <a href="{{ route('login') }}"
                   class="text-sm font-semibold text-gray-600 hover:text-[#2F4538] transition hidden sm:inline">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="bg-[#2F4538] text-white text-sm font-semibold px-5 py-2 rounded-full hover:bg-[#26392E] transition">
                    Daftar
                </a>
            @endguest

            @auth
                {{-- Sudah login: tampil avatar + nama + dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 bg-[#2F4538] text-white text-sm font-semibold px-4 py-2 rounded-full hover:bg-[#26392E] transition">
                        {{-- Inisial avatar --}}
                        <div class="w-6 h-6 rounded-full bg-white/30 flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="max-w-[100px] truncate">
                            {{ explode(' ', auth()->user()->name)[0] }}
                        </span>
                        <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open"
                         x-transition
                         @click.outside="open = false"
                         class="absolute right-0 top-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 w-56 z-50 overflow-hidden">

                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-xs text-gray-400">Masuk sebagai</p>
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        </div>

                        {{-- Link ke Dashboard (user atau admin berdasarkan role) --}}
                        @if(auth()->user()->role === 'admin')
                            {{-- TODO: aktifkan setelah route admin.dashboard dibuat --}}
                            {{-- <a href="{{ route('admin.dashboard') }}" --}}
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                                </svg>
                                Dashboard Admin
                            </a>
                        @else
                            {{-- TODO: aktifkan setelah route user.dashboard dibuat --}}
                            {{-- <a href="{{ route('user.dashboard') }}" --}}
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Dashboard Saya
                            </a>
                        @endif

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            {{-- Hamburger Mobile --}}
            <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                    class="md:hidden p-2 text-[#2F4538]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu"
         class="hidden md:hidden border-t border-gray-100 bg-white px-6 py-4 flex flex-col gap-3 text-sm font-medium text-gray-600">

        <a href="{{ route('home') }}" class="hover:text-[#2F4538]">Beranda</a>

        {{-- TODO: aktifkan setelah route dibuat --}}
        <a href="#" class="hover:text-[#2F4538]">Kamar</a>
        <a href="#" class="hover:text-[#2F4538]">Tentang Kami</a>

        <a href="https://wa.me/6285956181427" target="_blank" rel="noopener"
           class="hover:text-[#2F4538]">Kontak</a>

        @guest
            <hr class="border-gray-100">
            <a href="{{ route('login') }}" class="hover:text-[#2F4538]">Masuk</a>
            <a href="{{ route('register') }}" class="hover:text-[#2F4538]">Daftar</a>
        @endguest

        @auth
            <hr class="border-gray-100">
            <a href="{{ route('dashboard') }}" class="hover:text-[#2F4538]">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Keluar</button>
            </form>
        @endauth
    </div>
</header>

{{-- Alpine.js untuk dropdown (CDN) --}}
{{-- Jika Alpine.js sudah di-include di layout, hapus baris ini --}}
@once
    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
@endonce