@extends('layouts.user')

@section('title', $room->name . ' - Kos Putri Gardenia')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

        {{-- ===== KOLOM KIRI: Galeri Foto ===== --}}
        <div class="lg:col-span-3">
            @php
                $allPhotos = [];
                if ($room->primaryPhoto) {
                    $allPhotos[] = asset('storage/' . $room->primaryPhoto->photo_path);
                }
                foreach ($room->photos->where('is_primary', false)->take(3) as $photo) {
                    $allPhotos[] = asset('storage/' . $photo->photo_path);
                }
                while (count($allPhotos) < 4) {
                    $allPhotos[] = 'https://via.placeholder.com/600x400/e5e7eb/9ca3af?text=Foto+' . (count($allPhotos) + 1);
                }
            @endphp

            {{-- Foto Utama --}}
            <div class="relative rounded-2xl overflow-hidden cursor-pointer mb-3" onclick="bukaLightbox(0)">
                @if($room->is_available)
                    <span class="absolute top-4 left-4 z-10 bg-white/95 backdrop-blur text-green-600 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Tersedia</span>
                @else
                    <span class="absolute top-4 left-4 z-10 bg-white/95 backdrop-blur text-red-600 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Terisi</span>
                @endif
                <img src="{{ $allPhotos[0] }}" alt="Foto {{ $room->name }}" class="w-full h-[320px] md:h-[420px] object-cover" />
            </div>

            {{-- Thumbnail --}}
            <div class="grid grid-cols-3 gap-3 mb-10 lg:mb-0">
                @for($i = 1; $i < 4; $i++)
                <div class="rounded-xl overflow-hidden cursor-pointer" onclick="bukaLightbox({{ $i }})">
                    <img src="{{ $allPhotos[$i] }}" alt="Foto {{ $room->name }}" class="w-full h-[110px] md:h-[140px] object-cover hover:opacity-90 transition" />
                </div>
                @endfor
            </div>
        </div>

        {{-- ===== KOLOM KANAN: Info & Aksi ===== --}}
        <div class="lg:col-span-2">
            <div class="lg:sticky lg:top-24">
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-xs text-gray-400 mb-4">
                    <a href="{{ route('user.rooms') }}" class="hover:text-[#2F4538] transition">Kamar</a>
                    <span>&gt;</span>
                    <a href="{{ route('user.rooms', ['lantai' => $room->floor]) }}" class="hover:text-[#2F4538] transition">Lantai {{ $room->floor }}</a>
                    <span>&gt;</span>
                    <span class="text-gray-700 font-medium">{{ $room->name }}</span>
                </nav>

                {{-- Nama Kamar --}}
                <h1 class="font-display text-2xl md:text-3xl font-bold text-gray-900 mb-6">{{ $room->name }}</h1>

                {{-- Kartu Harga & Deskripsi --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-xs text-gray-400 mb-1">Mulai Dari</p>
                    <p class="text-2xl font-bold text-[#2F4538] mb-5">
                        Rp {{ number_format($room->price, 0, ',', '.') }}
                        <span class="text-sm font-normal text-gray-400">/Bulan</span>
                    </p>

                    <h2 class="text-base font-semibold text-gray-900 mb-2">Tentang Kamar</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-6">
                        {{ $room->description ?? 'Kamar ini menawarkan suasana yang nyaman dan tenang, menciptakan ruang istirahat yang sempurna untuk Anda.' }}
                    </p>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col gap-3">
                        @if($room->is_available)
                            <a href="{{ route('user.booking.create', $room) }}"
                               class="bg-[#2F4538] text-white font-semibold text-sm py-3 rounded-xl text-center hover:bg-[#26392E] transition">
                                Pesan Sekarang
                            </a>
                        @else
                            <span class="bg-gray-200 text-gray-500 font-semibold text-sm py-3 rounded-xl text-center cursor-not-allowed">
                                Kamar Sudah Terisi
                            </span>
                        @endif
                        <a href="#" onclick="bukaPopupWA(); return false;"
                           class="flex items-center justify-center gap-2 border-2 border-[#2F4538] text-[#2F4538] font-semibold text-sm py-3 rounded-xl text-center hover:bg-[#2F4538] hover:text-white transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.03 2 11c0 2.29 1.02 4.36 2.68 5.95L4 22l5.29-1.53A11.2 11.2 0 0012 21c5.52 0 10-4.03 10-9s-4.48-10-10-10z"/>
                            </svg>
                            Tanya Pemilik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== FASILITAS UTAMA (full width, sama untuk semua kamar) ===== --}}
    <h2 class="text-lg font-semibold text-gray-900 mt-12 mb-4">Fasilitas Utama</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @foreach(\App\Support\RoomFacilities::all() as $fac)
        <div class="flex items-start gap-4 bg-white rounded-xl border border-gray-100 p-4">
            <div class="w-11 h-11 rounded-xl bg-gray-100 flex items-center justify-center p-2.5 text-gray-800 flex-shrink-0">
                {!! $fac['icon'] !!}
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900">{{ $fac['title'] }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ $fac['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== LIGHTBOX PHOTO GALLERY ===== --}}
<div id="lightbox" class="hidden fixed inset-0 z-[1002] flex items-center justify-center">
    <div class="absolute inset-0 bg-black/90" onclick="tutupLightbox()"></div>
    <button onclick="tutupLightbox()" class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <button onclick="prevFoto()" class="absolute left-4 z-10 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <img id="lightbox-img" src="" alt="Foto kamar" class="relative z-10 max-w-[90vw] max-h-[85vh] object-contain rounded-lg shadow-2xl" />
    <button onclick="nextFoto()" class="absolute right-4 z-10 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-10 bg-black/50 text-white text-sm font-medium px-4 py-2 rounded-full">
        <span id="lightbox-counter">1 / 4</span>
    </div>
</div>

@push('scripts')
<script>
    const fotoList = @json($allPhotos);
    let fotoIndex = 0;

    function bukaLightbox(index) {
        fotoIndex = index;
        updateLightbox();
        document.getElementById('lightbox').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function tutupLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function nextFoto() {
        fotoIndex = (fotoIndex + 1) % fotoList.length;
        updateLightbox();
    }

    function prevFoto() {
        fotoIndex = (fotoIndex - 1 + fotoList.length) % fotoList.length;
        updateLightbox();
    }

    function updateLightbox() {
        document.getElementById('lightbox-img').src = fotoList[fotoIndex];
        document.getElementById('lightbox-counter').textContent = (fotoIndex + 1) + ' / ' + fotoList.length;
    }

    document.addEventListener('keydown', function(e) {
        const lb = document.getElementById('lightbox');
        if (lb.classList.contains('hidden')) return;
        if (e.key === 'ArrowRight') nextFoto();
        if (e.key === 'ArrowLeft') prevFoto();
        if (e.key === 'Escape') tutupLightbox();
    });
</script>
@endpush
@endsection
