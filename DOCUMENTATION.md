# Dokumentasi Teknis — Sistem Manajemen Kos Putri Gardenia

> Dibuat otomatis dari analisis codebase. Versi terakhir: 22 Juli 2026.

---

## Daftar Isi

1. [Ringkasan Project](#1-ringkasan-project)
2. [Tech Stack](#2-tech-stack)
3. [Struktur Folder & File](#3-struktur-folder--file)
4. [Arsitektur & Alur Data](#4-arsitektur--alur-data)
5. [Skema Database](#5-skema-database)
6. [Daftar Halaman & Navigasi (per Role)](#6-daftar-halaman--navigasi-per-role)
7. [Daftar API Endpoint / Route](#7-daftar-api-endpoint--route)
8. [Fitur & Logika Bisnis Utama](#8-fitur--logika-bisnis-utama)
9. [Integrasi Eksternal](#9-integrasi-eksternal)
10. [Dependency & Package](#10-dependency--package)
11. [Cara Menjalankan Project](#11-cara-menjalankan-project)
12. [Kendala / Catatan Teknis](#12-kendala--catatan-teknis)

---

## 1. Ringkasan Project

### Identitas

| Atribut | Detail |
|---------|--------|
| **Nama Project** | Kos Putri Gardenia |
| **Nama Repositori** | `kost_Gardenia` |
| **Tujuan Utama** | Sistem manajemen kos berbasis web: pengelolaan kamar, booking, dan pembayaran uang muka (DP) secara online |
| **Target Pengguna** | **Publik** (calon penghuni yang belum login), **User** (penghuni terdaftar), **Admin** (pengelola kos) |

### Deskripsi Singkat

Project ini adalah aplikasi web manajemen kos (rumah kos) yang dibangun dengan Laravel 12. Sistem ini mengotomatiskan proses mulai dari promosi kamar ke publik hingga konfirmasi pembayaran oleh admin.

### Alur Bisnis Utama (Booking Kamar)

```
[Publik] Lihat kamar tersedia di halaman /kamar
     ↓
[Publik] Klik detail kamar → lihat foto, fasilitas, harga
     ↓
[User] Login / Register (jika belum punya akun)
     ↓
[User] Klik "Pesan Kamar" → isi form booking
       - Pilih tanggal check-in
       - Pilih metode pembayaran DP (QRIS / Dana / OVO / BCA)
     ↓
[System] Booking dibuat dengan status "pending"
         Pembayaran DP Rp 250.000 dibuat dengan status "pending"
         Kamar BELUM dikunci (menunggu verifikasi admin)
     ↓
[User] Upload bukti transfer pembayaran DP
     ↓
[Admin] Menerima notifikasi pembayaran pending di dashboard
        → Verifikasi bukti transfer
     ↓
[Admin] APPROVE → Status booking → "confirmed", kamar → tidak tersedia
        REJECT  → Status booking → "cancelled", kamar tetap tersedia
     ↓
[Admin] Mengubah status booking secara manual:
        pending → confirmed → active → completed
        (atau cancelled kapan saja)
     ↓
[Admin] Jika booking completed/cancelled → kamar otomatis tersedia lagi
```

---

## 2. Tech Stack

### Bahasa Pemrograman

| Bahasa | Versi | Peran |
|--------|-------|-------|
| PHP | ^8.2 (target platform 8.3.0) | Backend |
| JavaScript | ES2020+ | Frontend interactivity |
| HTML / Blade | — | Template engine |
| CSS | — | Styling (via Tailwind) |

### Framework

| Framework | Versi | Peran |
|-----------|-------|-------|
| Laravel | ^12.0 | Backend MVC framework |
| Laravel Breeze | ^2.4 | Scaffolding autentikasi (login, register, reset password) |
| Tailwind CSS | ^3.1.0 | Utility-first CSS framework |
| Alpine.js | ^3.4.2 | JavaScript ringan untuk reaktivitas UI |
| Vite | ^6.0.11 | Build tool dan dev server frontend |

### Database

| Komponen | Detail |
|----------|--------|
| **Lokal** | SQLite (file `database/database.sqlite`) |
| **Production** | MySQL (InfinityFree: `sql306.infinityfree.com` atau sejenisnya) |

### Library / Package Penting

| Package | Versi | Fungsi |
|---------|-------|--------|
| `laravel/framework` | ^12.0 | Core framework |
| `laravel/breeze` | ^2.4 (dev) | Starter kit autentikasi |
| `laravel/tinker` | ^2.10.1 | REPL interaktif untuk debugging |
| `alpinejs` | ^3.4.2 | Reaktivitas UI ringan (modal, toggle, dsb) |
| `axios` | ^1.7.4 | HTTP client untuk request AJAX dari JS |
| `@tailwindcss/forms` | ^0.5.2 | Reset styling form untuk Tailwind |
| `laravel-vite-plugin` | ^1.2.0 | Integrasi Vite dengan Laravel |
| `fakerphp/faker` | ^1.23 (dev) | Generator data palsu untuk testing/seeding |
| `phpunit/phpunit` | ^11.5.3 (dev) | Framework unit testing |

### Tools Deployment / Hosting

| Tool | Fungsi |
|------|--------|
| **InfinityFree** | Shared hosting gratis untuk production |
| **FileZilla (FTP)** | Upload file ke server via FTP (`ftpupload.net:21`) |
| **phpMyAdmin** | Import database SQL ke server production |
| `.htaccess` (root) | Redirect semua request dari root `htdocs/` ke subfolder `public/` |

---

## 3. Struktur Folder & File

### Pohon Direktori (Maks. 3 Level)

```
gardenia-kosla122/
├── app/                          # Kode aplikasi Laravel
│   ├── Http/
│   │   ├── Controllers/          # Semua controller
│   │   │   ├── Admin/            # Controller khusus admin
│   │   │   └── Auth/             # Controller autentikasi (Breeze)
│   │   ├── Middleware/           # Middleware (AdminMiddleware)
│   │   └── Requests/             # Form Request validation
│   ├── Models/                   # Eloquent ORM models
│   ├── Providers/                # Service providers (AppServiceProvider)
│   └── Support/                  # Kelas helper/utility
│       ├── Icons.php             # Koleksi ikon SVG inline
│       └── RoomFacilities.php    # Daftar fasilitas kamar statis
│
├── bootstrap/                    # Bootstrap framework dan cache
├── config/                       # Konfigurasi Laravel
├── database/
│   ├── migrations/               # Migrasi skema database (14 file)
│   ├── seeders/                  # Seeder (AdminSeeder, DatabaseSeeder)
│   ├── factories/                # Factory untuk data dummy
│   ├── database.sqlite           # File database SQLite lokal
│   └── gardenia_kos.sql          # Dump SQL untuk production (MySQL)
│
├── deploy-infinityfree/          # Aset dan panduan deployment
│   ├── .htaccess.root            # Template .htaccess untuk root hosting
│   ├── PANDUAN-DEPLOY.md         # Panduan deploy step-by-step
│   └── _staging/                 # Staging build artifacts
│
├── public/                       # Webroot yang diakses browser
│   ├── index.php                 # Entry point Laravel
│   ├── .htaccess                 # URL rewriting
│   ├── build/                    # Hasil build Vite (CSS/JS compiled)
│   └── images/                   # Gambar statis
│
├── resources/
│   ├── css/app.css               # Entry point CSS (Tailwind directives)
│   ├── js/app.js                 # Entry point JavaScript
│   └── views/                    # Template Blade
│       ├── layouts/              # Layout utama (app, user, admin)
│       ├── admin/                # Halaman panel admin
│       ├── user/                 # Halaman panel user
│       ├── rooms/                # Halaman kamar publik
│       ├── profile/              # Halaman edit profil
│       ├── partials/             # Komponen parsial
│       ├── components/           # Blade components reusable
│       ├── home.blade.php        # Halaman beranda publik
│       └── tentang.blade.php     # Halaman tentang kami
│
├── routes/
│   ├── web.php                   # Definisi semua route web
│   ├── auth.php                  # Route autentikasi Breeze
│   └── console.php               # Route command Artisan
│
├── storage/                      # Storage: logs, cache, file upload
├── tests/                        # Unit dan feature tests
├── vendor/                       # Dependencies Composer (tidak di-commit)
├── node_modules/                 # Dependencies NPM (tidak di-commit)
├── .env                          # Environment variables aktif (tidak di-commit)
├── .env.example                  # Template environment variables
├── .env_hosting                  # Template env untuk production hosting
├── composer.json                 # Definisi dependencies PHP
├── package.json                  # Definisi dependencies JavaScript
├── tailwind.config.js            # Konfigurasi Tailwind CSS
├── vite.config.js                # Konfigurasi Vite build tool
├── artisan                       # CLI Laravel
└── DOCUMENTATION.md              # File ini
```

### File Konfigurasi Penting & Environment Variables

| Variabel | Deskripsi | Contoh Nilai |
|----------|-----------|--------------|
| `APP_NAME` | Nama aplikasi | `Gardenia Kos` |
| `APP_ENV` | Environment (local/production) | `local` / `production` |
| `APP_KEY` | Kunci enkripsi Laravel (wajib generate) | `base64:...` |
| `APP_DEBUG` | Mode debug | `true` / `false` |
| `APP_URL` | URL dasar aplikasi | `http://localhost` |
| `DB_CONNECTION` | Driver database | `sqlite` / `mysql` |
| `DB_HOST` | Host database (MySQL production) | `sql306.infinityfree.com` |
| `DB_PORT` | Port database | `3306` |
| `DB_DATABASE` | Nama database | `if0_xxx_gardenia` |
| `DB_USERNAME` | Username database | `if0_xxx` |
| `DB_PASSWORD` | Password database | *(rahasia)* |
| `SESSION_DRIVER` | Driver session | `database` |
| `FILESYSTEM_DISK` | Disk penyimpanan file | `local` |
| `QUEUE_CONNECTION` | Driver queue | `database` |
| `CACHE_STORE` | Driver cache | `database` |
| `MAIL_MAILER` | Driver mail | `log` |
| `MAIL_FROM_ADDRESS` | Alamat pengirim email | `hello@example.com` |

---

## 4. Arsitektur & Alur Data

### Pola Arsitektur

Project menggunakan pola **MVC (Model-View-Controller)** bawaan Laravel:

- **Model** — Eloquent ORM (`app/Models/`): merepresentasikan entitas data dan hubungan antar tabel
- **View** — Blade Templates (`resources/views/`): merender HTML yang dikirim ke browser
- **Controller** — (`app/Http/Controllers/`): menangani request, memanggil model, dan mengembalikan view
- **Route** — (`routes/web.php`, `routes/auth.php`): memetakan URL ke controller method

Tidak ada REST API murni. Semua komunikasi terjadi via **server-side rendering** dengan sesekali **AJAX (Axios)** untuk fitur booking tanpa page reload.

### Cara Frontend Berkomunikasi dengan Backend

| Mekanisme | Kapan Digunakan |
|-----------|-----------------|
| **Form HTML biasa** (POST/PATCH/DELETE) | Sebagian besar operasi CRUD |
| **AJAX (Axios)** | Submit booking (`POST /user/booking`) → respons JSON untuk modal sukses tanpa reload halaman |
| **Alpine.js** | Mengelola state UI lokal: toggle dropdown, buka/tutup modal, galeri foto |

**Alur AJAX Booking:**
```
User klik "Konfirmasi Booking" di form
    → Axios POST /user/booking (data form)
    → BookingController::store() diproses di backend
    → Response JSON: { success, nama, kamar, booking_code, sisa, ... }
    → Alpine.js menampilkan modal sukses dengan data tersebut
```

### Mekanisme Autentikasi & Otorisasi

| Komponen | Detail |
|----------|--------|
| **Autentikasi** | Laravel Breeze (session-based, bukan token/JWT) |
| **Session** | Disimpan di tabel `sessions` (database driver) |
| **Guard** | `web` (default Laravel) |
| **Middleware Auth** | `auth` — memastikan user sudah login |
| **Middleware Admin** | `admin` (`AdminMiddleware`) — memastikan user punya `role = 'admin'`, HTTP 403 jika bukan admin |
| **Role System** | Kolom `role` di tabel `users` (`'user'` atau `'admin'`). Method helper `isAdmin()` di model `User` |
| **Redirect Post-Login** | Route `/dashboard` mengecek role: admin ke `/admin/dashboard`, user ke `/user/dashboard` |
| **HTTPS Paksa** | `AppServiceProvider::boot()` memanggil `URL::forceScheme('https')` di non-`local` environment |

---

## 5. Skema Database

### Tabel `users`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Primary key |
| `name` | VARCHAR(255) | NOT NULL | Nama lengkap |
| `email` | VARCHAR(255) | UNIQUE, NOT NULL | Email (untuk login) |
| `email_verified_at` | TIMESTAMP | NULL | Waktu verifikasi email |
| `phone` | VARCHAR(255) | NULL | Nomor telepon |
| `address` | TEXT | NULL | Alamat |
| `birth_date` | DATE | NULL | Tanggal lahir |
| `role` | VARCHAR(255) | DEFAULT `'user'` | Role: `'user'` atau `'admin'` |
| `avatar` | VARCHAR(255) | NULL | Path foto profil (`storage/avatars/`) |
| `password` | VARCHAR(255) | NOT NULL | Password (bcrypt hash) |
| `remember_token` | VARCHAR(100) | NULL | Token "ingat saya" |
| `created_at` | TIMESTAMP | NULL | — |
| `updated_at` | TIMESTAMP | NULL | — |

### Tabel `rooms`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK | — |
| `name` | VARCHAR(255) | NOT NULL | Nama kamar (mis. "Kamar A1") |
| `type` | ENUM | NOT NULL, DEFAULT `'standard'` | `standard`, `deluxe`, `vip` |
| `price` | DECIMAL(15,2) | NOT NULL, DEFAULT 0 | Harga sewa per bulan |
| `description` | TEXT | NULL | Deskripsi kamar |
| `is_available` | BOOLEAN | DEFAULT `true` | Status ketersediaan |
| `floor` | INTEGER | DEFAULT `1` | Lantai (1 atau 2) |
| `created_at` | TIMESTAMP | NULL | — |
| `updated_at` | TIMESTAMP | NULL | — |

### Tabel `room_photos`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK | — |
| `room_id` | BIGINT UNSIGNED | FK → `rooms.id` CASCADE | Kamar pemilik foto |
| `photo_path` | VARCHAR(255) | NOT NULL | Path file foto (`storage/rooms/`) |
| `is_primary` | BOOLEAN | DEFAULT `false` | Foto utama kamar |
| `created_at` | TIMESTAMP | NULL | — |
| `updated_at` | TIMESTAMP | NULL | — |

### Tabel `facilities`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK | — |
| `name` | VARCHAR(255) | NOT NULL | Nama fasilitas (mis. "AC") |
| `icon` | VARCHAR(255) | NULL | Nama/kode ikon |
| `created_at` | TIMESTAMP | NULL | — |
| `updated_at` | TIMESTAMP | NULL | — |

### Tabel `room_facility` *(Pivot Many-to-Many)*

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `room_id` | BIGINT UNSIGNED | FK → `rooms.id` CASCADE | — |
| `facility_id` | BIGINT UNSIGNED | FK → `facilities.id` CASCADE | — |
| — | — | PRIMARY KEY (`room_id`, `facility_id`) | Kunci komposit |

### Tabel `bookings`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK | — |
| `user_id` | BIGINT UNSIGNED | FK → `users.id` CASCADE | Penyewa |
| `room_id` | BIGINT UNSIGNED | FK → `rooms.id` CASCADE | Kamar yang dipesan |
| `booking_code` | VARCHAR(255) | UNIQUE | Kode unik booking (`GDN-XXXXXXXX`) |
| `check_in_date` | DATE | NOT NULL | Tanggal masuk |
| `duration_months` | INTEGER | DEFAULT `1` | Durasi sewa (bulan) |
| `total_price` | DECIMAL(15,2) | NOT NULL, DEFAULT 0 | Total harga sewa |
| `dp_amount` | DECIMAL(15,2) | DEFAULT 250000 | Jumlah uang muka |
| `status` | ENUM | DEFAULT `'pending'` | `pending`, `confirmed`, `active`, `cancelled`, `completed` |
| `notes` | TEXT | NULL | Catatan tambahan |
| `cancelled_reason` | TEXT | NULL | Alasan pembatalan |
| `cancelled_by` | VARCHAR(255) | NULL | `'user'`, `'admin'`, `'system'` |
| `created_at` | TIMESTAMP | NULL | — |
| `updated_at` | TIMESTAMP | NULL | — |

### Tabel `payments`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK | — |
| `booking_id` | BIGINT UNSIGNED | FK → `bookings.id` CASCADE | Booking yang dibayar |
| `user_id` | BIGINT UNSIGNED | FK → `users.id` CASCADE | User yang membayar |
| `amount` | DECIMAL(15,2) | NOT NULL, DEFAULT 0 | Nominal pembayaran |
| `payment_method` | ENUM | NOT NULL | `qris`, `dana`, `ovo`, `bca` |
| `payment_type` | ENUM | DEFAULT `'dp'` | `dp`, `monthly`, `full` |
| `proof_path` | VARCHAR(255) | NULL | Path bukti transfer (`storage/payments/`) |
| `status` | ENUM | DEFAULT `'pending'` | `pending`, `verified`, `rejected` |
| `verified_at` | TIMESTAMP | NULL | Waktu verifikasi admin |
| `verified_by` | BIGINT UNSIGNED | NULL, FK → `users.id` | Admin yang memverifikasi |
| `notes` | TEXT | NULL | Catatan/alasan penolakan |
| `created_at` | TIMESTAMP | NULL | — |
| `updated_at` | TIMESTAMP | NULL | — |

### Tabel `testimonials`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| `id` | BIGINT UNSIGNED | PK | — |
| `user_id` | BIGINT UNSIGNED | NULL, FK → `users.id` SET NULL | User terkait (opsional) |
| `booking_id` | BIGINT UNSIGNED | NULL, FK → `bookings.id` SET NULL | Booking terkait (opsional) |
| `name` | VARCHAR(255) | NULL | Nama tampilan (diisi admin) |
| `label` | VARCHAR(255) | NULL | Label/jabatan penghuni |
| `rating` | TINYINT | DEFAULT `5` | Rating bintang 1-5 |
| `content` | TEXT | NOT NULL | Isi testimoni |
| `status` | ENUM | DEFAULT `'pending'` | `pending`, `approved`, `rejected` |
| `created_at` | TIMESTAMP | NULL | — |
| `updated_at` | TIMESTAMP | NULL | — |

### Tabel `sessions` *(Laravel Built-in)*

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | VARCHAR | PK, ID sesi |
| `user_id` | BIGINT | NULL, FK user yang login |
| `ip_address` | VARCHAR(45) | IP klien |
| `user_agent` | TEXT | User agent browser |
| `payload` | LONGTEXT | Data sesi terenkripsi |
| `last_activity` | INTEGER | Unix timestamp aktivitas terakhir |

### Diagram Relasi Antar Tabel

```
users ──────────────────────────────────────────┐
  │ hasMany                                       │
  ├──── bookings ─── belongsTo ── rooms           │
  │         │ hasMany                             │
  │         ├──── payments ─── belongsTo ─────────┘ (verified_by)
  │         └──── testimonials
  │
  ├──── payments (langsung dari user)
  └──── testimonials

rooms ─── hasMany ─── room_photos
rooms ─── belongsToMany ─── facilities (via room_facility)
rooms ─── hasMany ─── bookings
```

---

## 6. Daftar Halaman & Navigasi (per Role)

### Halaman Publik (Tanpa Login)

| Nama Halaman | URL / Route | View | Fitur Utama | Navigasi Lanjutan |
|---|---|---|---|---|
| **Beranda** | `/` (`home`) | `home.blade.php` | Showcase 3 kamar tersedia, 3 testimoni, hero section | Daftar Kamar, Login |
| **Daftar Kamar** | `/kamar` (`rooms.index`) | `rooms/index.blade.php` | List kamar, filter lantai | Detail Kamar |
| **Detail Kamar** | `/kamar/{room}` (`rooms.show`) | `rooms/show.blade.php` | Galeri, fasilitas, harga, tombol pesan | Login |
| **Tentang Kami** | `/tentang` (`tentang`) | `tentang.blade.php` | Info kos, lokasi, fasilitas umum | — |
| **Login** | `/login` (`login`) | `auth/login.blade.php` | Form login email + password | Dashboard |
| **Register** | `/register` (`register`) | `auth/register.blade.php` | Form registrasi | Dashboard |
| **Lupa Password** | `/forgot-password` (`password.request`) | `auth/forgot-password.blade.php` | Input email reset | Email reset |
| **Reset Password** | `/reset-password/{token}` (`password.reset`) | `auth/reset-password.blade.php` | Form password baru | Login |

### Halaman User (Harus Login, Role: `user`)

| Nama Halaman | URL / Route | View | Fitur Utama | Navigasi Lanjutan |
|---|---|---|---|---|
| **Dashboard User** | `/user/dashboard` (`user.dashboard`) | `user/dashboard.blade.php` | Ringkasan booking aktif, total booking | Detail Booking |
| **Daftar Kamar** | `/user/kamar` (`user.rooms`) | `user/rooms/index.blade.php` | List kamar, filter lantai | Detail Kamar |
| **Detail Kamar** | `/user/kamar/{room}` (`user.rooms.show`) | `user/rooms/show.blade.php` | Galeri, fasilitas, tombol "Pesan Sekarang" | Form Booking |
| **Form Booking** | `/user/booking/{room}` (`user.booking.create`) | `user/booking/create.blade.php` | Form booking dengan modal konfirmasi AJAX | Dashboard |
| **Detail Booking** | `/user/booking/{booking}` (`user.booking.show`) | `user/booking/show.blade.php` | Detail + status + upload bukti bayar | — |
| **Riwayat Booking** | `/user/riwayat` (`user.booking.history`) | `user/booking/history.blade.php` | List semua booking (paginated 10) | Detail Booking |
| **Edit Profil** | `/user/profil` (`user.profile.edit`) | `profile/edit.blade.php` | Edit data diri, avatar, password | — |

### Halaman Admin (Harus Login, Role: `admin`)

| Nama Halaman | URL / Route | View | Fitur Utama | Navigasi Lanjutan |
|---|---|---|---|---|
| **Dashboard Admin** | `/admin/dashboard` (`admin.dashboard`) | `admin/dashboard.blade.php` | Statistik, 5 booking terbaru, pembayaran pending | Detail Booking, Verifikasi |
| **Daftar Kamar** | `/admin/kamar` (`admin.kamar.index`) | `admin/rooms/index.blade.php` | List kamar (paginated 20), toggle, edit, hapus | Tambah Kamar |
| **Tambah Kamar** | `/admin/kamar/create` (`admin.kamar.create`) | `admin/rooms/create.blade.php` | Form tambah + upload foto + fasilitas | Daftar Kamar |
| **Edit Kamar** | `/admin/kamar/{kamar}/edit` (`admin.kamar.edit`) | `admin/rooms/edit.blade.php` | Form edit + manajemen foto individual | Daftar Kamar |
| **Detail Booking** | `/admin/booking/{booking}` (`admin.booking.show`) | `admin/bookings/show.blade.php` | Detail + update status + pembatalan | Daftar Pembayaran |
| **Daftar Pembayaran** | `/admin/pembayaran` (`admin.payment.index`) | `admin/payments/index.blade.php` | List pembayaran, filter status, verifikasi/tolak | Detail Booking |
| **Daftar Testimoni** | `/admin/testimoni` (`admin.testimonial.index`) | `admin/testimonials/index.blade.php` | List testimoni, hapus | Tambah, Edit |
| **Tambah Testimoni** | `/admin/testimoni/create` (`admin.testimonial.create`) | `admin/testimonials/create.blade.php` | Form tambah testimoni | Daftar Testimoni |
| **Edit Testimoni** | `/admin/testimoni/{testimonial}/edit` (`admin.testimonial.edit`) | `admin/testimonials/edit.blade.php` | Form edit + ubah status | Daftar Testimoni |

---

## 7. Daftar API Endpoint / Route

> Semua endpoint berbasis web (bukan REST API murni). Response HTML kecuali yang bertanda [JSON].

### Route Publik

| Method | Path | Controller Method | Fungsi | Auth |
|--------|------|-------------------|--------|------|
| GET | `/` | `HomeController@index` | Halaman beranda | Tidak |
| GET | `/tentang` | `HomeController@tentang` | Tentang kami | Tidak |
| GET | `/kamar` | `RoomController@index` | Daftar kamar (`?lantai=`) | Tidak |
| GET | `/kamar/{room}` | `RoomController@show` | Detail kamar | Tidak |

### Route Autentikasi (Laravel Breeze)

| Method | Path | Fungsi | Kondisi |
|--------|------|--------|---------|
| GET | `/login` | Form login | Guest only |
| POST | `/login` | Proses login | Guest only |
| GET | `/register` | Form register | Guest only |
| POST | `/register` | Proses registrasi | Guest only |
| GET | `/forgot-password` | Form lupa password | Guest only |
| POST | `/forgot-password` | Kirim link reset | Guest only |
| GET | `/reset-password/{token}` | Form reset password | Guest only |
| POST | `/reset-password` | Proses reset | Guest only |
| POST | `/logout` | Logout | Auth |

### Route User (`/user/...`, middleware: `auth`)

| Method | Path | Controller Method | Fungsi | Body / Parameter |
|--------|------|-------------------|--------|-----------------|
| GET | `/user/dashboard` | `BookingController@dashboard` | Dashboard user | — |
| GET | `/user/kamar` | `RoomController@indexUser` | Daftar kamar | `?lantai=` |
| GET | `/user/kamar/{room}` | `RoomController@showUser` | Detail kamar | — |
| GET | `/user/booking/{room}` | `BookingController@create` | Form booking | — |
| POST | `/user/booking` | `BookingController@store` | **[JSON]** Submit booking | `room_id`, `check_in_date`, `payment_method`, `ewallet_phone?` |
| GET | `/user/booking/{booking}` | `BookingController@show` | Detail booking | — |
| GET | `/user/riwayat` | `BookingController@history` | Riwayat booking | — |
| POST | `/user/pembayaran/{booking}` | `PaymentController@store` | Upload bukti bayar | `payment_method`, `proof` (image max 2MB) |
| GET | `/user/profil` | `ProfileController@edit` | Form edit profil | — |
| PATCH | `/user/profil` | `ProfileController@update` | Update profil | `name`, `phone`, `address`, `birth_date`, `avatar?`, `password?` |

**Contoh Response JSON — POST `/user/booking` sukses:**
```json
{
  "success": true,
  "nama": "Siti Rahayu",
  "telepon": "08123456789",
  "kamar": "Kamar A1",
  "lantai": 1,
  "tanggal_masuk": "Senin, 01 September 2026",
  "metode": "qris",
  "booking_code": "GDN-12345678",
  "tanggal_transaksi": "Selasa, 22 Juli 2026",
  "sisa": 800000
}
```

**Contoh Response JSON — POST `/user/booking` gagal:**
```json
{
  "success": false,
  "message": "Kamar sudah terisi."
}
```

### Route Admin (`/admin/...`, middleware: `auth`, `admin`)

| Method | Path | Controller Method | Fungsi | Body / Parameter |
|--------|------|-------------------|--------|-----------------|
| GET | `/admin/dashboard` | `Admin\DashboardController@index` | Dashboard + statistik | — |
| GET | `/admin/kamar` | `Admin\RoomController@index` | Daftar kamar (paginate 20) | — |
| GET | `/admin/kamar/create` | `Admin\RoomController@create` | Form tambah kamar | — |
| POST | `/admin/kamar` | `Admin\RoomController@store` | Simpan kamar baru | `name`, `type`, `floor`, `price`, `description?`, `photos[]?`, `facilities[]?` |
| GET | `/admin/kamar/{kamar}/edit` | `Admin\RoomController@edit` | Form edit kamar | — |
| PUT | `/admin/kamar/{kamar}` | `Admin\RoomController@update` | Update data kamar | `name`, `type`, `floor`, `price`, `description?`, `is_available?`, `facilities[]?` |
| DELETE | `/admin/kamar/{kamar}` | `Admin\RoomController@destroy` | Hapus kamar + semua foto | — |
| POST | `/admin/kamar/{kamar}/foto` | `Admin\RoomController@uploadPhoto` | Upload foto tambahan | `photo` (image max 2MB) |
| DELETE | `/admin/foto/{photo}` | `Admin\RoomController@deletePhoto` | Hapus 1 foto | — |
| PATCH | `/admin/kamar/{kamar}/toggle` | `Admin\RoomController@toggleAvailability` | Toggle ketersediaan | — |
| GET | `/admin/booking/{booking}` | `Admin\BookingController@show` | Detail booking | — |
| PATCH | `/admin/booking/{booking}/status` | `Admin\BookingController@updateStatus` | Update status booking | `status`, `cancel_reason?` |
| GET | `/admin/pembayaran` | `Admin\PaymentController@index` | Daftar pembayaran | `?status=` |
| PATCH | `/admin/pembayaran/{payment}/verify` | `Admin\PaymentController@verify` | Verifikasi pembayaran | — |
| PATCH | `/admin/pembayaran/{payment}/reject` | `Admin\PaymentController@reject` | Tolak pembayaran | `reject_notes?` |
| GET | `/admin/testimoni` | `Admin\TestimonialController@index` | Daftar testimoni | — |
| GET | `/admin/testimoni/create` | `Admin\TestimonialController@create` | Form tambah testimoni | — |
| POST | `/admin/testimoni` | `Admin\TestimonialController@store` | Simpan testimoni | `name`, `label?`, `rating`, `content` |
| GET | `/admin/testimoni/{testimonial}/edit` | `Admin\TestimonialController@edit` | Form edit testimoni | — |
| PUT | `/admin/testimoni/{testimonial}` | `Admin\TestimonialController@update` | Update testimoni | `name`, `label?`, `rating`, `content`, `status` |
| DELETE | `/admin/testimoni/{testimonial}` | `Admin\TestimonialController@destroy` | Hapus testimoni | — |

---

## 8. Fitur & Logika Bisnis Utama

### 8.1 Manajemen Kamar

**Fitur:**
- Admin dapat menambah, mengedit, dan menghapus kamar
- Tipe kamar: `standard`, `deluxe`, `vip`; Lantai: `1` atau `2`
- Foto pertama yang diupload otomatis menjadi **foto utama** (`is_primary = true`)
- Jika foto utama dihapus, foto berikutnya otomatis dijadikan foto utama
- Toggle ketersediaan kamar secara manual oleh admin
- Kamar dapat dihubungkan ke banyak fasilitas (many-to-many via `room_facility`)

**Status Kamar:**

| `is_available` | Artinya |
|----------------|---------|
| `true` | Kamar tersedia, tampil di halaman publik |
| `false` | Kamar tidak tersedia (dihuni atau dikunci manual) |

**Aturan Tampil:**  
Kamar hanya tampil di halaman publik jika `is_available = true` **DAN** tidak ada booking dengan status `pending`, `confirmed`, atau `active` (scope `noActiveBooking` di model Room).

### 8.2 Proses Booking

**Validasi Form:**
- `room_id`: wajib, harus ada di tabel `rooms`
- `check_in_date`: wajib, harus hari ini atau yang akan datang
- `payment_method`: wajib, salah satu dari `qris`, `dana`, `ovo`, `bca`
- `ewallet_phone`: wajib jika metode `dana` atau `ovo`

**Kode Booking:** Format `GDN-XXXXXXXX` (8 digit angka acak dari `mt_rand`)

**Uang Muka (DP):** Tetap **Rp 250.000** untuk semua booking (`Booking::DP_AMOUNT = 250_000`)

**State Machine Status Booking:**

| Status | Deskripsi | Efek ke Kamar |
|--------|-----------|---------------|
| `pending` | Booking dibuat, menunggu verifikasi DP | Kamar masih tersedia (belum dikunci) |
| `confirmed` | Pembayaran DP terverifikasi admin | Kamar `is_available = false` |
| `active` | Penghuni aktif menghuni | Kamar tetap tidak tersedia |
| `completed` | Masa sewa selesai | Kamar `is_available = true` kembali |
| `cancelled` | Dibatalkan / DP ditolak | Kamar `is_available = true` kembali |

### 8.3 Manajemen Pembayaran

**Status Pembayaran:**

| Status | Deskripsi | Efek ke Booking |
|--------|-----------|-----------------|
| `pending` | Menunggu verifikasi admin | Booking tetap `pending` |
| `verified` | Diterima admin | Booking → `confirmed`, kamar dikunci |
| `rejected` | Ditolak admin | Booking → `cancelled` |

**Aturan:**
- Pembayaran manual (tidak ada payment gateway otomatis)
- File bukti transfer: gambar, max 2MB, disimpan di `storage/app/public/payments/`
- Verifikasi dicatat dengan `verified_at` (timestamp) dan `verified_by` (user_id admin) menggunakan DB transaction
- Penolakan langsung membatalkan booking dengan alasan dari `reject_notes`

### 8.4 Manajemen Testimoni

**Desain:** Testimoni dikelola **sepenuhnya oleh admin** — bukan disubmit oleh user (refaktor Juli 2026).

**Aturan:**
- Admin mengisi nama, label (mis. "Mahasiswi"), rating (1-5), isi testimoni
- Status testimoni baru yang dibuat admin langsung `approved`
- Hanya testimoni `approved` yang tampil di beranda publik
- Kolom `user_id` dan `booking_id` opsional (bisa null untuk testimoni manual)
- Accessor `displayName`: prioritas nama manual → nama akun user → `'Penghuni'`

### 8.5 Manajemen Profil User

- Edit: nama, nomor telepon, alamat, tanggal lahir
- Upload avatar (disimpan di `storage/app/public/avatars/`)
- Ganti password (memerlukan password lama)
- **Email read-only** dari form ini
- Avatar lama dihapus dari storage sebelum menyimpan yang baru

### 8.6 Dashboard Admin — Statistik Real-Time

| Metrik | Cara Hitung |
|--------|-------------|
| Total kamar | `Room::count()` |
| Kamar tersedia | `Room::where('is_available', true)->count()` |
| Kamar terisi | `Room::where('is_available', false)->count()` |
| Total booking | `Booking::count()` |
| Booking pending | `Booking::where('status', 'pending')->count()` |
| Total user | `User::where('role', 'user')->count()` |
| Pembayaran pending | `Payment::where('status', 'pending')->count()` |
| Pendapatan bulan ini | Sum `amount` dari payments `verified` di bulan & tahun berjalan |
| Verifikasi bulan ini | Count payments `verified` di bulan & tahun berjalan |

---

## 9. Integrasi Eksternal

### Payment Gateway

**Tidak ada integrasi payment gateway otomatis.** Sistem menggunakan **manual transfer**:
1. User memilih metode: QRIS, Dana, OVO, atau BCA
2. User transfer ke rekening/nomor pemilik kos secara mandiri di luar sistem
3. User mengupload foto bukti transfer ke sistem
4. Admin memverifikasi secara manual

> **Catatan:** Informasi rekening/nomor tujuan transfer **tidak ditemukan di codebase** — kemungkinan ditampilkan secara statis di template Blade atau dikomunikasikan di luar sistem.

### Hosting: InfinityFree

| Aspek | Detail |
|-------|--------|
| Platform | InfinityFree (shared hosting gratis) |
| FTP Server | `ftpupload.net:21` |
| MySQL Host | `sql306.infinityfree.com` (sesuai panel) |
| PHP Support | 8.2 (kompatibel Laravel 12) |
| Batasan | Tidak ada SSH, tidak ada cron job, tidak ada `exec()`/`shell_exec()`, bandwidth terbatas, max file upload 10MB via FTP |

### Email

Driver mail default adalah `log` — email hanya ditulis ke file log, tidak benar-benar dikirim. Tidak ada integrasi SMTP aktif (Mailgun, SendGrid, dsb.) di codebase.

### Storage File

File upload disimpan di `storage/app/public/` dan diakses via symlink `public/storage/`. Di production InfinityFree, symlink dibuat manual via script PHP helper (tidak bisa `php artisan storage:link` karena tidak ada SSH).

---

## 10. Dependency & Package

### PHP Dependencies (`composer.json`)

| Package | Versi | Fungsi |
|---------|-------|--------|
| `php` | ^8.2 | Runtime PHP |
| `laravel/framework` | ^12.0 | Core Laravel: routing, ORM, session, middleware, dsb |
| `laravel/tinker` | ^2.10.1 | REPL interaktif untuk eksplorasi aplikasi di CLI |
| `fakerphp/faker` | ^1.23 (dev) | Generate data dummy untuk factory/seeder |
| `laravel/breeze` | ^2.4 (dev) | Starter kit autentikasi |
| `laravel/pail` | ^1.2.2 (dev) | Log viewer real-time di terminal |
| `laravel/pint` | ^1.13 (dev) | Code formatter PHP |
| `laravel/sail` | ^1.41 (dev) | Docker environment untuk development lokal |
| `mockery/mockery` | ^1.6 (dev) | Mocking library untuk unit testing |
| `nunomaduro/collision` | ^8.6 (dev) | Error reporting yang lebih informatif di CLI |
| `phpunit/phpunit` | ^11.5.3 (dev) | Framework unit testing |

### JavaScript Dependencies (`package.json`)

| Package | Versi | Fungsi |
|---------|-------|--------|
| `alpinejs` | ^3.4.2 | Framework JS ringan: reaktivitas UI, modal, dropdown |
| `axios` | ^1.7.4 | HTTP client untuk AJAX request |
| `tailwindcss` | ^3.1.0 | Utility-first CSS framework |
| `@tailwindcss/forms` | ^0.5.2 | Plugin Tailwind: reset style elemen form |
| `@tailwindcss/vite` | ^4.0.0 | Integrasi Tailwind dengan Vite |
| `vite` | ^6.0.11 | Build tool modern: bundling CSS/JS, HMR |
| `laravel-vite-plugin` | ^1.2.0 | Jembatan antara Vite dan Laravel (asset manifests) |
| `autoprefixer` | ^10.4.2 | PostCSS plugin: tambah vendor prefix CSS otomatis |
| `postcss` | ^8.4.31 | CSS processor pipeline |
| `concurrently` | ^9.0.1 | Jalankan beberapa command sekaligus (dev mode) |

---

## 11. Cara Menjalankan Project

### Prasyarat

- PHP >= 8.2
- Composer
- Node.js >= 18 & NPM
- Git
- (Opsional) Laragon / XAMPP / WAMP untuk lingkungan lokal

### Langkah Setup Lokal

```bash
# 1. Clone repository
git clone https://github.com/regitadwicahyani27-dot/kost_Gardenia.git
cd kost_Gardenia

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Buat file SQLite (database lokal default)
# Linux/Mac:
touch database/database.sqlite
# Windows PowerShell:
New-Item -ItemType File database/database.sqlite

# 7. Jalankan migrasi database
php artisan migrate

# 8. Buat akun admin
php artisan db:seed --class=AdminSeeder

# 9. Buat symlink storage untuk file upload
php artisan storage:link

# 10. Build aset frontend (untuk production)
npm run build
# ATAU untuk development dengan hot reload:
npm run dev

# 11. Jalankan server development
php artisan serve
```

Akses di: **http://localhost:8000**

### Kredensial Admin Default

| Field | Value |
|-------|-------|
| Email | `admin@gardenia.com` |
| Password | `admin123` |

### Menjalankan Semua Service Sekaligus (Development)

```bash
composer run dev
```

Menjalankan secara bersamaan:
- `php artisan serve` — web server Laravel
- `php artisan queue:listen --tries=1` — queue worker
- `php artisan pail --timeout=0` — log viewer real-time
- `npm run dev` — Vite HMR server

### Deploy ke Production (InfinityFree)

Lihat panduan lengkap di `deploy-infinityfree/PANDUAN-DEPLOY.md`.

Ringkasan:
1. Buat akun hosting dan database MySQL di InfinityFree
2. Import `database/gardenia_kos.sql` via phpMyAdmin
3. Edit `.env` dengan credentials production (DB host, nama DB, username, password, APP_URL)
4. Build frontend: `npm run build`
5. Upload semua file ke `htdocs/` via FileZilla (FTP ke `ftpupload.net:21`)
6. Copy `deploy-infinityfree/.htaccess.root` ke root `htdocs/` sebagai `.htaccess`
7. Set permission `storage/` dan `bootstrap/cache/` ke `777` (recursive)
8. Buat symlink storage via script PHP helper (jalankan di browser, lalu hapus)

---

## 12. Kendala / Catatan Teknis

| # | Kategori | Deskripsi | Lokasi |
|---|----------|-----------|--------|
| 1 | **TODO** | `payment_type` punya opsi `monthly` dan `full` tapi sistem hanya menggunakan `dp`. Pembayaran cicilan bulanan dan pelunasan penuh belum diimplementasikan. | `Payment.php`, migration payments |
| 2 | **TODO** | `duration_months` selalu hardcoded `1`. Belum ada fitur booking multi-bulan. | `BookingController.php` baris 62 |
| 3 | **Keamanan** | Kode booking menggunakan `mt_rand()` yang tidak cryptographically secure. Berpotensi tabrakan meski ada constraint UNIQUE di DB. | `BookingController.php` baris 54 |
| 4 | **Data Tidak Ada** | Nomor rekening/dompet digital tujuan transfer tidak ditemukan di codebase. | — |
| 5 | **Email Tidak Aktif** | Mail driver `log` — tidak ada notifikasi email untuk booking, verifikasi pembayaran, dsb. | `.env.example` |
| 6 | **Batasan Hosting** | InfinityFree tidak mendukung cron job. Otomatisasi perubahan status booking harus dilakukan manual oleh admin. | `PANDUAN-DEPLOY.md` |
| 7 | **Batasan Hosting** | InfinityFree tidak mendukung SSH — perintah Artisan tidak bisa dijalankan di production. Migrasi harus via SQL manual. | `PANDUAN-DEPLOY.md` |
| 8 | **Breaking Change** | Sistem testimoni direfaktor Juli 2026 dari pengajuan user menjadi manajemen penuh admin. | Migration `2026_07_08_091431...` |
| 9 | **Race Condition** | Tidak ada mekanisme locking kamar saat booking dibuat. Dua user bisa memesan kamar yang sama bersamaan sebelum admin verifikasi salah satu. | `BookingController@store` |
| 10 | **Typo** | Komentar "ponytail:" di `Room.php` baris 37 kemungkinan artefak dari editor AI. | `app/Models/Room.php` baris 37 |
| 11 | **Fasilitas Ganda** | Dua sistem fasilitas yang tidak saling terhubung: (1) `RoomFacilities` statis untuk halaman publik; (2) tabel `facilities` yang bisa dikonfigurasi per kamar oleh admin. | `app/Support/RoomFacilities.php`, `app/Models/Facility.php` |
| 12 | **Fitur Tidak Ada** | Tidak ada fitur hapus akun user. | — |
| 13 | **Testing** | Tidak ada test case custom untuk fitur aplikasi — hanya boilerplate default Laravel. | `tests/` |