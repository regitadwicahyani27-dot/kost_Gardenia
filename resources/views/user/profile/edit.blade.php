@extends('layouts.user')

@section('title', 'Profil Saya - Kos Putri Gardenia')

@section('content')
<div class="max-w-lg mx-auto px-6 py-10">
    <h1 class="font-display text-2xl font-bold text-gray-900 mb-6">Profil Saya</h1>

    <form action="{{ route('user.profile.update') }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        @csrf
        @method('PATCH')

        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('name') border-red-400 @enderror" />
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('email') border-red-400 @enderror" />
            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('phone') border-red-400 @enderror" />
            @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="address" class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
            <textarea id="address" name="address" rows="2"
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] resize-none @error('address') border-red-400 @enderror">{{ old('address', $user->address) }}</textarea>
            @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="birth_date" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir</label>
            <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('birth_date') border-red-400 @enderror" />
            @error('birth_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection