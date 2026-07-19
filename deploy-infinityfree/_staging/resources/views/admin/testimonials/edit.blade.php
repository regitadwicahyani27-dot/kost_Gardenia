@extends('layouts.admin')

@section('title', 'Edit Testimoni - Admin Kos Putri Gardenia')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.testimonial.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2F4538] transition mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Kelola Testimoni
    </a>

    <h1 class="font-display text-2xl font-bold text-gray-900 mb-1">Edit Testimoni</h1>
    <p class="text-sm text-gray-500 mb-6">Ubah isi testimoni atau atur apakah tampil di beranda.</p>

    <form action="{{ route('admin.testimonial.update', $testimonial) }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Penghuni <span class="text-red-500">*</span></label>
            <input id="name" name="name" type="text" value="{{ old('name', $testimonial->display_name) }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('name') border-red-400 @enderror" />
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="label" class="block text-sm font-semibold text-gray-700 mb-1">Label</label>
            <input id="label" name="label" type="text" value="{{ old('label', $testimonial->label) }}" placeholder="Contoh: Penghuni Aktif, Alumni"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('label') border-red-400 @enderror" />
            @error('label') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="rating" class="block text-sm font-semibold text-gray-700 mb-1">Rating <span class="text-red-500">*</span></label>
            <select id="rating" name="rating"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('rating') border-red-400 @enderror">
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                @endfor
            </select>
            @error('rating') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="content" class="block text-sm font-semibold text-gray-700 mb-1">Isi Testimoni <span class="text-red-500">*</span></label>
            <textarea id="content" name="content" rows="4"
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#2F4538] resize-none @error('content') border-red-400 @enderror">{{ old('content', $testimonial->content) }}</textarea>
            @error('content') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Tampil</label>
            <div class="flex gap-3">
                <label class="flex-1 flex items-center gap-2 border-2 rounded-xl px-4 py-2.5 cursor-pointer transition has-[:checked]:border-[#2F4538] has-[:checked]:bg-[#2F4538]/5 border-gray-200">
                    <input type="radio" name="status" value="approved" class="accent-[#2F4538]" {{ old('status', $testimonial->status) === 'approved' ? 'checked' : '' }} />
                    <span class="text-sm text-gray-700">Tampil di Beranda</span>
                </label>
                <label class="flex-1 flex items-center gap-2 border-2 rounded-xl px-4 py-2.5 cursor-pointer transition has-[:checked]:border-[#2F4538] has-[:checked]:bg-[#2F4538]/5 border-gray-200">
                    <input type="radio" name="status" value="rejected" class="accent-[#2F4538]" {{ old('status', $testimonial->status) === 'rejected' ? 'checked' : '' }} />
                    <span class="text-sm text-gray-700">Sembunyikan</span>
                </label>
            </div>
            @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="flex-1 bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.testimonial.index') }}" class="flex-1 text-center border-2 border-gray-200 text-gray-700 font-bold text-sm py-3 rounded-xl hover:bg-gray-50 transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
