<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kos Putri Gardenia')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        @keyframes popupFadeIn {
            from { opacity: 0; transform: scale(0.88) translateY(16px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes overlayFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slideUp 0.5s ease forwards; }
        .stagger-1 { animation-delay: 0.1s; opacity: 0; }
        .stagger-2 { animation-delay: 0.2s; opacity: 0; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #a1a1a1; }
    </style>
    @stack('styles')
</head>
<body class="bg-white text-gray-800 pt-20">

    {{-- Navbar --}}
    <header class="bg-white border-b border-gray-100 fixed w-full top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="w-9 h-9 text-[#2F4538] flex-shrink-0">@include('partials.logo')</span>
                <span class="font-display font-bold text-[#2F4538] tracking-wide">GARDENIA</span>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                <a href="{{ route('home') }}" class="relative pb-1 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[2px] after:w-full after:bg-[#2F4538] after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-200 after:ease-out after:origin-center {{ request()->routeIs('home') ? 'text-[#2F4538] font-bold after:scale-x-100' : 'hover:text-[#2F4538]' }}">Beranda</a>
                <a href="{{ route('rooms.index') }}" class="relative pb-1 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[2px] after:w-full after:bg-[#2F4538] after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-200 after:ease-out after:origin-center {{ request()->routeIs('rooms.*') ? 'text-[#2F4538] font-bold after:scale-x-100' : 'hover:text-[#2F4538]' }}">Kamar</a>
                <a href="{{ route('tentang') }}" class="relative pb-1 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[2px] after:w-full after:bg-[#2F4538] after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-200 after:ease-out after:origin-center {{ request()->routeIs('tentang') ? 'text-[#2F4538] font-bold after:scale-x-100' : 'hover:text-[#2F4538]' }}">Tentang Kami</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <x-user-nav-dropdown />
                @else
                    <button type="button" onclick="bukaPopupAuth('login')" class="bg-[#2F4538] text-white text-sm font-semibold px-5 py-2 rounded-full hover:bg-[#26392E] transition">Masuk</button>
                @endauth

                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden p-2 text-[#2F4538]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white px-6 py-4 flex flex-col gap-3 text-sm font-medium text-gray-600">
            <a href="{{ route('home') }}" class="transition {{ request()->routeIs('home') ? 'text-[#2F4538] font-bold border-l-2 border-[#2F4538] pl-2' : 'hover:text-[#2F4538]' }}">Beranda</a>
            <a href="{{ route('rooms.index') }}" class="transition {{ request()->routeIs('rooms.*') ? 'text-[#2F4538] font-bold border-l-2 border-[#2F4538] pl-2' : 'hover:text-[#2F4538]' }}">Kamar</a>
            <a href="{{ route('tentang') }}" class="transition {{ request()->routeIs('tentang') ? 'text-[#2F4538] font-bold border-l-2 border-[#2F4538] pl-2' : 'hover:text-[#2F4538]' }}">Tentang Kami</a>
            @auth
                <hr class="border-gray-100">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2F4538]">Dashboard Admin</a>
                @endif
                <a href="{{ route('user.profile.edit') }}" class="hover:text-[#2F4538]">Profil</a>
                <a href="{{ route('user.booking.history') }}" class="hover:text-[#2F4538]">Riwayat</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-left">Keluar</button>
                </form>
            @endauth
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div id="flash-success" class="fixed top-4 right-4 z-[9999] bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-2">
            <span class="font-bold">&#10003;</span> {{ session('success') }}
        </div>
        <script>setTimeout(() => document.getElementById('flash-success')?.remove(), 3500);</script>
    @endif
    @if(session('error'))
        <div id="flash-error" class="fixed top-4 right-4 z-[9999] bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-2">
            <span class="font-bold">&#10007;</span> {{ session('error') }}
        </div>
        <script>setTimeout(() => document.getElementById('flash-error')?.remove(), 3500);</script>
    @endif

    {{-- Floating WhatsApp Button --}}
    <a href="https://wa.me/6285956181427" target="_blank" rel="noopener" class="fixed bottom-6 right-6 bg-[#25D366] text-white p-3 rounded-full shadow-lg hover:scale-110 hover:shadow-xl transition-transform z-50 flex items-center justify-center w-14 h-14">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    {{-- Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="text-white" style="background: linear-gradient(to right, #1E2E23, #2F4538, #3A5444)">
        <div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="font-display text-lg font-bold">Pastinya Dekat<br>Semua Jangkauan</h3>
                <p class="text-xs text-white/60 mt-3">&copy; {{ date('Y') }} Kos Putri Gardenia. All rights reserved.</p>
            </div>
            <div>
                <h4 class="font-semibold text-sm mb-3">Info Kontak</h4>
                <p class="text-sm text-white/70 leading-relaxed">Jl. H. M Tohir No 09 RT/RT 01/02<br>Pondok Cina, Kecamatan Beji,<br>Kota Depok Jawa Barat 16424</p>
                <p class="text-sm text-white/70 mt-2">+62 859 5618 1427</p>
            </div>
            <div>
                <h4 class="font-semibold text-sm mb-3">Jam Operasional</h4>
                <p class="text-sm text-white/70">Setiap Hari Jam 08:00 - 20:00</p>
            </div>
            <div>
                <h4 class="font-semibold text-sm mb-3">Explore Gardenia</h4>
                <ul class="text-sm text-white/70 space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                    <li><a href="{{ route('rooms.index') }}" class="hover:text-white">Kamar</a></li>
                    <li><a href="{{ route('tentang') }}" class="hover:text-white">Tentang Kami</a></li>
                </ul>
            </div>
        </div>
    </footer>

    @guest
        @include('partials.auth-modal')
    @endguest

    {{-- ===== POPUP WHATSAPP (Gambar 3) ===== --}}
    <div id="popup-wa" class="hidden fixed inset-0 z-[998] flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/50" style="animation: overlayFadeIn 0.22s ease forwards" onclick="tutupPopupWA()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 z-10 text-center" style="animation: popupFadeIn 0.28s cubic-bezier(0.34, 1.46, 0.64, 1) forwards">
            <button onclick="tutupPopupWA()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </div>
            <h2 class="font-display text-xl font-bold text-gray-900 mb-2">Hubungi Kami</h2>
            <p class="text-sm text-gray-500 mb-6">Kami siap membantu! Chat langsung dengan pemilik kos via WhatsApp.</p>
            <a href="https://wa.me/6285956181427" target="_blank" rel="noopener"
               class="flex items-center justify-center gap-3 bg-green-500 text-white font-semibold text-sm py-3.5 px-6 rounded-xl hover:bg-green-600 transition w-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Chat via WhatsApp
            </a>
            <p class="text-xs text-gray-400 mt-4">+62 859 5618 1427 · Setiap Hari 06:00–00:00</p>
        </div>
    </div>

    <script>
        // WhatsApp Popup
        function bukaPopupWA() {
            document.getElementById('popup-wa').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function tutupPopupWA() {
            document.getElementById('popup-wa').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
    @stack('scripts')
</body>
</html>
