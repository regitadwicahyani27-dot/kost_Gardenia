<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Kos Putri Gardenia')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                <x-user-nav-dropdown />
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
        </nav>

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
