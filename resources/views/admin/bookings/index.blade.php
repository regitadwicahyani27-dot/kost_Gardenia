@extends('layouts.admin')

@section('title', 'Kelola Booking - Admin Kos Putri Gardenia')

@section('content')
<div>
    <h1 class="font-display text-2xl font-bold text-gray-900 mb-6">Kelola Booking</h1>

    @if($bookings->count())
    <div class="space-y-3">
        @foreach($bookings as $booking)
        @php
            $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-gray-100 text-gray-700'];
            $statusLabels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'];
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <p class="font-semibold text-gray-900">{{ $booking->user->name }}</p>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColors[$booking->status] ?? '' }}">
                            {{ $statusLabels[$booking->status] ?? $booking->status }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400">
                        <span class="font-mono font-semibold">{{ $booking->booking_code }}</span>
                        · {{ $booking->room->name }}
                        · {{ $booking->duration_months }} bulan
                        · Masuk: {{ $booking->check_in_date->translatedFormat('d M Y') }}
                    </p>
                </div>
                <p class="text-lg font-bold text-[#2F4538]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
            </div>

            <div class="flex items-center justify-between border-t border-gray-50 pt-3">
                <p class="text-xs text-gray-400">Dibuat: {{ $booking->created_at->translatedFormat('d M Y H:i') }}</p>
                <div class="flex items-center gap-2">
                    {{-- Quick Status Actions --}}
                    @if($booking->status === 'pending')
                    <form action="{{ route('admin.booking.status', $booking) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button class="text-xs font-semibold text-white bg-blue-500 px-3 py-1.5 rounded-full hover:bg-blue-600 transition">Konfirmasi</button>
                    </form>
                    <form action="{{ route('admin.booking.status', $booking) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button class="text-xs font-semibold text-white bg-red-500 px-3 py-1.5 rounded-full hover:bg-red-600 transition">Tolak</button>
                    </form>
                    @endif

                    @if($booking->status === 'confirmed')
                    <form action="{{ route('admin.booking.status', $booking) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button class="text-xs font-semibold text-white bg-green-500 px-3 py-1.5 rounded-full hover:bg-green-600 transition">Aktifkan</button>
                    </form>
                    @endif

                    @if($booking->status === 'active')
                    <form action="{{ route('admin.booking.status', $booking) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button class="text-xs font-semibold text-white bg-gray-500 px-3 py-1.5 rounded-full hover:bg-gray-600 transition">Selesai</button>
                    </form>
                    @endif

                    <a href="{{ route('admin.booking.show', $booking) }}"
                       class="text-xs font-semibold text-[#2F4538] border border-[#2F4538] px-3 py-1.5 rounded-full hover:bg-[#2F4538] hover:text-white transition">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
    @else
    <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4 p-4 text-gray-500">{!! \App\Support\Icons::get('clipboard') !!}</div>
        <p class="text-gray-400">Belum ada booking masuk.</p>
    </div>
    @endif
</div>
@endsection
