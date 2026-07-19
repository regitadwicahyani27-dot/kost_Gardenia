@extends('layouts.app')

@section('title', 'Kos Putri Gardenia - Hunian Asri Khusus Putri')

@section('content')
<div class="bg-[#FAF7F2]">

    {{-- Hero Section --}}
    <section class="relative">
        <div class="relative h-[620px] w-full overflow-hidden">
            <img src="{{ asset('images/hero/bangunan.jpg') }}"
                 alt="Foto Bangunan Kos Gardenia"
                 class="absolute inset-0 w-full h-full object-cover"
                 onerror="this.src='https://via.placeholder.com/1600x900/2F4538/ffffff?text=Foto+Bangunan+Kos+Gardenia'" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 px-6 pb-16 text-center">
                <h1 class="font-display text-3xl md:text-5xl font-bold text-white leading-tight animate-slide-up">
                    Hunian Asri, Aman dan Nyaman<br>Khusus Putri
                </h1>
                <p class="text-white/90 text-sm md:text-base max-w-xl mx-auto mt-4 animate-slide-up stagger-1">
                    Nikmati suasana tinggal di tengah pusat kota Depok. Hunian khusus putri
                    yang nyaman dengan suasana asri mulai Rp 750.000/Bulan
                </p>
                <a href="{{ route('rooms.index') }}"
                   class="inline-block mt-6 bg-[#2F4538] text-white text-sm font-semibold px-7 py-3 rounded-full hover:bg-[#26392E] transition animate-slide-up stagger-2">
                    Lihat Kamar
                </a>
            </div>
        </div>
    </section>

    {{-- Fasilitas Bersama --}}
    <section class="bg-white py-16 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl px-6 md:px-12 py-12">
                <div class="text-center mb-10">
                    <h2 class="font-display text-2xl md:text-3xl font-bold text-gray-900">Fasilitas Bersama</h2>
                    <p class="text-gray-500 text-sm md:text-base mt-3 max-w-xl mx-auto">
                        Keamanan dan kenyamanan penyewa prioritas utama kami untuk hunian yang aman dan nyaman
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                    $fasilitasBersama = [
                        ['img' => 'Ruang+Tamu', 'icon' => 'sofa', 'title' => 'Ruang Tamu', 'desc' => 'Area bersama yang nyaman untuk saling bercengkerama dan menciptakan suasana kos yang terasa seperti rumah sendiri.', 'fallback' => 'https://via.placeholder.com/600x420/8B9D83/ffffff?text=Ruang+Tamu'],
                        ['img' => 'Dapur+Bersih', 'icon' => 'pan', 'title' => 'Dapur Bersih', 'desc' => 'Dapur bersih dengan peralatan lengkap dirancang khusus untuk memfasilitasi kebutuhan memasak harian yang nyaman.', 'fallback' => 'https://via.placeholder.com/600x420/8B9D83/ffffff?text=Dapur+Bersih'],
                        ['img' => 'Area+Jemuran', 'icon' => 'shirt', 'title' => 'Area Jemuran', 'desc' => 'Area jemuran yang luas di lantai 2 dengan paparan cahaya matahari maksimal.', 'fallback' => 'https://via.placeholder.com/600x420/8B9D83/ffffff?text=Area+Jemuran'],
                        ['img' => 'Area+Parkir', 'icon' => 'motorcycle', 'title' => 'Area Parkir Motor', 'desc' => 'Area parkir motor yang aman dan terjaga, tersedia untuk seluruh penghuni kos tanpa biaya tambahan.', 'fallback' => 'https://via.placeholder.com/600x420/8B9D83/ffffff?text=Area+Parkir'],
                    ];
                    @endphp
                    @foreach($fasilitasBersama as $f)
                    <div class="rounded-xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition">
                        <img src="{{ asset('images/fasilitas/' . Str::slug($f['title']) . '.jpg') }}"
                             alt="{{ $f['title'] }}"
                             class="w-full h-64 object-cover"
                             onerror="this.src='{{ $f['fallback'] }}'" />
                        <div class="p-5">
                            <h3 class="flex items-center gap-2 font-semibold text-gray-900">
                                <span class="w-5 h-5 text-[#2F4538]">{!! \App\Support\Icons::get($f['icon']) !!}</span> {{ $f['title'] }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('tentang') }}"
                       class="inline-block bg-[#2F4538] text-white text-sm font-semibold px-6 py-3 rounded-full hover:bg-[#26392E] transition">
                        Lihat Semua Fasilitas &amp; Lingkungan
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimoni --}}
    <section class="bg-white py-16 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="font-display text-2xl md:text-3xl font-bold text-gray-900">Apa Kata Mereka?</h2>
            <p class="text-gray-500 text-sm md:text-base mt-3 max-w-xl mx-auto">
                Kepuasan penghuni adalah kebanggaan kami dalam memberikan hunian dengan harga terjangkau dan berkualitas
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10 text-left">
                @forelse($testimonials as $t)
                <div class="border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center gap-0.5 text-yellow-400 mb-3">
                        @for($i = 0; $i < $t->rating; $i++) <span class="w-4 h-4">{!! \App\Support\Icons::get('star') !!}</span> @endfor
                    </div>
                    <p class="text-sm text-gray-600 mb-4">"{{ $t->content }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#2F4538] text-white flex items-center justify-center text-sm font-semibold">
                            {{ substr($t->display_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $t->display_name }}</p>
                            <p class="text-xs text-gray-400">{{ $t->label ?? 'Penghuni' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                {{-- Fallback testimoni statis --}}
                @php
                $testimoniDefault = [
                    ['rating' => 5, 'content' => 'Mantap nyaman banget nih kosan, fasilitas dapur nya lengkap banget jadi rajin masak nih.', 'nama' => 'Eca', 'inisial' => 'E', 'label' => 'Penghuni Aktif'],
                    ['rating' => 5, 'content' => 'Buat yang anak gunadarma, Best banget nih ini kosan, sedekit itu sama kompus terus lokasi nya dekat banyak warkop.', 'nama' => 'Lidia', 'inisial' => 'L', 'label' => 'Penghuni Aktif'],
                    ['rating' => 5, 'content' => 'Wifi nya kenceng banget disini, ruang tamu nya juga ada 3 enak banget buat nugas atau kalau mau ngumpul bareng bestie :)', 'nama' => 'Cahya', 'inisial' => 'C', 'label' => 'Alumni'],
                ];
                @endphp
                @foreach($testimoniDefault as $td)
                <div class="border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center gap-0.5 text-yellow-400 mb-3">@for($i = 0; $i < $td['rating']; $i++) <span class="w-4 h-4">{!! \App\Support\Icons::get('star') !!}</span> @endfor</div>
                    <p class="text-sm text-gray-600 mb-4">"{{ $td['content'] }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#2F4538] text-white flex items-center justify-center text-sm font-semibold">{{ $td['inisial'] }}</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $td['nama'] }}</p>
                            <p class="text-xs text-gray-400">{{ $td['label'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
                @endforelse
            </div>
        </div>
    </section>

</div>
@endsection
