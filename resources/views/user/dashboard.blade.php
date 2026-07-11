@extends('layouts.user')

@section('title', 'Dashboard - Kos Putri Gardenia')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-display text-2xl md:text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Selamat datang kembali, <span class="font-semibold text-[#2F4538]">{{ auth()->user()->name }}</span></p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center p-2.5 text-green-700">{!! \App\Support\Icons::get('home') !!}</div>
                <span class="text-xs text-gray-400 font-medium">Booking Aktif</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $activeBooking ? 1 : 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center p-2.5 text-blue-700">{!! \App\Support\Icons::get('clipboard') !!}</div>
                <span class="text-xs text-gray-400 font-medium">Total Booking</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-lg">⏳</div>
                <span class="text-xs text-gray-400 font-medium">Status Booking</span>
            </div>
            <p class="text-lg font-bold {{ $activeBooking && $activeBooking->status === 'pending' ? 'text-amber-500' : ($activeBooking ? 'text-green-600' : 'text-gray-400') }}">
                {{ $activeBooking ? ucfirst($activeBooking->status) : '-' }}
            </p>
        </div>
    </div>

    {{-- Booking Aktif --}}
    <div class="mb-8">
        <h2 class="font-semibold text-gray-900 mb-4">Booking Aktif</h2>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            @if($activeBooking)
                @php
                    $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700'];
                    $statusLabels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif'];
                @endphp
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[#2F4538]/10 flex items-center justify-center p-3 text-[#2F4538]">{!! \App\Support\Icons::get('home') !!}</div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $activeBooking->room->name }}</p>
                            <p class="text-xs text-gray-400">{{ $activeBooking->booking_code }} · {{ $activeBooking->duration_months }} bulan</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColors[$activeBooking->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $statusLabels[$activeBooking->status] ?? $activeBooking->status }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">
                            <a href="{{ route('user.booking.show', $activeBooking) }}" class="text-[#2F4538] hover:underline">Detail</a>
                        </p>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-400 text-center py-8">
                    Belum ada booking aktif.
                    <a href="{{ route('user.rooms') }}" class="text-[#2F4538] font-semibold hover:underline">Pesan kamar sekarang</a>
                </p>
            @endif
        </div>
    </div>

    {{-- Quick Actions --}}
    <h2 class="font-semibold text-gray-900 mb-4">Menu Cepat</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('user.rooms') }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center hover:shadow-md hover:border-[#2F4538]/30 transition group">
            <div class="w-12 h-12 rounded-xl bg-[#2F4538]/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-[#2F4538]/20 transition p-3 text-[#2F4538]">{!! \App\Support\Icons::get('bed') !!}</div>
            <p class="text-sm font-semibold text-gray-900">Lihat Kamar</p>
        </a>
        <a href="{{ route('user.booking.history') }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center hover:shadow-md hover:border-[#2F4538]/30 transition group">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-100 transition p-3 text-blue-700">{!! \App\Support\Icons::get('clipboard') !!}</div>
            <p class="text-sm font-semibold text-gray-900">Riwayat</p>
        </a>
        <a href="{{ route('user.profile.edit') }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center hover:shadow-md hover:border-[#2F4538]/30 transition group">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-100 transition p-3 text-purple-700">{!! \App\Support\Icons::get('user') !!}</div>
            <p class="text-sm font-semibold text-gray-900">Profil</p>
        </a>
        <a href="https://wa.me/6285956181427" target="_blank" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center hover:shadow-md hover:border-[#2F4538]/30 transition group">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center mx-auto mb-3 group-hover:bg-green-100 transition p-3 text-green-700">{!! \App\Support\Icons::get('chat') !!}</div>
            <p class="text-sm font-semibold text-gray-900">Hubungi Kami</p>
        </a>
    </div>
</div>
@endsection
