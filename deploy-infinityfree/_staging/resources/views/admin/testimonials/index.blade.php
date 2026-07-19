@extends('layouts.admin')

@section('title', 'Kelola Testimoni - Admin Kos Putri Gardenia')

@section('content')
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="font-display text-2xl font-bold text-gray-900">Kelola Testimoni</h1>
                <p class="text-sm text-gray-500 mt-1">Testimoni di sini ditulis langsung oleh admin dan tampil di halaman beranda.</p>
            </div>
            <a href="{{ route('admin.testimonial.create') }}"
               class="inline-flex items-center gap-2 bg-[#2F4538] text-white text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-[#26392E] transition whitespace-nowrap">
                <span class="w-4 h-4">{!! \App\Support\Icons::get('tag') !!}</span>
                Tambah Testimoni
            </a>
        </div>

        @if ($testimonials->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($testimonials as $t)
                    @php
                        $tStatusColors = [
                            'approved' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-gray-200 text-gray-500',
                        ];
                        $tStatusLabels = ['approved' => 'Tampil di Beranda', 'rejected' => 'Disembunyikan'];
                    @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[#2F4538] text-white flex items-center justify-center text-sm font-semibold">
                                    {{ substr($t->display_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $t->display_name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $t->label ?? 'Penghuni' }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full {{ $tStatusColors[$t->status] ?? '' }}">
                                {{ $tStatusLabels[$t->status] ?? $t->status }}
                            </span>
                        </div>

                        {{-- Rating --}}
                        <div class="flex items-center gap-0.5 text-yellow-400 mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="w-3.5 h-3.5">{!! \App\Support\Icons::get($i <= $t->rating ? 'star' : 'star-outline') !!}</span>
                            @endfor
                            <span class="text-xs text-gray-400 ml-1">({{ $t->rating }}/5)</span>
                        </div>

                        {{-- Content --}}
                        <p class="text-sm text-gray-600 mb-4 leading-relaxed">"{{ $t->content }}"</p>

                        {{-- Actions --}}
                        <div class="flex gap-2 border-t border-gray-50 pt-3">
                            <a href="{{ route('admin.testimonial.edit', $t) }}"
                               class="flex-1 text-center text-xs font-semibold text-gray-700 border border-gray-200 px-4 py-2 rounded-full hover:bg-gray-50 transition">
                                Edit
                            </a>
                            <form action="{{ route('admin.testimonial.destroy', $t) }}" method="POST" class="flex-1"
                                onsubmit="return confirm('Yakin hapus testimoni ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold text-red-500 border border-red-200 px-4 py-2 rounded-full hover:bg-red-500 hover:text-white transition">
                                    <span class="w-3.5 h-3.5">{!! \App\Support\Icons::get('close') !!}</span> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $testimonials->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4 p-4 text-gray-500">{!! \App\Support\Icons::get('chat') !!}</div>
                <p class="text-gray-400 mb-3">Belum ada testimoni.</p>
                <a href="{{ route('admin.testimonial.create') }}" class="text-sm text-[#2F4538] font-semibold hover:underline">Tambah testimoni pertama</a>
            </div>
        @endif
    </div>
@endsection
