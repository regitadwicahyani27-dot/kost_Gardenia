@extends('layouts.app')

@section('title', 'Tipe Kamar - Kos Putri Gardenia')

@section('content')
<div class="bg-[#FAF7F2]">
    {{-- Header --}}
    <section class="text-center pt-12 pb-6 px-6">
        <h1 class="font-display text-2xl md:text-3xl font-bold text-gray-900">Tipe Kamar</h1>
        <p class="text-gray-500 text-sm md:text-base mt-3 max-w-xl mx-auto">
            Temukan kenyamanan maksimal di setiap kamar kami, dirancang khusus
            untuk ketenangan dan produktivitas anda.
        </p>
    </section>

    {{-- Tab: Semua / Lantai 1 / Lantai 2 --}}
    <div class="flex justify-center mb-10 px-6">
        <div class="inline-flex items-center gap-1 bg-gray-100 rounded-full p-1">
            <a href="{{ route('rooms.index') }}"
               class="px-5 py-1.5 rounded-full text-sm font-semibold transition {{ !request('lantai') ? 'bg-[#2F4538] text-white' : 'bg-transparent text-gray-600 hover:bg-gray-200' }}">
                Semua
            </a>
            <a href="{{ route('rooms.index', ['lantai' => 1]) }}"
               class="px-5 py-1.5 rounded-full text-sm font-semibold transition {{ request('lantai') == 1 ? 'bg-[#2F4538] text-white' : 'bg-transparent text-gray-600 hover:bg-gray-200' }}">
                Lantai 1
            </a>
            <a href="{{ route('rooms.index', ['lantai' => 2]) }}"
               class="px-5 py-1.5 rounded-full text-sm font-semibold transition {{ request('lantai') == 2 ? 'bg-[#2F4538] text-white' : 'bg-transparent text-gray-600 hover:bg-gray-200' }}">
                Lantai 2
            </a>
        </div>
    </div>

    {{-- Room Grid --}}
    <main class="max-w-5xl mx-auto px-6 pb-16">
        @if($rooms->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rooms as $room)
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-lg transition group">
                {{-- Foto --}}
                <div class="relative overflow-hidden">
                    <img src="{{ $room->primaryPhoto ? asset('storage/' . $room->primaryPhoto->photo_path) : 'https://via.placeholder.com/600x400/8B9D83/ffffff?text=' . urlencode($room->name) }}"
                         alt="{{ $room->name }}"
                         class="w-full h-48 object-cover group-hover:scale-105 transition duration-300" />
                    @if($room->is_available)
                    <div class="absolute top-3 left-3">
                        <span class="bg-white/95 backdrop-blur text-green-600 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Tersedia</span>
                    </div>
                    @else
                    <div class="absolute top-3 left-3">
                        <span class="bg-white/95 backdrop-blur text-red-600 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Terisi</span>
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-5">
                    <div class="flex items-start justify-between gap-2 mb-0.5">
                        <h3 class="text-base font-semibold text-gray-900">Tipe {{ ucfirst($room->type) }}</h3>
                        <span class="text-sm font-bold text-gray-900 whitespace-nowrap">Rp {{ number_format($room->price, 0, ',', '.') }}/Bulan</span>
                    </div>
                    <p class="text-xs text-gray-400 mb-4">{{ $room->name }}</p>

                    {{-- Fasilitas (standar, sama untuk semua kamar) --}}
                    <div class="space-y-2 mb-5">
                        @foreach(array_slice(\App\Support\RoomFacilities::all(), 0, 4) as $fac)
                        <span class="flex items-center gap-2 text-xs text-gray-600">
                            <span class="w-4 h-4 text-[#2F4538] flex-shrink-0">{!! $fac['icon'] !!}</span>
                            {{ $fac['title'] }}
                        </span>
                        @endforeach
                    </div>

                    {{-- Tombol --}}
                    <a href="{{ route('rooms.show', $room) }}"
                       class="block w-full text-center bg-[#2F4538] text-white text-sm font-semibold py-2.5 rounded-xl hover:bg-[#26392E] transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center text-gray-400 py-12">Belum ada kamar di lantai ini.</p>
        @endif
    </main>
</div>
@endsection
