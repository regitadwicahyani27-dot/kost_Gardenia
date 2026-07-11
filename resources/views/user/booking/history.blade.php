@extends('layouts.user')

@section('title', 'Riwayat Booking - Kos Putri Gardenia')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="font-display text-2xl font-bold text-gray-900 mb-6">Riwayat Booking</h1>

    @if($bookings->count())
    <div class="space-y-4">
        @foreach($bookings as $booking)
        @php
            $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-gray-100 text-gray-700'];
            $statusLabels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'];
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center justify-between hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <img src="{{ $booking->room->primaryPhoto ? asset('storage/' . $booking->room->primaryPhoto->photo_path) : 'https://via.placeholder.com/56x56/8B9D83/ffffff?text=Room' }}"
                     class="w-14 h-14 rounded-xl object-cover" />
                <div>
                    <p class="font-semibold text-gray-900">{{ $booking->room->name }}</p>
                    <p class="text-xs text-gray-400">{{ $booking->booking_code }} · {{ $booking->check_in_date->translatedFormat('d M Y') }}</p>
                    <p class="text-sm font-bold text-[#2F4538] mt-1">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$booking->status] ?? $booking->status }}
                </span>
                <p class="mt-2">
                    <a href="{{ route('user.booking.show', $booking) }}" class="text-xs text-[#2F4538] font-semibold hover:underline">Lihat Detail</a>
                </p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $bookings->links() }}
    </div>
    @else
    <div class="text-center py-16">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4 p-4 text-gray-500">{!! \App\Support\Icons::get('clipboard') !!}</div>
        <p class="text-gray-400 mb-2">Belum ada riwayat booking</p>
        <a href="{{ route('user.rooms') }}" class="text-sm text-[#2F4538] font-semibold hover:underline">Pesan kamar sekarang</a>
    </div>
    @endif
</div>
@endsection
