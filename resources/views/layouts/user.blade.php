<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Kos Putri Gardenia')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-up { animation: slideUp 0.5s ease forwards; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#FAF7F2] text-gray-800 min-h-screen pt-20">

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
                <x-user-nav-dropdown />
                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden p-2 text-[#2F4538]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white px-6 py-4 flex flex-col gap-3 text-sm font-medium text-gray-600">
            <a href="{{ route('home') }}" class="transition {{ request()->routeIs('home') ? 'text-[#2F4538] font-bold border-l-2 border-[#2F4538] pl-2' : 'hover:text-[#2F4538]' }}">Beranda</a>
            <a href="{{ route('rooms.index') }}" class="transition {{ request()->routeIs('rooms.*') ? 'text-[#2F4538] font-bold border-l-2 border-[#2F4538] pl-2' : 'hover:text-[#2F4538]' }}">Kamar</a>
            <a href="{{ route('tentang') }}" class="transition {{ request()->routeIs('tentang') ? 'text-[#2F4538] font-bold border-l-2 border-[#2F4538] pl-2' : 'hover:text-[#2F4538]' }}">Tentang Kami</a>
            <hr class="border-gray-100">

            <a href="{{ route('user.dashboard') }}" class="hover:text-[#2F4538]">Dashboard</a>
            <a href="{{ route('user.profile.edit') }}" class="hover:text-[#2F4538]">Profil</a>
            <a href="{{ route('user.booking.history') }}" class="hover:text-[#2F4538]">Riwayat</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-left">Keluar</button>
            </form>
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

    @yield('content')

    {{-- Floating WhatsApp Button --}}
    <a href="https://wa.me/6285956181427" target="_blank" rel="noopener" class="fixed bottom-6 right-6 bg-[#25D366] text-white p-3 rounded-full shadow-lg hover:scale-110 hover:shadow-xl transition-transform z-50 flex items-center justify-center w-14 h-14">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    {{-- Toast --}}
    <style>
        @keyframes toastIn { from { opacity:0; transform:translate(-50%,-12px); } to { opacity:1; transform:translate(-50%,0); } }
        @keyframes toastOut { from { opacity:1; transform:translate(-50%,0); } to { opacity:0; transform:translate(-50%,-12px); } }
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
    </script>
    @auth
    @if(!auth()->user()->isAdmin())
    <script>
        (function() {
            let initialPaymentUpdate = null;
            let initialBookingUpdate = null;

            async function checkStatus() {
                try {
                    const res = await fetch("{{ route('user.check-status') }}", {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (!data.logged_in) return;

                    if (initialPaymentUpdate === null) {
                        initialPaymentUpdate = data.last_payment_updated;
                        initialBookingUpdate = data.last_booking_updated;
                        return;
                    }

                    if (data.last_payment_updated > initialPaymentUpdate || data.last_booking_updated > initialBookingUpdate) {
                        initialPaymentUpdate = data.last_payment_updated;
                        initialBookingUpdate = data.last_booking_updated;

                        if (typeof showToast === 'function') {
                            showToast('Status pembayaran / booking Anda telah diperbarui oleh Admin!', 'success');
                        }
                        setTimeout(() => {
                            window.location.reload();
                        }, 1200);
                    }
                } catch(e) {}
            }

            checkStatus();
            setInterval(checkStatus, 10000);
        })();
    </script>
    @endif
    @endauth
    @stack('scripts')
</body>
</html>
