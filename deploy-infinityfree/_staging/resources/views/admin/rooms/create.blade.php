@extends('layouts.admin')

@section('title', 'Tambah Kamar - Admin Kos Putri Gardenia')

@section('content')
<div>
    <a href="{{ route('admin.kamar.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2F4538] transition mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Kamar
    </a>

    <form action="{{ route('admin.kamar.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100">
                <div>
                    <h2 class="font-display text-lg font-bold text-gray-900">Tambah Kamar Baru</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Isi data dan unggah foto asli kamar Anda</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

                {{-- KOLOM KIRI: FOTO --}}
                <div class="px-6 py-5 border-b md:border-b-0 md:border-r border-gray-100">
                    <p class="text-xs font-semibold text-gray-700 mb-3">
                        Foto Kamar <span class="font-normal text-gray-400">(bisa pilih lebih dari 1)</span>
                    </p>

                    {{-- Foto Utama --}}
                    <div class="mb-4">
                        <p class="text-[11px] font-semibold text-gray-500 mb-1.5 flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded bg-[#2F4538] text-white flex items-center justify-center text-[9px]">&#9733;</span>
                            Foto Utama <span class="text-red-500">*</span>
                        </p>
                        <label class="group block w-full rounded-xl border-2 border-dashed border-gray-200 overflow-hidden cursor-pointer relative hover:border-[#2F4538] transition" style="height:180px">
                            <img id="preview-foto-utama" class="hidden w-full h-full object-cover absolute inset-0" />
                            <div id="placeholder-foto-utama" class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-gray-300 group-hover:text-[#2F4538] transition">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-medium">Klik untuk unggah foto utama</span>
                                <span class="text-[10px] text-gray-300">JPG / PNG / WebP &middot; Maks. 2MB</span>
                            </div>
                            <input type="file" name="photos[]" accept="image/*" class="hidden" onchange="previewFoto(this, 'preview-foto-utama', 'placeholder-foto-utama')" />
                        </label>
                    </div>

                    {{-- Foto Tambahan --}}
                    <div>
                        <p class="text-[11px] font-semibold text-gray-500 mb-1.5">Foto Tambahan (opsional)</p>
                        <div class="grid grid-cols-3 gap-2">
                            @for($i = 1; $i <= 3; $i++)
                            <label class="group block aspect-square rounded-xl border-2 border-dashed border-gray-200 overflow-hidden cursor-pointer relative hover:border-[#2F4538] transition">
                                <img id="preview-foto-{{ $i }}" class="hidden w-full h-full object-cover absolute inset-0" />
                                <div id="placeholder-foto-{{ $i }}" class="absolute inset-0 flex items-center justify-center text-gray-300 group-hover:text-[#2F4538] transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <input type="file" name="photos[]" accept="image/*" class="hidden" onchange="previewFoto(this, 'preview-foto-{{ $i }}', 'placeholder-foto-{{ $i }}')" />
                            </label>
                            @endfor
                        </div>
                    </div>

                    @error('photos.*')
                        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KOLOM KANAN: FORM --}}
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Kamar <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Contoh: Kamar 01"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('name') border-red-400 @enderror" />
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                            <select id="type" name="type"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('type') border-red-400 @enderror">
                                <option value="standard" {{ old('type') === 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="deluxe" {{ old('type') === 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                                <option value="vip" {{ old('type') === 'vip' ? 'selected' : '' }}>VIP</option>
                            </select>
                            @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="floor" class="block text-sm font-semibold text-gray-700 mb-1">Lantai <span class="text-red-500">*</span></label>
                            <select id="floor" name="floor"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('floor') border-red-400 @enderror">
                                <option value="1" {{ old('floor') == 1 ? 'selected' : '' }}>Lantai 1</option>
                                <option value="2" {{ old('floor') == 2 ? 'selected' : '' }}>Lantai 2</option>
                            </select>
                            @error('floor') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-1">Harga per Bulan (Rp) <span class="text-red-500">*</span></label>
                        <input id="price" name="price" type="number" value="{{ old('price', 750000) }}" placeholder="750000" min="0"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('price') border-red-400 @enderror" />
                        @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" placeholder="Deskripsi kamar..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Fasilitas --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fasilitas Kamar</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($facilities as $fac)
                            <label class="inline-flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-full px-3 py-1.5 cursor-pointer hover:border-[#2F4538] transition has-[:checked]:bg-[#2F4538]/10 has-[:checked]:border-[#2F4538]">
                                <input type="checkbox" name="facilities[]" value="{{ $fac->id }}" class="accent-[#2F4538] w-3.5 h-3.5"
                                       {{ in_array($fac->id, old('facilities', [])) ? 'checked' : '' }} />
                                <span class="text-xs text-gray-700">{{ $fac->icon }} {{ $fac->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition mt-2">
                        Simpan Kamar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewFoto(input, previewId, placeholderId) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById(previewId);
        const ph = document.getElementById(placeholderId);
        img.src = e.target.result;
        img.classList.remove('hidden');
        if (ph) ph.style.display = 'none';
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
@endsection