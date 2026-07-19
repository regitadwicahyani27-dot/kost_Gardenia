@extends('layouts.admin')

@section('title', 'Kelola Kamar - Admin Kos Putri Gardenia')

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-display text-2xl font-bold text-gray-900">Kelola Kamar</h1>
        <a href="{{ route('admin.kamar.create') }}" class="bg-[#2F4538] text-white text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-[#26392E] transition">
            + Tambah Kamar
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
    @endif

    @if($rooms->count())
    <div class="space-y-3">
        @foreach($rooms as $room)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">
            {{-- Thumbnail --}}
            <img src="{{ $room->primaryPhoto ? asset('storage/' . $room->primaryPhoto->photo_path) : 'https://via.placeholder.com/80x80/8B9D83/ffffff?text=' . urlencode($room->name) }}"
                 class="w-20 h-20 rounded-xl object-cover flex-shrink-0" />

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <p class="font-semibold text-gray-900 truncate">{{ $room->name }}</p>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $room->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $room->is_available ? 'Tersedia' : 'Terisi' }}
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 uppercase">{{ $room->type }}</span>
                </div>
                <p class="text-xs text-gray-400">Lantai {{ $room->floor }} &middot; Rp {{ number_format($room->price, 0, ',', '.') }}/bulan</p>
                <div class="flex flex-wrap gap-1 mt-1.5">
                    @foreach($room->facilities->take(3) as $fac)
                    <span class="text-[10px] bg-gray-50 text-gray-500 px-2 py-0.5 rounded-full">{{ $fac->name }}</span>
                    @endforeach
                    @if($room->facilities->count() > 3)
                    <span class="text-[10px] bg-gray-50 text-gray-400 px-2 py-0.5 rounded-full">+{{ $room->facilities->count() - 3 }}</span>
                    @endif
                </div>
            </div>

            {{-- Aksi --}}
            <div class="flex gap-2 flex-shrink-0 flex-wrap justify-end">
                {{-- Buka Kamar --}}
                @if(!$room->is_available)
                <form action="{{ route('admin.kamar.toggle', $room) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="text-xs font-semibold text-white bg-green-500 px-3 py-1.5 rounded-full hover:bg-green-600 transition"
                            onclick="event.preventDefault(); const f=this.closest('form'); showConfirm('Buka kamar {{ $room->name }} jadi Tersedia kembali?', function() { f.submit(); })">
                        Buka Kamar
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.kamar.edit', $room) }}"
                   class="text-xs font-semibold text-[#2F4538] border border-[#2F4538] px-3 py-1.5 rounded-full hover:bg-[#2F4538] hover:text-white transition">
                    Edit
                </a>

                <form action="{{ route('admin.kamar.destroy', $room) }}" method="POST"
                      onsubmit="event.preventDefault(); const f=this; showConfirm('Yakin ingin menghapus kamar {{ $room->name }}?', function() { f.submit(); })">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-xs font-semibold text-red-500 border border-red-300 px-3 py-1.5 rounded-full hover:bg-red-500 hover:text-white transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $rooms->links() }}
    </div>
    @else
    <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4 p-4 text-gray-500">{!! \App\Support\Icons::get('bed') !!}</div>
        <p class="text-gray-400 mb-2">Belum ada kamar</p>
        <a href="{{ route('admin.kamar.create') }}" class="text-sm text-[#2F4538] font-semibold hover:underline">Tambah kamar pertama</a>
    </div>
    @endif
</div>
@endsection
