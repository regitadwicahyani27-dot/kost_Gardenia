@extends('layouts.admin')

@section('title', 'Edit Kamar - Admin Kos Putri Gardenia')

@section('content')
<div>
    <a href="{{ route('admin.kamar.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#2F4538] transition mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Kamar
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100">
            <div>
                <h2 class="font-display text-lg font-bold text-gray-900">Edit Kamar</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $kamar->name }}</p>
            </div>
            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $kamar->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $kamar->is_available ? 'Tersedia' : 'Terisi' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

            {{-- ============================================ --}}
            {{-- KOLOM KIRI: FOTO (form terpisah, BERSARANG) --}}
            {{-- ============================================ --}}
            <div class="px-6 py-5 border-b md:border-b-0 md:border-r border-gray-100">

                {{-- Foto Existing --}}
                <p class="text-xs font-semibold text-gray-700 mb-3">Foto Kamar Saat Ini</p>
                @if($kamar->photos->count())
                <div class="grid grid-cols-3 gap-2 mb-4">
                    @foreach($kamar->photos as $photo)
                    <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200">
                        <img src="{{ asset('storage/' . $photo->photo_path) }}" class="w-full h-full object-cover" />
                        @if($photo->is_primary)
                        <div class="absolute top-1 left-1">
                            <span class="bg-[#2F4538] text-white text-[8px] font-bold px-1.5 py-0.5 rounded">&#9733; Utama</span>
                        </div>
                        @endif
                        {{-- FORM HAPUS FOTO: terpisah, bukan nested --}}
                        <form action="{{ route('admin.foto.delete', $photo) }}" method="POST"
                              class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center"
                              onsubmit="event.preventDefault(); const f=this; showConfirm('Yakin hapus foto ini?', function() { f.submit(); })">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="hidden group-hover:flex bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-full hover:bg-red-600 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-xs text-gray-400 mb-4">Belum ada foto.</p>
                @endif

                {{-- FORM UPLOAD FOTO BARU: terpisah, bukan nested --}}
                <form action="{{ route('admin.kamar.foto', $kamar) }}" method="POST" enctype="multipart/form-data" class="border-t border-gray-100 pt-4">
                    @csrf
                    <p class="text-xs font-semibold text-gray-700 mb-3">Upload Foto Baru</p>
                    
                    <label class="group block w-full rounded-xl border-2 border-dashed border-gray-200 overflow-hidden cursor-pointer relative hover:border-[#2F4538] transition mb-3" style="height:150px">
                        <img id="preview-foto-baru" class="hidden w-full h-full object-cover absolute inset-0" />
                        <div id="placeholder-foto-baru" class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-gray-400 group-hover:text-[#2F4538] transition bg-gray-50 group-hover:bg-[#2F4538]/5">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-xs font-medium">Klik untuk pilih foto</span>
                            <span class="text-[10px] text-gray-400">JPG / PNG / WebP &middot; Maks. 2MB</span>
                        </div>
                        <input type="file" name="photo" accept="image/*" class="hidden" onchange="previewFoto(this, 'preview-foto-baru', 'placeholder-foto-baru')" />
                    </label>

                    <button type="submit" class="w-full bg-[#2F4538] text-white text-xs font-bold px-4 py-3 rounded-xl hover:bg-[#26392E] transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Upload Foto ke Galeri
                    </button>
                    @error('photo') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
                </form>
            </div>

            {{-- ============================================= --}}
            {{-- KOLOM KANAN: FORM EDIT DATA (form terpisah) --}}
            {{-- ============================================= --}}
            <form action="{{ route('admin.kamar.update', $kamar) }}" method="POST" class="px-6 py-5">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Kamar <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $kamar->name) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('name') border-red-400 @enderror" />
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                            <select id="type" name="type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538]">
                                <option value="standard" {{ old('type', $kamar->type) === 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="deluxe" {{ old('type', $kamar->type) === 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                                <option value="vip" {{ old('type', $kamar->type) === 'vip' ? 'selected' : '' }}>VIP</option>
                            </select>
                        </div>
                        <div>
                            <label for="floor" class="block text-sm font-semibold text-gray-700 mb-1">Lantai <span class="text-red-500">*</span></label>
                            <select id="floor" name="floor" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538]">
                                <option value="1" {{ old('floor', $kamar->floor) == 1 ? 'selected' : '' }}>Lantai 1</option>
                                <option value="2" {{ old('floor', $kamar->floor) == 2 ? 'selected' : '' }}>Lantai 2</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-1">Harga per Bulan (Rp) <span class="text-red-500">*</span></label>
                        <input id="price" name="price" type="number" value="{{ old('price', (int) $kamar->price) }}" min="0"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('price') border-red-400 @enderror" />
                        @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2F4538] resize-none">{{ old('description', $kamar->description) }}</textarea>
                    </div>

                    {{-- Fasilitas --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fasilitas Kamar</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($facilities as $fac)
                            <label class="inline-flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-full px-3 py-1.5 cursor-pointer hover:border-[#2F4538] transition has-[:checked]:bg-[#2F4538]/10 has-[:checked]:border-[#2F4538]">
                                <input type="checkbox" name="facilities[]" value="{{ $fac->id }}" class="accent-[#2F4538] w-3.5 h-3.5"
                                       {{ $kamar->facilities->contains($fac->id) ? 'checked' : '' }} />
                                <span class="text-xs text-gray-700">{{ $fac->icon }} {{ $fac->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-2">
                        <input id="is_available" name="is_available" type="checkbox" value="1" class="accent-[#2F4538] w-4 h-4"
                               {{ old('is_available', $kamar->is_available) ? 'checked' : '' }} />
                        <label for="is_available" class="text-sm text-gray-700">Kamar tersedia untuk disewa</label>
                    </div>

                    <button type="submit" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3 rounded-xl hover:bg-[#26392E] transition mt-2">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewFoto(input, previewId, placeholderId) {
    const file = input.files[0];
    if (!file) {
        document.getElementById(previewId).classList.add('hidden');
        document.getElementById(placeholderId).style.display = 'flex';
        return;
    }
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