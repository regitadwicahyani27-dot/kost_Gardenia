<div class="relative" x-data="{ open: false }">
    <button @click="open = !open"
            class="flex items-center gap-2 bg-[#2F4538] text-white text-sm font-semibold px-4 py-2 rounded-full hover:bg-[#26392E] transition">
        <x-user-avatar :user="auth()->user()" size="sm" placeholder-bg="bg-white/30" />
        <span class="max-w-[100px] truncate">{{ Str::before(auth()->user()->name, ' ') }}</span>
        <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <div x-show="open"
         x-transition
         @click.outside="open = false"
         class="absolute right-0 top-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 w-56 z-50 overflow-hidden">

        <div class="px-4 py-3 border-b border-gray-100">
            <p class="text-xs text-gray-400">{{ auth()->user()->isAdmin() ? 'Admin' : 'Masuk sebagai' }}</p>
            <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
        </div>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Dashboard Admin
            </a>
        @endif

        <a href="{{ route('user.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Profil
        </a>

        {{-- Admin tidak booking kamar sendiri, Riwayat hanya untuk user --}}
        @unless(auth()->user()->isAdmin())
            <a href="{{ route('user.booking.history') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Riwayat
            </a>
        @endunless

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </div>
</div>
