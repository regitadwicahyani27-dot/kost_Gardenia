@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')
<div class="mb-6">
    <h1 class="font-display text-2xl font-bold text-gray-900">Pengaturan Profil</h1>
    <p class="text-sm text-gray-500 mt-1">Perbarui informasi data diri Anda secara berkala.</p>
</div>

<div class="max-w-xl bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
    <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ auth()->user()->name }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#2F4538] focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
            <input type="tel" name="phone" value="{{ auth()->user()->phone }}" required
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#2F4538] focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email (Akun)</label>
            <input type="email" value="{{ auth()->user()->email }}" disabled
                class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Rumah Sesuai KTP</label>
            <textarea name="address" rows="2" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-[#2F4538] focus:outline-none resize-none">{{ auth()->user()->address }}</textarea>
        </div>

        <div class="border-t border-gray-50 pt-4">
            <button type="submit" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition">
                Simpan Perubahan Data
            </button>
        </div>
    </form>
</div>
@endsection