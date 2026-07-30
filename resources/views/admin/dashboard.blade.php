@extends('layouts.admin')

@section('title', 'Dashboard Admin - Kos Putri Gardenia')

@section('content')
<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-2xl md:text-3xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola Kos Putri Gardenia</p>
        </div>
        <span class="text-xs font-semibold bg-[#2F4538] text-white px-3 py-1.5 rounded-full">Admin</span>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">Total Kamar</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_rooms'] }}</p>
            <p class="text-xs text-gray-400 mt-1"><span class="text-green-600 font-semibold">{{ $stats['available_rooms'] }} tersedia</span> &middot; {{ $stats['occupied_rooms'] }} terisi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">Pendapatan Bulan Ini</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['monthly_income'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['monthly_verified_count'] }} pembayaran terverifikasi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 mb-1">Total Pengguna</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['total_users'] }}</p>
            <p class="text-xs text-gray-400 mt-1">akun terdaftar</p>
        </div>
    </div>

    {{-- Booking Terbaru --}}
    <h2 class="font-semibold text-gray-900 mb-4">Booking Terbaru</h2>
    @if($recentBookings->count())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Kode</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Pengguna</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Kamar</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Uang Muka (DP)</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-mono text-xs font-semibold text-gray-700">{{ $booking->booking_code }}</td>
                        <td class="px-5 py-3 text-gray-900">{{ $booking->user->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $booking->room->name }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColors[$booking->status] ?? '' }}">{{ $statusLabels[$booking->status] ?? $booking->status }}</span>
                        </td>
                        <td class="px-5 py-3 font-semibold text-gray-900">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            <button x-data
                                    @click="$store.bookingModal.show(window._bookingDetailData[{{ $booking->id }}])"
                                    class="text-[#2F4538] text-xs font-semibold hover:underline">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <p class="text-center text-gray-400 py-8 bg-white rounded-2xl border border-gray-100">Belum ada booking masuk.</p>
    @endif

    {{-- Pembayaran Menunggu Verifikasi --}}
    <div class="mt-8 mb-4 flex items-center gap-3">
        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Pembayaran Menunggu Verifikasi
        </h2>
        @if($pendingPayments->count())
        <span class="bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingPayments->count() }}</span>
        @endif
    </div>

    @if($pendingPayments->count())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">USER</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">PAKET</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">HARGA</th>
                        <th class="text-left px-5 py-3 font-semibold text-gray-600">WAKTU PESAN</th>
                        <th class="text-right px-5 py-3 font-semibold text-gray-600">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pendingPayments as $payment)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-5 py-4">
                            <p class="font-bold text-gray-900">{{ $payment->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->user->email }}</p>
                        </td>
                        <td class="px-5 py-4 font-semibold text-gray-700">
                            {{ $payment->booking->room->name ?? '-' }}
                        </td>
                        <td class="px-5 py-4 font-bold text-[#2F4538]">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-xs text-gray-500">
                            {{ $payment->created_at->diffForHumans() }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.payment.verify', $payment) }}" method="POST" onsubmit="event.preventDefault(); const f=this; showConfirm('Verifikasi pembayaran ini?', function() { f.submit(); })">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1.5 bg-[#2F4538] text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-[#26392E] transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.payment.reject', $payment) }}" method="POST" onsubmit="event.preventDefault(); const f=this; showConfirm('Tolak pembayaran ini?', function() { f.submit(); })">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-100 transition border border-red-100 hover:border-red-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <p class="text-center text-gray-400 py-6 bg-white rounded-2xl border border-gray-100 mb-8">Tidak ada pembayaran yang menunggu verifikasi.</p>
    @endif
</div>

{{-- Data JSON untuk modal --}}
@php
$statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700', 'completed' => 'bg-gray-100 text-gray-700'];
$statusLabels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif', 'cancelled' => 'Dibatalkan', 'completed' => 'Selesai'];

$mappedBookings = $recentBookings->keyBy('id')->map(function($booking) use ($statusLabels, $statusColors) {
    return [
        'id' => $booking->id,
        'booking_code' => $booking->booking_code,
        'status' => $booking->status,
        'statusLabel' => $statusLabels[$booking->status] ?? $booking->status,
        'statusBadgeClass' => $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700',
        'createdAt' => $booking->created_at->translatedFormat('d M Y H:i'),
        'userName' => $booking->user->name,
        'userPhone' => $booking->user->phone ?? '',
        'roomName' => $booking->room->name,
        'roomType' => strtoupper($booking->room->type),
        'roomFloor' => $booking->room->floor,
        'roomImage' => $booking->room->primaryPhoto ? asset('storage/' . $booking->room->primaryPhoto->photo_path) : '',
        'checkInDate' => $booking->check_in_date->translatedFormat('d F Y'),
        'duration' => $booking->duration_months,
        'totalPrice' => number_format($booking->total_price, 0, ',', '.'),
        'dpAmount' => number_format($booking->dp_amount, 0, ',', '.'),
        'sisa' => number_format($booking->total_price - $booking->dp_amount, 0, ',', '.'),
        'cancelledReason' => $booking->cancelled_reason,
        'cancelledBy' => $booking->cancelled_by,
        'showUrl' => route('admin.booking.show', $booking),
        'payments' => $booking->payments->map(function($payment) {
            return [
                'id' => $payment->id,
                'type' => ['dp' => 'Uang Muka (DP)', 'monthly' => 'Bulanan', 'full' => 'Penuh'][$payment->payment_type] ?? $payment->payment_type,
                'method' => $payment->payment_method === 'cash' ? 'CASH' : strtoupper($payment->payment_method),
                'amount' => number_format($payment->amount, 0, ',', '.'),
                'status' => $payment->status,
                'statusLabel' => ['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'][$payment->status] ?? $payment->status,
                'date' => $payment->created_at->format('d M Y H:i'),
                'proofUrl' => $payment->proof_path ? asset('storage/' . $payment->proof_path) : '',
            ];
        })->toArray()
    ];
});
@endphp
<script>
    window._bookingDetailData = @json($mappedBookings);
</script>

{{-- Modal --}}
@include('components.booking-detail-modal')
@endsection
