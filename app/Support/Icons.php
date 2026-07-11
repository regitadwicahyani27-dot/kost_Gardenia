<?php

namespace App\Support;

/**
 * Pustaka ikon SVG line-art terpusat (gaya konsisten dengan RoomFacilities).
 * Pakai di Blade lewat: {!! \App\Support\Icons::get('nama-ikon') !!}
 * dibungkus span/div dengan class ukuran, misal: <span class="w-5 h-5">...</span>
 */
class Icons
{
    public static function get(string $name): string
    {
        return static::all()[$name] ?? '';
    }

    protected static function all(): array
    {
        $stroke = 'fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"';

        return [
            'clipboard' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2\"/><rect x=\"9\" y=\"3\" width=\"6\" height=\"4\" rx=\"1\"/></svg>",
            'check' => "<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2.4\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"w-full h-full\"><polyline points=\"20 6 9 17 4 12\"/></svg>",
            'close' => "<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2.4\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"w-full h-full\"><line x1=\"18\" y1=\"6\" x2=\"6\" y2=\"18\"/><line x1=\"6\" y1=\"6\" x2=\"18\" y2=\"18\"/></svg>",
            'check-circle' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><circle cx=\"12\" cy=\"12\" r=\"10\"/><polyline points=\"16 9 10.5 14.5 8 12\"/></svg>",
            'close-circle' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><circle cx=\"12\" cy=\"12\" r=\"10\"/><line x1=\"15\" y1=\"9\" x2=\"9\" y2=\"15\"/><line x1=\"9\" y1=\"9\" x2=\"15\" y2=\"15\"/></svg>",
            'phone' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z\"/></svg>",
            'map-pin' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z\"/><circle cx=\"12\" cy=\"10\" r=\"3\"/></svg>",
            'credit-card' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><rect x=\"1\" y=\"4\" width=\"22\" height=\"16\" rx=\"2\"/><line x1=\"1\" y1=\"10\" x2=\"23\" y2=\"10\"/></svg>",
            'user' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2\"/><circle cx=\"12\" cy=\"7\" r=\"4\"/></svg>",
            'home' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z\"/><polyline points=\"9 22 9 12 15 12 15 22\"/></svg>",
            'calendar' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><rect x=\"3\" y=\"4\" width=\"18\" height=\"18\" rx=\"2\"/><line x1=\"16\" y1=\"2\" x2=\"16\" y2=\"6\"/><line x1=\"8\" y1=\"2\" x2=\"8\" y2=\"6\"/><line x1=\"3\" y1=\"10\" x2=\"21\" y2=\"10\"/></svg>",
            'tag' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M20.59 13.41L13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z\"/><line x1=\"7\" y1=\"7\" x2=\"7.01\" y2=\"7\"/></svg>",
            'wallet' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><line x1=\"12\" y1=\"1\" x2=\"12\" y2=\"23\"/><path d=\"M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6\"/></svg>",
            'chat' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z\"/></svg>",
            'bed' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M3 18v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6\"/><path d=\"M3 18h18\"/><path d=\"M3 12V7a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v3\"/></svg>",
            'sofa' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M4 13V9a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4\"/><path d=\"M2 13h20v4a1 1 0 0 1-1 1h-1v2h-2v-2H6v2H4v-2H3a1 1 0 0 1-1-1z\"/></svg>",
            'pan' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><circle cx=\"10\" cy=\"12\" r=\"6\"/><line x1=\"16\" y1=\"12\" x2=\"22\" y2=\"12\"/></svg>",
            'shirt' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M8 3l4 2 4-2 4 4-3 3v11H7V10L4 7z\"/></svg>",
            'motorcycle' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><circle cx=\"5\" cy=\"17\" r=\"3\"/><circle cx=\"19\" cy=\"17\" r=\"3\"/><path d=\"M5 17h6l3-6h4l2 6\"/><path d=\"M11 11l-2-4H6\"/></svg>",
            'door' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><rect x=\"5\" y=\"2\" width=\"14\" height=\"20\" rx=\"1\"/><circle cx=\"15\" cy=\"12\" r=\"1\" fill=\"currentColor\" stroke=\"none\"/></svg>",
            'wifi' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M5 12.55a11 11 0 0 1 14.08 0\"/><path d=\"M1.42 9a16 16 0 0 1 21.16 0\"/><path d=\"M8.53 16.11a6 6 0 0 1 6.95 0\"/><line x1=\"12\" y1=\"20\" x2=\"12.01\" y2=\"20\"/></svg>",
            'utensils' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M6 2v6a2 2 0 0 0 2 2h0a2 2 0 0 0 2-2V2\"/><path d=\"M7 2v20\"/><path d=\"M17 2c-1 2-1 4-1 6a2 2 0 0 0 2 2 2 2 0 0 0 2-2c0-2 0-4-1-6\"/><path d=\"M18 10v12\"/></svg>",
            'plant' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M12 22V12\"/><path d=\"M12 12C7 12 4 9 4 5c4 0 7 3 8 7z\"/><path d=\"M12 12c5 0 8-3 8-7-4 0-7 3-8 7z\"/></svg>",
            'train' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><rect x=\"4\" y=\"3\" width=\"16\" height=\"14\" rx=\"3\"/><line x1=\"4\" y1=\"11\" x2=\"20\" y2=\"11\"/><path d=\"M8 17l-2 4\"/><path d=\"M16 17l2 4\"/><circle cx=\"8\" cy=\"14\" r=\"0.8\" fill=\"currentColor\" stroke=\"none\"/><circle cx=\"16\" cy=\"14\" r=\"0.8\" fill=\"currentColor\" stroke=\"none\"/></svg>",
            'building' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M3 21h18\"/><path d=\"M5 21V7l7-4 7 4v14\"/><line x1=\"9\" y1=\"9\" x2=\"9\" y2=\"9.01\"/><line x1=\"9\" y1=\"13\" x2=\"9\" y2=\"13.01\"/><line x1=\"9\" y1=\"17\" x2=\"9\" y2=\"17.01\"/><line x1=\"15\" y1=\"9\" x2=\"15\" y2=\"9.01\"/><line x1=\"15\" y1=\"13\" x2=\"15\" y2=\"13.01\"/><line x1=\"15\" y1=\"17\" x2=\"15\" y2=\"17.01\"/></svg>",
            'fork-knife' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M6 2v7a2 2 0 0 0 4 0V2\"/><path d=\"M8 9v13\"/><path d=\"M17 2c-1.5 2-2 4.5-2 6.5a2.5 2.5 0 0 0 5 0c0-2-.5-4.5-2-6.5z\"/><path d=\"M18 10.5V22\"/></svg>",
            'warning' => "<svg viewBox=\"0 0 24 24\" $stroke class=\"w-full h-full\"><path d=\"M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z\"/><line x1=\"12\" y1=\"9\" x2=\"12\" y2=\"13\"/><line x1=\"12\" y1=\"17\" x2=\"12.01\" y2=\"17\"/></svg>",
            'star' => "<svg viewBox=\"0 0 24 24\" fill=\"currentColor\" stroke=\"none\" class=\"w-full h-full\"><polygon points=\"12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2\"/></svg>",
            'star-outline' => "<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"w-full h-full\"><polygon points=\"12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2\"/></svg>",
        ];
    }
}
