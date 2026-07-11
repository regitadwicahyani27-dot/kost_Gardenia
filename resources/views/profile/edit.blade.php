@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-xl mx-auto px-6 py-10">
    <div class="mb-6">
        <h1 class="font-display text-2xl font-bold text-gray-900">Pengaturan Profil</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui informasi data diri Anda.</p>
    </div>

    @if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PATCH')

        {{-- Foto Profil --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Foto Profil</h2>
            <div class="flex items-center gap-5">
                <div class="relative">
                    <div id="avatar-display">
                        <x-user-avatar :user="auth()->user()" size="lg" class="border-2 border-gray-100" />
                    </div>
                    <img id="avatar-preview" src="" class="w-20 h-20 rounded-full object-cover border-2 border-gray-100 hidden" />
                </div>
                <div>
                    <label class="cursor-pointer inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2 rounded-xl transition">
                        {{ auth()->user()->avatar ? 'Ganti Foto' : 'Upload Foto' }}
                        <input type="file" name="avatar" id="avatar-input" accept="image/*" class="hidden" onchange="previewAvatar(event)" />
                    </label>
                    <p class="text-xs text-gray-400 mt-1.5">JPG atau PNG, maks. 2 MB</p>
                </div>
            </div>
            @error('avatar')
                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Info Diri --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Informasi Diri</h2>

            <div class="space-y-4">
                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full border @error('name') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#2F4538] focus:outline-none">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nomor HP --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                           class="w-full border @error('phone') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#2F4538] focus:outline-none">
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email (readonly) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email (Akun)</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled
                           class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed">
                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                    <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah dari halaman ini.</p>
                </div>
            </div>
        </div>

        {{-- Ganti Password (Collapsible) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{ open: {{ $errors->hasAny(['current_password', 'password']) ? 'true' : 'false' }} }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Ganti Password</h2>
                <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti password.</p>

            <div x-show="open" x-transition class="mt-4 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password Lama</label>
                    <input type="password" name="current_password"
                           class="w-full border @error('current_password') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#2F4538] focus:outline-none">
                    @error('current_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password"
                           class="w-full border @error('password') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#2F4538] focus:outline-none">
                    <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter.</p>
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#2F4538] focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const display = document.getElementById('avatar-display');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            display.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
