@extends('layouts.app')

@section('title', 'Tentang Kami - Kos Putri Gardenia')

@section('content')
<div class="bg-white">

    {{-- Kisah Kami --}}
    <section class="max-w-3xl mx-auto px-6 pt-12 pb-10 text-center">
        <h1 class="font-display text-2xl md:text-3xl font-bold text-gray-900 tracking-wide">KISAH KAMI</h1>
        <div class="text-left mt-6 space-y-4 text-sm md:text-base text-gray-600 leading-relaxed">
            <p>
                Berdiri sejak 2015, Kos Putri Gardenia hadir menawarkan hunian yang asri, aman, dan nyaman.
                Kami menjadi tempat beristirahat yang sempurna bagi para mahasiswi di tengah hiruk-pikuk dan
                kesibukan Kota Depok.
            </p>
            <p>
                Kami menyediakan 30 kamar dengan standar kualitas yang sama. Hanya mulai dari Rp 750.000/bulan,
                setiap kamar sudah dilengkapi furnitur modern, Wi-Fi, listrik, air bersih, dan kamar mandi dalam.
                Tersedia juga satu kamar mandi luar untuk kenyamanan ekstra.
            </p>
            <p>
                Fasilitas bersama kami dirancang untuk kenyamanan bersama, mencakup dapur bersama yang luas dan
                peralatannya, ruang tamu, area jemuran di lt 2 dan area parkir motor yang aman.
            </p>
        </div>
    </section>

    {{-- Fasilitas Lengkap --}}
    <section class="max-w-5xl mx-auto px-6 pb-16">
        <h2 class="font-display text-xl md:text-2xl font-bold text-gray-900 text-center mb-10">
            Fasilitas Lengkap &amp; Lingkungan Hunian<br>Gardenia
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php
            $galeri = [
                ['img' => 'Ruang+Tamu+2', 'icon' => 'sofa', 'title' => 'Ruang Tamu 2', 'desc' => 'Cocok untuk mengerjakan tugas dan kumpul bersama, dengan pencahayaan lebih terang dan lebih sejuk.', 'colspan' => false, 'fallback' => 'https://via.placeholder.com/600x420/8B9D83/ffffff?text=Ruang+Tamu+2'],
                ['img' => 'Ruang+Tamu+3', 'icon' => 'sofa', 'title' => 'Ruang Tamu 3', 'desc' => 'Spot santai yang lebih tertutup dan tenang untuk bersantai.', 'colspan' => false, 'fallback' => 'https://via.placeholder.com/600x420/8B9D83/ffffff?text=Ruang+Tamu+3'],
                ['img' => 'Lorong+Lantai+1', 'icon' => 'door', 'title' => 'Lorong Lantai 1', 'desc' => 'Akses lorong dengan pencahayaan maksimal dan sirkulasi udara yang baik.', 'colspan' => false, 'fallback' => 'https://via.placeholder.com/600x420/8B9D83/ffffff?text=Lorong+Lantai+1'],
                ['img' => 'Lorong+Lantai+2', 'icon' => 'wifi', 'title' => 'Lorong Lantai 2', 'desc' => 'Lorong atas dengan akses kamar yang luas disertai sirkulasi udara dan pencahayaan yang maksimal.', 'colspan' => false, 'fallback' => 'https://via.placeholder.com/600x420/8B9D83/ffffff?text=Lorong+Lantai+2'],
                ['img' => 'Meja+Makan+Bersama', 'icon' => 'utensils', 'title' => 'Meja Makan Bersama', 'desc' => 'Spot makan bersama yang nyaman persis di area dapur.', 'colspan' => false, 'fallback' => 'https://via.placeholder.com/600x420/8B9D83/ffffff?text=Meja+Makan+Bersama'],
                ['img' => 'Dapur+Bersama+Lt+2', 'icon' => 'pan', 'title' => 'Dapur Bersama Lt 2', 'desc' => 'Area dapur bersama di lantai 2 lengkap dengan peralatan.', 'colspan' => false, 'fallback' => 'https://via.placeholder.com/600x420/8B9D83/ffffff?text=Dapur+Bersama+Lt+2'],
                ['img' => 'Teras+Luar', 'icon' => 'plant', 'title' => 'Teras Luar', 'desc' => 'Area semi-terbuka dengan sirkulasi udara terbuka yang sejuk.', 'colspan' => true, 'fallback' => 'https://via.placeholder.com/1200x500/8B9D83/ffffff?text=Teras+Luar'],
            ];
            @endphp
            @foreach($galeri as $g)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition {{ $g['colspan'] ? 'md:col-span-2' : '' }}">
                <img src="{{ asset('images/fasilitas/' . Str::slug($g['title']) . '.jpg') }}"
                     alt="{{ $g['title'] }}"
                     class="w-full h-72 object-cover"
                     onerror="this.src='{{ $g['fallback'] }}'" />
                <div class="p-5">
                    <h3 class="flex items-center gap-2 font-semibold text-gray-900">
                        <span class="w-5 h-5 text-[#2F4538]">{!! \App\Support\Icons::get($g['icon']) !!}</span> {{ $g['title'] }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $g['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Lokasi Strategis --}}
    <section class="max-w-5xl mx-auto px-6 pb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center hover:shadow-md transition">
                <div class="w-14 h-14 rounded-full bg-[#2F4538] text-white flex items-center justify-center mx-auto mb-4 p-3.5">{!! \App\Support\Icons::get('train') !!}</div>
                <h3 class="font-semibold text-gray-900 mb-2">Transportasi Umum</h3>
                <p class="text-sm text-gray-500">Hanya 700 m dari Stasiun Pondok Cina, memudahkan akses transportasi umum ke berbagai area.</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center hover:shadow-md transition">
                <div class="w-14 h-14 rounded-full bg-[#2F4538] text-white flex items-center justify-center mx-auto mb-4 p-3.5">{!! \App\Support\Icons::get('building') !!}</div>
                <h3 class="font-semibold text-gray-900 mb-2">Universitas</h3>
                <p class="text-sm text-gray-500">Berlokasi sangat strategis, hanya 280 m dari Kampus F8 Gunadarma, 140 m dari F5 Gunadarma dan 500 m dari Kampus D Gunadarma.</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center hover:shadow-md transition">
                <div class="w-14 h-14 rounded-full bg-[#2F4538] text-white flex items-center justify-center mx-auto mb-4 p-3.5">{!! \App\Support\Icons::get('fork-knife') !!}</div>
                <h3 class="font-semibold text-gray-900 mb-2">Pusat Kuliner</h3>
                <p class="text-sm text-gray-500">Dikelilingi deretan tempat populer favorit mahasiswa yang nyaman untuk Work From Cafe (WFC). Hanya 5-10 Menit jalan kaki.</p>
            </div>
        </div>
    </section>

</div>
@endsection
