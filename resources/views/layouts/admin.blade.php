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
            <a href="{{ route('admin.testimonial.index') }}"
               class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition {{ request()->routeIs('admin.testimonial.*') ? 'bg-[#2F4538] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Testimoni
            </a>
        </nav>

        @yield('content')
    </div>

    {{-- Toast & Confirm Modal --}}
    <style>
        @keyframes toastIn { from { opacity:0; transform:translate(-50%,-12px); } to { opacity:1; transform:translate(-50%,0); } }
        @keyframes toastOut { from { opacity:1; transform:translate(-50%,0); } to { opacity:0; transform:translate(-50%,-12px); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        @keyframes popupIn { from { opacity:0; transform:scale(0.88) translateY(16px); } to { opacity:1; transform:scale(1) translateY(0); } }
    </style>
    <script>
        function showToast(pesan, tipe) {
            const old = document.getElementById('toast-msg'); if (old) old.remove();
            const warna = { success: ['bg-green-50 border-green-200', 'bg-green-100 text-green-600', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'], error: ['bg-red-50 border-red-200', 'bg-red-100 text-red-600', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'], warning: ['bg-amber-50 border-amber-200', 'bg-amber-100 text-amber-600', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'] };
            const w = warna[tipe] || warna.warning;
            const t = document.createElement('div'); t.id = 'toast-msg';
            t.className = 'fixed top-6 left-1/2 -translate-x-1/2 z-[2000] flex items-center gap-3 '+w[0]+' border shadow-lg rounded-2xl px-5 py-3 max-w-sm w-[calc(100%-2rem)]';
            t.style.animation = 'toastIn 0.3s cubic-bezier(0.34,1.46,0.64,1) forwards';
            t.innerHTML = '<span class="w-8 h-8 rounded-full '+w[1]+' flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">'+w[2]+'</svg></span><span class="text-sm font-medium text-gray-700">'+pesan+'</span>';
            document.body.appendChild(t);
            setTimeout(() => { t.style.animation='toastOut 0.25s ease forwards'; setTimeout(()=>t.remove(),250); }, 3000);
        }

        function showConfirm(pesan, onConfirm) {
            const d = document.createElement('div'); d.id = 'modal-konfirmasi';
            d.className = 'fixed inset-0 z-[2000] flex items-center justify-center px-4';
            d.innerHTML = '<div class="absolute inset-0 bg-black/50" style="animation:fadeIn 0.22s ease forwards" onclick="document.getElementById(\'modal-konfirmasi\').remove()"></div><div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm z-10 p-6" style="animation:popupIn 0.28s cubic-bezier(0.34,1.46,0.64,1) forwards"><div class="text-center"><div class="w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4"><svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div><h3 class="text-base font-bold text-gray-900 mb-2">Konfirmasi</h3><p class="text-sm text-gray-500 mb-6">'+pesan+'</p><div class="flex gap-3"><button onclick="document.getElementById(\'modal-konfirmasi\').remove()" class="flex-1 bg-gray-100 text-gray-600 font-semibold text-sm py-2.5 rounded-xl hover:bg-gray-200 transition">Batal</button><button id="modal-konfirmasi-ya" class="flex-1 bg-[#2F4538] text-white font-semibold text-sm py-2.5 rounded-xl hover:bg-[#26392E] transition">Ya, Lanjut</button></div></div></div>';
            document.body.appendChild(d);
            document.getElementById('modal-konfirmasi-ya').onclick = () => { d.remove(); onConfirm(); };
        }
    </script>
    @stack('scripts')
</body>
</html>
