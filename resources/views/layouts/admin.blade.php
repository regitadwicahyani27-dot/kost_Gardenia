<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Kos Putri Gardenia')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#FAF7F2] text-gray-800 min-h-screen">

    {{-- Admin Navbar --}}
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <span class="w-9 h-9 text-[#2F4538] flex-shrink-0">@include('partials.logo')</span>
                <span class="font-display font-bold text-[#2F4538] tracking-wide">GARDENIA</span>
                <span class="text-[10px] font-bold bg-[#2F4538] text-white px-2 py-0.5 rounded-full ml-1">ADMIN</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-[#2F4538] transition hidden md:inline">Lihat Website</a>
                <div class="relative" id="btn-profil">
                    <button onclick="toggleDropdown()" class="flex items-center gap-2 bg-[#2F4538] text-white text-sm font-semibold px-4 py-2 rounded-full hover:bg-[#26392E] transition">
                        <div class="w-6 h-6 rounded-full bg-white/30 flex items-center justify-center text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <span class="max-w-[100px] truncate">{{ Str::before(auth()->user()->name, ' ') }}</span>
                        <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="dropdown-profil" class="hidden absolute right-0 top-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 w-56 z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-xs text-gray-400">Admin</p>
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
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

    {{-- Sidebar + Content --}}
    <div class="max-w-6xl mx-auto px-6 py-8">
        {{-- Admin Nav Tabs --}}
        <nav class="flex gap-2 mb-8 overflow-x-auto pb-2">
            <a href="{{ route('admin.dashboard') }}"
               class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#2F4538] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.kamar.index') }}"
               class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.kamar.*') ? 'bg-[#2F4538] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Kelola Kamar
            </a>
            <a href="{{ route('admin.booking.index') }}"
               class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.booking.*') ? 'bg-[#2F4538] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Booking
            </a>
            <a href="{{ route('admin.payment.index') }}"
               class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.payment.*') ? 'bg-[#2F4538] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Pembayaran
            </a>
        </nav>

        @yield('content')
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById('dropdown-profil').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const profil = document.getElementById('btn-profil');
            const dropdown = document.getElementById('dropdown-profil');
            if (profil && dropdown && !profil.contains(e.target)) dropdown.classList.add('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
