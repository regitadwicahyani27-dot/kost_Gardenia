<?php

namespace App\Support;

/**
 * Daftar fasilitas standar yang berlaku SAMA untuk semua kamar.
 * Cukup ubah di satu tempat ini, tidak perlu konfigurasi ulang per kamar.
 */
class RoomFacilities
{
    public static function all(): array
    {
        return [
            [
                'title' => 'Kamar Mandi Dalam',
                'desc'  => 'Kamar mandi dilengkapi dengan shower, rak mandi dan bak mandi',
                'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full"><path d="M6 6l1.5 1.5"/><path d="M18 8a6 6 0 00-9.33-5"/><rect x="5" y="9" width="14" height="3" rx="1.5"/><path d="M8 15v1"/><path d="M12 15v2"/><path d="M16 15v1"/></svg>',
            ],
            [
                'title' => 'Meja Belajar',
                'desc'  => 'Tersedia meja belajar kayu dilengkapi dengan kursi yang nyaman',
                'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full"><path d="M3 7l2-4h14l2 4"/><path d="M3 7h18"/><path d="M5 7v13"/><path d="M19 7v13"/></svg>',
            ],
            [
                'title' => 'WiFi',
                'desc'  => 'Koneksi WiFi berkecepatan 50 Mbps dan stabil 24 jam',
                'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>',
            ],
            [
                'title' => 'Kasur Nyaman',
                'desc'  => 'Sudah dilengkapi kasur nyaman',
                'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full"><path d="M3 18v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6"/><path d="M3 18h18"/><path d="M3 12V7a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v3"/></svg>',
            ],
            [
                'title' => 'Lemari Baju',
                'desc'  => 'Tersedia lemari baju kayu besar dengan desain 2 pintu',
                'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full"><rect x="4" y="3" width="16" height="18" rx="1"/><line x1="12" y1="3" x2="12" y2="21"/><line x1="9.5" y1="12" x2="9.5" y2="12"/><line x1="14.5" y1="12" x2="14.5" y2="12"/></svg>',
            ],
            [
                'title' => 'Listrik',
                'desc'  => 'Sudah termasuk biaya listrik untuk kebutuhan dasar harian',
                'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
            ],
        ];
    }
}
