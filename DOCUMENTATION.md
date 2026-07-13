# Dokumentasi Teknis — Kos Putri Gardenia

---

## Daftar Isi

1. [Ringkasan Project](#1-ringkasan-project)
2. [Tech Stack](#2-tech-stack)
3. [Struktur Folder & File](#3-struktur-folder--file)
4. [Arsitektur & Alur Data](#4-arsitektur--alur-data)
5. [Skema Database](#5-skema-database)
6. [Daftar Halaman & Navigasi (per Role)](#6-daftar-halaman--navigasi-per-role)
7. [Daftar API Endpoint](#7-daftar-api-endpoint)
8. [Fitur & Logika Bisnis Utama](#8-fitur--logika-bisnis-utama)
9. [Integrasi Eksternal](#9-integrasi-eksternal)
10. [Dependency & Package](#10-dependency--package)
11. [Cara Menjalankan Project](#11-cara-menjalankan-project)
12. [Kendala/Catatan Teknis](#12-kendalacatatan-teknis)

---

## 1. Ringkasan Project

### Identitas

| Aspek | Keterangan |
|---|---|
| **Nama Project** | Kos Putri Gardenia |
| **Tujuan** | Sistem informasi dan booking kamar kos putri secara online |
| **Target Pengguna** | Publik (pengunjung tanpa login), User (calon penghuni/penghuni terdaftar), Admin (pengelola kos) |
| **Nama Kode** | `gardenia-kosla122` |

### Deskripsi Singkat Alur Bisnis Utama

1. **Pengunjung** mengakses halaman publik untuk melihat daftar kamar yang tersedia, detail kamar beserta fasilitasnya, halaman tentang, dan testimoni penghuni.
2. **Pengunjung mendaftar** akun melalui modal popup registrasi (bukan halaman terpisah) dengan mengisi data pribadi (nama, tanggal lahir, no HP, email, alamat).
3. **User yang sudah login** dapat memilih kamar, lalu membuat **booking** dengan memilih tanggal check-in dan metode pembayaran (QRIS, DANA, OVO, atau BCA).
4. Saat booking dibuat, sistem otomatis membuat **pembayaran DP** sebesar Rp250.000 dengan status `pending`.
5. **Admin** memverifikasi pembayaran DP melalui dashboard. Jika diverifikasi:
   - Status pembayaran → `verified`
   - Status booking → `confirmed`
   - Kamar ditandai tidak tersedia (`is_available = false`)
6. Jika admin **menolak** pembayaran, booking otomatis dibatalkan.
7. Admin dapat mengubah status booking ke `active`, `completed`, atau `cancelled`. Saat booking `cancelled` atau `completed`, kamar dibebaskan kembali menjadi tersedia.
8. User dapat melihat riwayat booking dan detail pembayaran melalui dashboard.
9. **Testimoni** dikelola langsung oleh admin (bukan diajukan oleh user).

---

## 2. Tech Stack

### Bahasa Pemrograman & Framework

| Komponen | Teknologi | Versi |
|---|---|---|
| Bahasa Backend | PHP | ^8.2 (platform 8.3.0) |
| Framework Backend | Laravel | ^12.0 |
| Bahasa Frontend | HTML + Blade Templating, JavaScript | - |
| CSS Framework | TailwindCSS | ^3.1.0 |
| JS Reactivity | Alpine.js | ^3.4.2 |
| Build Tool | Vite | ^6.0.11 |
| HTTP Client (JS) | Axios | ^1.7.4 |

### Database

| Aspek | Keterangan |
|---|---|
| **Lokal (development)** | MySQL (via Laragon) — database `gardenia_kos` |
| **Default .env.example** | SQLite |
| **Hosting (production)** | MySQL di InfinityFree (`sql307.infinityfree.com`) |

### Library/Package Penting

| Package | Fungsi |
|---|---|
| `laravel/breeze` | Scaffolding autentikasi (login, register, reset password, email verification) |
| `laravel/tinker` | REPL interaktif untuk debugging |
| `laravel/sail` | Docker development environment (tersedia di dev-dependencies) |
| `@tailwindcss/forms` | Plugin TailwindCSS untuk styling form elements |
| `laravel-vite-plugin` | Integrasi Vite dengan Laravel |
| `alpinejs` | Framework JavaScript ringan untuk interaktivitas frontend |
| `axios` | HTTP client untuk AJAX requests |

### Tools Deployment/Hosting

| Tool | Keterangan |
|---|---|
| **Laragon** | Local development server (Windows) |
| **InfinityFree** | Hosting production (konfigurasi ditemukan di `.env_hosting`) |
| **Laravel Sail** | Docker environment (tersedia sebagai dev-dependency, opsional) |
| **Vite** | Build tool untuk asset bundling (CSS/JS) |

---

## 3. Struktur Folder & File

### Tree Struktur (Maks 3 Level)

```
gardenia-kosla122/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Controller khusus admin
│   │   │   ├── Auth/               # Controller autentikasi (Breeze)
│   │   │   ├── BookingController.php
│   │   │   ├── HomeController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── ProfileController.php
│   │   │   └── RoomController.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php  # Middleware cek role admin
│   │   └── Requests/
│   │       ├── Auth/
│   │       │   └── LoginRequest.php
│   │       └── ProfileUpdateRequest.php
│   ├── Models/                     # Eloquent Models
│   │   ├── Booking.php
│   │   ├── Facility.php
│   │   ├── Payment.php
│   │   ├── Room.php
│   │   ├── RoomPhoto.php
│   │   ├── Testimonial.php
│   │   └── User.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Support/
│       ├── Icons.php               # Helper SVG icons
│       └── RoomFacilities.php      # Daftar fasilitas standar kamar
├── bootstrap/                      # Bootstrap framework Laravel
├── config/                         # File konfigurasi Laravel
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── filesystems.php
│   ├── mail.php
│   ├── session.php
│   └── ...
├── database/
│   ├── factories/                  # Model factories (kosong)
│   ├── migrations/                 # 15 file migrasi database
│   ├── seeders/
│   │   ├── AdminSeeder.php         # Seeder akun admin default
│   │   └── DatabaseSeeder.php
│   └── database.sqlite             # File SQLite (default)
├── public/
│   ├── build/                      # Compiled Vite assets
│   ├── images/                     # Gambar statis
│   ├── storage -> ../storage/app/public  # Symlink storage
│   ├── index.php                   # Entry point aplikasi
│   └── .htaccess
├── resources/
│   ├── css/
│   │   └── app.css                 # Entry point CSS (Tailwind directives)
│   ├── js/
│   │   ├── app.js                  # Entry point JS (Alpine.js)
│   │   └── bootstrap.js            # Axios setup
│   └── views/
│       ├── admin/                  # View halaman admin
│       │   ├── dashboard.blade.php
│       │   ├── bookings/
│       │   ├── payments/
│       │   ├── rooms/
│       │   └── testimonials/
│       ├── components/             # Blade components
│       ├── layouts/                # Layout templates
│       │   ├── admin.blade.php
│       │   ├── app.blade.php       # Layout publik
│       │   └── user.blade.php
│       ├── partials/               # Partial views
│       ├── profile/
│       ├── rooms/                  # Halaman kamar publik
│       ├── user/                   # View halaman user
│       │   ├── dashboard.blade.php
│       │   ├── booking/
│       │   └── rooms/
│       ├── home.blade.php          # Halaman beranda
│       └── tentang.blade.php       # Halaman tentang
├── routes/
│   ├── web.php                     # Route utama (publik, user, admin)
│   ├── auth.php                    # Route autentikasi (Breeze)
│   └── console.php
├── storage/                        # File storage (uploads, logs, cache)
├── tests/                          # Test files
├── .env.example                    # Template environment variables
├── .env_hosting                    # Config khusus hosting InfinityFree
├── composer.json                   # PHP dependencies
├── package.json                    # Node.js dependencies
├── tailwind.config.js              # Konfigurasi TailwindCSS
├── vite.config.js                  # Konfigurasi Vite
└── phpunit.xml                     # Konfigurasi PHPUnit
```

### File Konfigurasi Penting & Variabel Environment

| File | Fungsi |
|---|---|
| `.env.example` | Template variabel environment untuk setup lokal |
| `.env_hosting` | Variabel environment untuk deployment ke InfinityFree |
| `composer.json` | Definisi dependency PHP |
| `package.json` | Definisi dependency Node.js |
| `tailwind.config.js` | Kustomisasi TailwindCSS (font: Figtree) |
| `vite.config.js` | Konfigurasi build tool Vite |
| `phpunit.xml` | Konfigurasi testing |

### Variabel Environment yang Dibutuhkan

| Variabel | Deskripsi |
|---|---|
| `APP_NAME` | Nama aplikasi |
| `APP_ENV` | Environment (`local` / `production`) |
| `APP_KEY` | Application encryption key |
| `APP_DEBUG` | Mode debug (`true` / `false`) |
| `APP_URL` | URL dasar aplikasi |
| `DB_CONNECTION` | Driver database (`mysql` / `sqlite`) |
| `DB_HOST` | Host database |
| `DB_PORT` | Port database |
| `DB_DATABASE` | Nama database |
| `DB_USERNAME` | Username database |
| `DB_PASSWORD` | Password database |
| `SESSION_DRIVER` | Driver session (`file` / `database`) |
| `FILESYSTEM_DISK` | Disk penyimpanan file (`local`) |
| `QUEUE_CONNECTION` | Driver queue (`database`) |
| `CACHE_STORE` | Driver cache (`database`) |
| `MAIL_MAILER` | Driver email (`log` di development) |
| `VITE_APP_NAME` | Nama aplikasi untuk frontend |

---

## 4. Arsitektur & Alur Data

### Pola Arsitektur

Project ini menggunakan pola arsitektur **MVC (Model-View-Controller)** bawaan Laravel:

- **Model** (`app/Models/`): Mendefinisikan entitas bisnis dan relasinya (Eloquent ORM)
- **View** (`resources/views/`): Template Blade untuk rendering halaman HTML
- **Controller** (`app/Http/Controllers/`): Menangani logika request-response

Tambahan komponen arsitektur:
- **Middleware** (`app/Http/Middleware/`): Filter request (autentikasi, otorisasi admin)
- **Form Request** (`app/Http/Requests/`): Validasi input terpusat
- **Support** (`app/Support/`): Helper class untuk data statis (fasilitas, ikon)

### Komunikasi Frontend-Backend

| Aspek | Keterangan |
|---|---|
| **Metode** | Server-Side Rendering (SSR) dengan Blade templating |
| **AJAX** | Digunakan untuk proses booking (via Axios + AJAX request) |
| **Interaktivitas** | Alpine.js untuk komponen interaktif di frontend (modal, dropdown, toggle) |
| **Asset Bundling** | Vite mengompilasi CSS (Tailwind) dan JS (Alpine.js + Axios) |

Alur request secara umum:
```
Browser → Route (web.php/auth.php)
       → Middleware (auth, admin)
       → Controller
       → Model (Eloquent) ↔ Database
       → View (Blade) → Response HTML
```

Untuk AJAX (contoh: booking store):
```
Browser (Axios POST) → Route → Controller
                    → Model → Database
                    → JSON Response → JavaScript handler
```

### Mekanisme Autentikasi & Otorisasi

| Aspek | Keterangan |
|---|---|
| **Paket Auth** | Laravel Breeze (Blade stack) |
| **Metode Auth** | Session-based authentication |
| **Session Driver** | File (lokal) / Database (hosting) |
| **Password Hashing** | Bcrypt (12 rounds) |
| **Role System** | Kolom `role` di tabel `users` (`user` / `admin`) |
| **Middleware Admin** | `AdminMiddleware` — mengecek `auth()->user()->isAdmin()` |
| **Guard** | Default Laravel guard (`web`) |

**Alur Otorisasi:**
1. Route tanpa middleware → **Publik** (siapa saja bisa akses)
2. Route dengan middleware `auth` → **User yang sudah login**
3. Route dengan middleware `['auth', 'admin']` → **Admin saja**
4. Pengecekan kepemilikan resource dilakukan di controller (mis. `$booking->user_id !== auth()->id()` → `abort(403)`)

**Alur Login/Register:**
- Registrasi menggunakan modal popup (bukan halaman terpisah). `RegisteredUserController::create()` me-redirect ke home dengan flash session `open_auth=register`.
- Login juga melalui modal popup di halaman publik.
- Setelah login, route `/dashboard` otomatis me-redirect berdasarkan role:
  - Admin → `/admin/dashboard`
  - User → `/user/dashboard`

---

## 5. Skema Database

### Daftar Tabel & Kolom

#### Tabel `users`

| Kolom | Tipe Data | Constraint |
|---|---|---|
| `id` | bigint (unsigned) | PRIMARY KEY, AUTO_INCREMENT |
| `name` | varchar(255) | NOT NULL |
| `email` | varchar(255) | NOT NULL, UNIQUE |
| `phone` | varchar(255) | NULLABLE |
| `address` | text | NULLABLE |
| `birth_date` | date | NULLABLE |
| `role` | varchar(255) | DEFAULT 'user' |
| `avatar` | varchar(255) | NULLABLE |
| `email_verified_at` | timestamp | NULLABLE |
| `password` | varchar(255) | NOT NULL |
| `remember_token` | varchar(100) | NULLABLE |
| `created_at` | timestamp | NULLABLE |
| `updated_at` | timestamp | NULLABLE |

#### Tabel `facilities`

| Kolom | Tipe Data | Constraint |
|---|---|---|
| `id` | bigint (unsigned) | PRIMARY KEY, AUTO_INCREMENT |
| `name` | varchar(255) | NOT NULL |
| `icon` | varchar(255) | NULLABLE |
| `created_at` | timestamp | NULLABLE |
| `updated_at` | timestamp | NULLABLE |

#### Tabel `rooms`

| Kolom | Tipe Data | Constraint |
|---|---|---|
| `id` | bigint (unsigned) | PRIMARY KEY, AUTO_INCREMENT |
| `name` | varchar(255) | NOT NULL |
| `type` | enum('standard','deluxe','vip') | DEFAULT 'standard' |
| `price` | decimal(15,2) | DEFAULT 0 |
| `description` | text | NULLABLE |
| `is_available` | boolean | DEFAULT true |
| `floor` | integer | DEFAULT 1 |
| `created_at` | timestamp | NULLABLE |
| `updated_at` | timestamp | NULLABLE |

#### Tabel `room_photos`

| Kolom | Tipe Data | Constraint |
|---|---|---|
| `id` | bigint (unsigned) | PRIMARY KEY, AUTO_INCREMENT |
| `room_id` | bigint (unsigned) | FOREIGN KEY → rooms(id) ON DELETE CASCADE |
| `photo_path` | varchar(255) | NOT NULL |
| `is_primary` | boolean | DEFAULT false |
| `created_at` | timestamp | NULLABLE |
| `updated_at` | timestamp | NULLABLE |

#### Tabel `room_facility` (Pivot)

| Kolom | Tipe Data | Constraint |
|---|---|---|
| `room_id` | bigint (unsigned) | FOREIGN KEY → rooms(id) ON DELETE CASCADE |
| `facility_id` | bigint (unsigned) | FOREIGN KEY → facilities(id) ON DELETE CASCADE |
| — | — | PRIMARY KEY (room_id, facility_id) |

#### Tabel `bookings`

| Kolom | Tipe Data | Constraint |
|---|---|---|
| `id` | bigint (unsigned) | PRIMARY KEY, AUTO_INCREMENT |
| `user_id` | bigint (unsigned) | FOREIGN KEY → users(id) ON DELETE CASCADE |
| `room_id` | bigint (unsigned) | FOREIGN KEY → rooms(id) ON DELETE CASCADE |
| `booking_code` | varchar(255) | NOT NULL, UNIQUE |
| `check_in_date` | date | NOT NULL |
| `duration_months` | integer | DEFAULT 1 |
| `total_price` | decimal(15,2) | DEFAULT 0 |
| `dp_amount` | decimal(15,2) | DEFAULT 0 |
| `status` | enum('pending','confirmed','active','cancelled','completed') | DEFAULT 'pending' |
| `notes` | text | NULLABLE |
| `cancelled_reason` | text | NULLABLE |
| `cancelled_by` | varchar(255) | NULLABLE |
| `created_at` | timestamp | NULLABLE |
| `updated_at` | timestamp | NULLABLE |

#### Tabel `payments`

| Kolom | Tipe Data | Constraint |
|---|---|---|
| `id` | bigint (unsigned) | PRIMARY KEY, AUTO_INCREMENT |
| `booking_id` | bigint (unsigned) | FOREIGN KEY → bookings(id) ON DELETE CASCADE |
| `user_id` | bigint (unsigned) | FOREIGN KEY → users(id) ON DELETE CASCADE |
| `amount` | decimal(15,2) | DEFAULT 0 |
| `payment_method` | enum('qris','dana','ovo','bca') | NOT NULL |
| `payment_type` | enum('dp','monthly','full') | DEFAULT 'dp' |
| `proof_path` | varchar(255) | NULLABLE |
| `status` | enum('pending','verified','rejected') | DEFAULT 'pending' |
| `verified_at` | timestamp | NULLABLE |
| `verified_by` | bigint (unsigned) | NULLABLE, FOREIGN KEY → users(id) |
| `notes` | text | NULLABLE |
| `created_at` | timestamp | NULLABLE |
| `updated_at` | timestamp | NULLABLE |

#### Tabel `testimonials`

| Kolom | Tipe Data | Constraint |
|---|---|---|
| `id` | bigint (unsigned) | PRIMARY KEY, AUTO_INCREMENT |
| `user_id` | bigint (unsigned) | NULLABLE, FOREIGN KEY → users(id) ON DELETE SET NULL |
| `name` | varchar(255) | NULLABLE |
| `label` | varchar(255) | NULLABLE |
| `booking_id` | bigint (unsigned) | NULLABLE, FOREIGN KEY → bookings(id) ON DELETE SET NULL |
| `rating` | tinyint | DEFAULT 5 |
| `content` | text | NOT NULL |
| `status` | enum('pending','approved','rejected') | DEFAULT 'pending' |
| `created_at` | timestamp | NULLABLE |
| `updated_at` | timestamp | NULLABLE |

#### Tabel `sessions`

| Kolom | Tipe Data | Constraint |
|---|---|---|
| `id` | varchar(255) | PRIMARY KEY |
| `user_id` | bigint (unsigned) | NULLABLE, INDEX |
| `ip_address` | varchar(45) | NULLABLE |
| `user_agent` | text | NULLABLE |
| `payload` | longtext | NOT NULL |
| `last_activity` | integer | INDEX |

#### Tabel `password_reset_tokens`

| Kolom | Tipe Data | Constraint |
|---|---|---|
| `email` | varchar(255) | PRIMARY KEY |
| `token` | varchar(255) | NOT NULL |
| `created_at` | timestamp | NULLABLE |

#### Tabel `cache` (Laravel cache)

> Dibuat oleh migrasi `0001_01_01_000001_create_cache_table.php` — tabel standar Laravel untuk cache driver database.

#### Tabel `jobs`, `job_batches`, `failed_jobs` (Laravel queue)

> Dibuat oleh migrasi `0001_01_01_000002_create_jobs_table.php` — tabel standar Laravel untuk queue driver database.

### Relasi Antar Tabel

```
users ||--o{ bookings      : "one-to-many (user memiliki banyak booking)"
users ||--o{ payments       : "one-to-many (user memiliki banyak pembayaran)"
users ||--o{ testimonials   : "one-to-many (opsional, user_id nullable)"
rooms ||--o{ bookings      : "one-to-many (kamar memiliki banyak booking)"
rooms ||--o{ room_photos   : "one-to-many (kamar memiliki banyak foto)"
rooms }o--o{ facilities    : "many-to-many (via tabel pivot room_facility)"
bookings ||--o{ payments   : "one-to-many (booking memiliki banyak pembayaran)"
bookings ||--o| testimonials : "one-to-one (booking memiliki satu testimoni, opsional)"
users ||--o{ payments (verified_by) : "one-to-many (admin yang memverifikasi)"
```

### Notasi ERD

```
users {
    id bigint PK
    name varchar
    email varchar UNIQUE
    phone varchar NULL
    address text NULL
    birth_date date NULL
    role varchar DEFAULT 'user'
    avatar varchar NULL
    email_verified_at timestamp NULL
    password varchar
    remember_token varchar NULL
    created_at timestamp
    updated_at timestamp
}

rooms {
    id bigint PK
    name varchar
    type enum('standard','deluxe','vip')
    price decimal(15,2)
    description text NULL
    is_available boolean DEFAULT true
    floor integer DEFAULT 1
    created_at timestamp
    updated_at timestamp
}

facilities {
    id bigint PK
    name varchar
    icon varchar NULL
    created_at timestamp
    updated_at timestamp
}

room_facility {
    room_id bigint FK(rooms.id)
    facility_id bigint FK(facilities.id)
    PK(room_id, facility_id)
}

room_photos {
    id bigint PK
    room_id bigint FK(rooms.id)
    photo_path varchar
    is_primary boolean DEFAULT false
    created_at timestamp
    updated_at timestamp
}

bookings {
    id bigint PK
    user_id bigint FK(users.id)
    room_id bigint FK(rooms.id)
    booking_code varchar UNIQUE
    check_in_date date
    duration_months integer DEFAULT 1
    total_price decimal(15,2)
    dp_amount decimal(15,2)
    status enum('pending','confirmed','active','cancelled','completed')
    notes text NULL
    cancelled_reason text NULL
    cancelled_by varchar NULL
    created_at timestamp
    updated_at timestamp
}

payments {
    id bigint PK
    booking_id bigint FK(bookings.id)
    user_id bigint FK(users.id)
    amount decimal(15,2)
    payment_method enum('qris','dana','ovo','bca')
    payment_type enum('dp','monthly','full')
    proof_path varchar NULL
    status enum('pending','verified','rejected')
    verified_at timestamp NULL
    verified_by bigint FK(users.id) NULL
    notes text NULL
    created_at timestamp
    updated_at timestamp
}

testimonials {
    id bigint PK
    user_id bigint FK(users.id) NULL
    name varchar NULL
    label varchar NULL
    booking_id bigint FK(bookings.id) NULL
    rating tinyint DEFAULT 5
    content text
    status enum('pending','approved','rejected')
    created_at timestamp
    updated_at timestamp
}
```

---

## 6. Daftar Halaman & Navigasi (per Role)

### Halaman Publik (Tanpa Login)

| No | Nama Halaman | Route/URL | Komponen Utama | Fitur | Navigasi Lanjutan |
|---|---|---|---|---|---|
| 1 | Beranda | `GET /` | Layout `app.blade.php`, `home.blade.php` | Menampilkan 3 kamar tersedia, 3 testimoni terbaru, modal login/register | Link ke halaman kamar, tentang; modal auth |
| 2 | Daftar Kamar | `GET /kamar` | `rooms/index.blade.php` | Daftar kamar tersedia (filter lantai), card kamar dengan foto utama & fasilitas | Link detail per kamar |
| 3 | Detail Kamar | `GET /kamar/{room}` | `rooms/show.blade.php` | Galeri foto, deskripsi, fasilitas, harga | Link booking (jika sudah login) |
| 4 | Tentang | `GET /tentang` | `tentang.blade.php` | Informasi tentang Kos Putri Gardenia | — |
| 5 | Login (Modal) | `GET /login` | Modal popup di `partials/auth-modal.blade.php` | Form login (email, password) | Redirect ke dashboard setelah login |
| 6 | Register (Modal) | `GET /register` | Modal popup di `partials/auth-modal.blade.php` | Form registrasi (nama, tgl lahir, HP, email, alamat, password) | Redirect ke home setelah register |
| 7 | Lupa Password | `GET /forgot-password` | Blade Breeze | Form request reset password | Link reset password via email |
| 8 | Reset Password | `GET /reset-password/{token}` | Blade Breeze | Form reset password baru | Redirect login |

### Halaman User (Setelah Login, Role: `user`)

| No | Nama Halaman | Route/URL | Komponen Utama | Fitur | Navigasi Lanjutan |
|---|---|---|---|---|---|
| 1 | Dashboard User | `GET /user/dashboard` | `user/dashboard.blade.php`, layout `user.blade.php` | Ringkasan booking aktif, jumlah total booking | Link ke kamar, riwayat |
| 2 | Daftar Kamar (User) | `GET /user/kamar` | `user/rooms/index.blade.php` | Daftar semua kamar (termasuk terisi), filter lantai | Link detail kamar |
| 3 | Detail Kamar (User) | `GET /user/kamar/{room}` | `user/rooms/show.blade.php` | Galeri foto, deskripsi, fasilitas, harga | Tombol "Booking Sekarang" |
| 4 | Form Booking | `GET /user/booking/{room}` | `user/booking/create.blade.php` | Form pilih tanggal check-in, metode pembayaran (QRIS/DANA/OVO/BCA), no e-wallet | Submit booking (AJAX) |
| 5 | Detail Booking | `GET /user/booking/{booking}` | `user/booking/show.blade.php` | Detail booking, status, daftar pembayaran, upload bukti bayar | Form upload bukti pembayaran |
| 6 | Riwayat Booking | `GET /user/riwayat` | `user/booking/history.blade.php` | Daftar semua booking user (paginated), modal detail booking | Link detail booking |
| 7 | Edit Profil | `GET /user/profil` | `profile/edit.blade.php` | Form edit nama, HP, avatar, password | Simpan perubahan |

### Halaman Admin (Setelah Login, Role: `admin`)

| No | Nama Halaman | Route/URL | Komponen Utama | Fitur | Navigasi Lanjutan |
|---|---|---|---|---|---|
| 1 | Dashboard Admin | `GET /admin/dashboard` | `admin/dashboard.blade.php`, layout `admin.blade.php` | Statistik (total kamar, kamar tersedia/terisi, total booking, pending booking, total user, pending pembayaran, pendapatan bulanan), daftar booking terbaru, daftar pembayaran pending | Link ke detail booking, verifikasi pembayaran |
| 2 | Kelola Kamar | `GET /admin/kamar` | `admin/rooms/index.blade.php` | Daftar kamar (paginated), toggle ketersediaan | Link tambah/edit/hapus kamar |
| 3 | Tambah Kamar | `GET /admin/kamar/create` | `admin/rooms/create.blade.php` | Form input nama, tipe, lantai, harga, deskripsi, fasilitas, upload foto (multiple) | Redirect ke daftar kamar |
| 4 | Edit Kamar | `GET /admin/kamar/{kamar}/edit` | `admin/rooms/edit.blade.php` | Form edit data kamar, kelola foto (upload/hapus), ubah fasilitas | Redirect ke daftar kamar |
| 5 | Detail Booking | `GET /admin/booking/{booking}` | `admin/bookings/show.blade.php` | Detail booking lengkap, data user, kamar, daftar pembayaran, form ubah status booking | Aksi: ubah status, batalkan booking |
| 6 | Kelola Pembayaran | `GET /admin/pembayaran` | `admin/payments/index.blade.php` | Daftar pembayaran (filter status, paginated), bukti bayar | Aksi: verifikasi/tolak pembayaran |
| 7 | Kelola Testimoni | `GET /admin/testimoni` | `admin/testimonials/index.blade.php` | Daftar testimoni (paginated) | Link tambah/edit/hapus |
| 8 | Tambah Testimoni | `GET /admin/testimoni/create` | `admin/testimonials/create.blade.php` | Form input nama, label, rating, konten | Redirect ke daftar testimoni |
| 9 | Edit Testimoni | `GET /admin/testimoni/{testimonial}/edit` | `admin/testimonials/edit.blade.php` | Form edit data testimoni, ubah status | Redirect ke daftar testimoni |

---

## 7. Daftar API Endpoint

### Route Publik (Tanpa Autentikasi)

| Method | Path | Fungsi | Controller | Parameter | Auth |
|---|---|---|---|---|---|
| GET | `/` | Halaman beranda | `HomeController@index` | — | Tidak |
| GET | `/kamar` | Daftar kamar tersedia | `RoomController@index` | Query: `lantai` (opsional) | Tidak |
| GET | `/kamar/{room}` | Detail kamar | `RoomController@show` | Path: `room` (id) | Tidak |
| GET | `/tentang` | Halaman tentang | `HomeController@tentang` | — | Tidak |

### Route Autentikasi (Laravel Breeze)

| Method | Path | Fungsi | Auth |
|---|---|---|---|
| GET | `/register` | Redirect ke home + buka popup register | Guest |
| POST | `/register` | Proses registrasi user baru | Guest |
| GET | `/login` | Redirect ke home + buka popup login | Guest |
| POST | `/login` | Proses login | Guest |
| GET | `/forgot-password` | Form lupa password | Guest |
| POST | `/forgot-password` | Kirim email reset password | Guest |
| GET | `/reset-password/{token}` | Form reset password | Guest |
| POST | `/reset-password` | Proses reset password | Guest |
| GET | `/verify-email` | Prompt verifikasi email | Auth |
| GET | `/verify-email/{id}/{hash}` | Verifikasi email | Auth, Signed |
| POST | `/email/verification-notification` | Kirim ulang email verifikasi | Auth |
| GET | `/confirm-password` | Form konfirmasi password | Auth |
| POST | `/confirm-password` | Proses konfirmasi password | Auth |
| PUT | `/password` | Update password | Auth |
| POST | `/logout` | Proses logout | Auth |

### Route User (Middleware: `auth`)

| Method | Path | Fungsi | Parameter/Body | Response | Auth |
|---|---|---|---|---|---|
| GET | `/dashboard` | Redirect ke dashboard sesuai role | — | Redirect | Auth |
| GET | `/user/dashboard` | Dashboard user | — | View HTML | Auth |
| GET | `/user/kamar` | Daftar kamar (semua) | Query: `lantai` (opsional) | View HTML | Auth |
| GET | `/user/kamar/{room}` | Detail kamar | Path: `room` | View HTML | Auth |
| GET | `/user/booking/{room}` | Form booking kamar | Path: `room` | View HTML | Auth |
| POST | `/user/booking` | Buat booking baru | Body: `room_id`, `check_in_date`, `payment_method`, `ewallet_phone` (opsional) | JSON (AJAX) / Redirect | Auth |
| GET | `/user/riwayat` | Riwayat booking | — | View HTML (paginated) | Auth |
| GET | `/user/booking/{booking}` | Detail booking | Path: `booking` | View HTML | Auth (owner) |
| POST | `/user/pembayaran/{booking}` | Upload bukti pembayaran | Body: `payment_method`, `proof` (file) | Redirect | Auth (owner) |
| GET | `/user/profil` | Form edit profil | — | View HTML | Auth |
| PATCH | `/user/profil` | Update profil | Body: `name`, `email`, `phone`, `avatar` (file), `current_password`, `password`, `password_confirmation` | Redirect | Auth |

**Contoh Response POST `/user/booking` (AJAX):**

```json
{
  "success": true,
  "nama": "John Doe",
  "telepon": "081234567890",
  "kamar": "Kamar 101",
  "lantai": 1,
  "tanggal_masuk": "Senin, 14 Juli 2026",
  "metode": "qris",
  "booking_code": "GDN-12345678",
  "tanggal_transaksi": "Minggu, 13 Juli 2026",
  "sisa": 750000
}
```

### Route Admin (Middleware: `auth`, `admin`)

| Method | Path | Fungsi | Parameter/Body | Auth |
|---|---|---|---|---|
| GET | `/admin/dashboard` | Dashboard admin | — | Admin |
| GET | `/admin/kamar` | Daftar kamar | — | Admin |
| GET | `/admin/kamar/create` | Form tambah kamar | — | Admin |
| POST | `/admin/kamar` | Simpan kamar baru | Body: `name`, `type`, `floor`, `price`, `description`, `photos[]`, `facilities[]` | Admin |
| GET | `/admin/kamar/{kamar}/edit` | Form edit kamar | Path: `kamar` | Admin |
| PUT | `/admin/kamar/{kamar}` | Update data kamar | Body: `name`, `type`, `floor`, `price`, `description`, `is_available`, `facilities[]` | Admin |
| DELETE | `/admin/kamar/{kamar}` | Hapus kamar | Path: `kamar` | Admin |
| POST | `/admin/kamar/{kamar}/foto` | Upload foto kamar | Body: `photo` (file) | Admin |
| DELETE | `/admin/foto/{photo}` | Hapus foto kamar | Path: `photo` | Admin |
| PATCH | `/admin/kamar/{kamar}/toggle` | Toggle ketersediaan kamar | Path: `kamar` | Admin |
| GET | `/admin/booking/{booking}` | Detail booking | Path: `booking` | Admin |
| PATCH | `/admin/booking/{booking}/status` | Update status booking | Body: `status`, `cancel_reason` (opsional) | Admin |
| GET | `/admin/pembayaran` | Daftar pembayaran | Query: `status` (opsional) | Admin |
| PATCH | `/admin/pembayaran/{payment}/verify` | Verifikasi pembayaran | Path: `payment` | Admin |
| PATCH | `/admin/pembayaran/{payment}/reject` | Tolak pembayaran | Body: `reject_notes` (opsional) | Admin |
| GET | `/admin/testimoni` | Daftar testimoni | — | Admin |
| GET | `/admin/testimoni/create` | Form tambah testimoni | — | Admin |
| POST | `/admin/testimoni` | Simpan testimoni baru | Body: `name`, `label`, `rating`, `content` | Admin |
| GET | `/admin/testimoni/{testimonial}/edit` | Form edit testimoni | Path: `testimonial` | Admin |
| PUT | `/admin/testimoni/{testimonial}` | Update testimoni | Body: `name`, `label`, `rating`, `content`, `status` | Admin |
| DELETE | `/admin/testimoni/{testimonial}` | Hapus testimoni | Path: `testimonial` | Admin |

---

## 8. Fitur & Logika Bisnis Utama

### 8.1 Sistem Booking Kamar

**Alur:**
1. User memilih kamar yang tersedia (`is_available = true`)
2. User mengisi form booking: tanggal check-in (minimal hari ini), metode pembayaran
3. Sistem memvalidasi:
   - `room_id` harus valid dan ada di database
   - `check_in_date` harus tanggal hari ini atau setelahnya
   - `payment_method` harus salah satu: `qris`, `dana`, `ovo`, `bca`
   - Jika metode `dana` atau `ovo`, nomor e-wallet wajib diisi
4. Sistem mengecek ketersediaan kamar (`is_available`)
5. Generate kode booking: format `GDN-XXXXXXXX` (8 digit random)
6. Durasi default: 1 bulan
7. Total harga = harga kamar per bulan
8. DP tetap = Rp250.000 (konstanta `Booking::DP_AMOUNT`)
9. Booking dibuat dengan status `pending`
10. Pembayaran DP dibuat dengan status `pending` (tanpa bukti bayar saat ini)

**Status Booking:**

| Status | Deskripsi |
|---|---|
| `pending` | Booking baru, menunggu verifikasi pembayaran DP oleh admin |
| `confirmed` | Pembayaran DP diverifikasi oleh admin |
| `active` | Penghuni aktif menempati kamar |
| `cancelled` | Booking dibatalkan (oleh admin) |
| `completed` | Masa sewa selesai |

**Aturan Bisnis:**
- Kamar **tidak** langsung ditandai unavailable saat booking dibuat — menunggu admin verifikasi pembayaran
- Saat booking `cancelled` atau `completed`, kamar otomatis dibebaskan (`is_available = true`)
- Saat booking selain `cancelled`/`completed`, kamar ditandai tidak tersedia (`is_available = false`)
- User hanya bisa melihat booking miliknya sendiri (pengecekan `user_id`)

### 8.2 Sistem Pembayaran

**Metode Pembayaran:**
- QRIS
- DANA
- OVO
- BCA (transfer bank)

**Tipe Pembayaran:**

| Tipe | Deskripsi |
|---|---|
| `dp` | Down Payment (uang muka) — Rp250.000 |
| `monthly` | Pembayaran bulanan |
| `full` | Pembayaran penuh |

> **Catatan:** Saat ini codebase hanya mengimplementasikan pembayaran DP. Tipe `monthly` dan `full` didefinisikan di schema tapi belum digunakan dalam logika controller.

**Status Pembayaran:**

| Status | Deskripsi |
|---|---|
| `pending` | Menunggu verifikasi admin |
| `verified` | Pembayaran diverifikasi admin |
| `rejected` | Pembayaran ditolak admin |

**Alur Verifikasi:**
1. User membuat booking → pembayaran DP otomatis dibuat (status `pending`)
2. User dapat upload bukti bayar melalui halaman detail booking (file gambar, max 2MB)
3. Admin melihat daftar pembayaran pending di dashboard
4. Admin verifikasi (`verify`):
   - Status pembayaran → `verified`
   - `verified_at` dicatat
   - `verified_by` = ID admin
   - Booking → `confirmed`
   - Kamar → `is_available = false`
5. Admin tolak (`reject`):
   - Status pembayaran → `rejected`
   - Booking → `cancelled`
   - `cancelled_reason` = catatan admin
   - `cancelled_by` = 'admin'

### 8.3 Manajemen Kamar (Admin)

**Fitur:**
- CRUD kamar (nama, tipe, lantai, harga, deskripsi)
- Upload multiple foto (format: JPG, JPEG, PNG, WebP, max 2MB per file)
- Foto pertama otomatis menjadi foto utama (`is_primary`)
- Jika foto utama dihapus, foto berikutnya otomatis jadi foto utama
- Toggle ketersediaan kamar (tersedia/terisi)
- Kelola fasilitas per kamar (many-to-many)

**Tipe Kamar:**
- `standard`
- `deluxe`
- `vip`

**Lantai:**
- Lantai 1
- Lantai 2

**Validasi Upload Foto:**
- Harus berupa gambar (`image`)
- Format: `jpg`, `jpeg`, `png`, `webp`
- Maksimal 2MB per file

### 8.4 Manajemen Testimoni (Admin)

**Catatan Penting:** Testimoni dikelola langsung oleh admin, **bukan** diajukan oleh user.

**Fitur:**
- CRUD testimoni
- Field: nama tampilan, label (default: "Penghuni Aktif"), rating (1-5), konten, status
- Status: `approved` atau `rejected`
- Saat dibuat admin, status otomatis `approved`
- Testimoni dengan status `approved` ditampilkan di halaman beranda publik

### 8.5 Profil User

**Fitur:**
- Edit nama, nomor HP
- Upload avatar (JPG/PNG, max 2MB) — avatar lama dihapus dari storage
- Ganti password (wajib isi password lama)
- Email bersifat **read-only** (tidak bisa diubah dari form profil)

### 8.6 Fasilitas Standar Kamar

Selain fasilitas yang dikelola via database, terdapat **fasilitas standar** yang berlaku untuk semua kamar (didefinisikan di `App\Support\RoomFacilities`):

1. Kamar Mandi Dalam
2. Meja Belajar
3. WiFi (50 Mbps)
4. Kasur Nyaman
5. Lemari Baju
6. Listrik (termasuk biaya dasar)

---

## 9. Integrasi Eksternal

| Integrasi | Status | Keterangan |
|---|---|---|
| **Payment Gateway** | ❌ Tidak ada | Pembayaran menggunakan metode manual (transfer/e-wallet) dengan verifikasi admin. Tidak ada integrasi payment gateway otomatis (Midtrans, Xendit, dll). |
| **WhatsApp API** | ❌ Tidak ditemukan di codebase | — |
| **Email Service** | ⚠️ Minimal | MAIL_MAILER = `log` (email hanya masuk log, tidak benar-benar terkirim). Infrastruktur email tersedia via konfigurasi Laravel tapi belum dikonfigurasi untuk production. |
| **Cloud Storage** | ❌ Tidak ada | File disimpan di disk `local` (public storage). Konfigurasi AWS S3 ada di `.env` tapi tidak terisi. |
| **InfinityFree Hosting** | ✅ Ada | Konfigurasi hosting production ditemukan di `.env_hosting` (MySQL di `sql307.infinityfree.com`). |

---

## 10. Dependency & Package

### PHP (composer.json)

#### Production Dependencies (`require`)

| Package | Versi | Fungsi |
|---|---|---|
| `php` | ^8.2 | Runtime PHP |
| `laravel/framework` | ^12.0 | Framework utama Laravel |
| `laravel/tinker` | ^2.10.1 | REPL interaktif untuk debugging Eloquent & Laravel |

#### Development Dependencies (`require-dev`)

| Package | Versi | Fungsi |
|---|---|---|
| `fakerphp/faker` | ^1.23 | Generator data palsu untuk testing & seeding |
| `laravel/breeze` | ^2.4 | Scaffolding autentikasi (login, register, reset password) |
| `laravel/pail` | ^1.2.2 | Real-time log viewer di terminal |
| `laravel/pint` | ^1.13 | PHP code formatter (PSR-12) |
| `laravel/sail` | ^1.41 | Docker development environment untuk Laravel |
| `mockery/mockery` | ^1.6 | Mocking framework untuk unit testing |
| `nunomaduro/collision` | ^8.6 | Error handler yang indah untuk CLI |
| `phpunit/phpunit` | ^11.5.3 | Framework testing unit PHP |

### Node.js (package.json)

| Package | Versi | Fungsi |
|---|---|---|
| `@tailwindcss/forms` | ^0.5.2 | Plugin TailwindCSS untuk reset & styling form elements |
| `@tailwindcss/vite` | ^4.0.0 | Plugin Vite untuk TailwindCSS |
| `alpinejs` | ^3.4.2 | Framework JavaScript ringan untuk interaktivitas UI (modal, dropdown, toggle) |
| `autoprefixer` | ^10.4.2 | PostCSS plugin untuk auto-prefix CSS |
| `axios` | ^1.7.4 | HTTP client untuk AJAX requests |
| `concurrently` | ^9.0.1 | Menjalankan multiple commands secara bersamaan (server + queue + vite) |
| `laravel-vite-plugin` | ^1.2.0 | Plugin Vite untuk integrasi asset bundling dengan Laravel |
| `postcss` | ^8.4.31 | CSS processor framework |
| `tailwindcss` | ^3.1.0 | Utility-first CSS framework |
| `vite` | ^6.0.11 | Build tool & development server untuk frontend |

---

## 11. Cara Menjalankan Project

### Prasyarat

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL (atau SQLite untuk development ringan)
- Laragon (opsional, untuk Windows)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repository-url> gardenia-kosla122
cd gardenia-kosla122

# 2. Install dependency PHP
composer install

# 3. Install dependency Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di .env
# Untuk MySQL:
#   DB_CONNECTION=mysql
#   DB_HOST=127.0.0.1
#   DB_PORT=3306
#   DB_DATABASE=gardenia_kos
#   DB_USERNAME=root
#   DB_PASSWORD=
#
# Untuk SQLite (default):
#   DB_CONNECTION=sqlite
#   (pastikan file database/database.sqlite ada)

# 7. Jalankan migrasi database
php artisan migrate

# 8. Jalankan seeder (membuat akun admin default)
php artisan db:seed
# Akun admin:
#   Email: admin@gardenia.com
#   Password: admin123

# 9. Buat symbolic link untuk public storage
php artisan storage:link

# 10. Build asset frontend (development)
npm run dev

# 11. Jalankan server Laravel
php artisan serve
```

### Menjalankan dengan Script Dev (Concurrent)

```bash
# Menjalankan server, queue, log viewer, dan vite secara bersamaan
composer dev
```

Perintah ini menjalankan:
- `php artisan serve` — Server Laravel
- `php artisan queue:listen --tries=1` — Queue worker
- `php artisan pail --timeout=0` — Real-time log viewer
- `npm run dev` — Vite dev server

### Akses Aplikasi

| URL | Keterangan |
|---|---|
| `http://localhost:8000` | Halaman beranda publik |
| `http://localhost:8000/login` | Login (redirect ke modal) |
| `http://localhost:8000/admin/dashboard` | Dashboard admin (login sebagai admin) |
| `http://localhost:8000/user/dashboard` | Dashboard user (login sebagai user) |

---

## 12. Kendala/Catatan Teknis

### Pembayaran Tipe `monthly` dan `full`

- Schema database mendukung tipe pembayaran `dp`, `monthly`, dan `full`, tetapi **hanya `dp` yang diimplementasikan** dalam logika controller. Fitur pembayaran bulanan dan penuh belum dikembangkan.

### Pembayaran DP Tanpa Bukti Bayar

- Saat booking pertama kali dibuat (via `BookingController@store`), pembayaran DP dibuat **tanpa bukti bayar** (`proof_path = null`). User bisa upload bukti bayar terpisah melalui `PaymentController@store`.

### Email Tidak Aktif

- Mail driver diset ke `log` (development) sehingga email verifikasi dan reset password **hanya masuk log** dan tidak benar-benar terkirim. Untuk production, perlu dikonfigurasi SMTP yang sesuai.

### Kredensial `.env_hosting` Terekspos

- File `.env_hosting` sebelumnya berisi kredensial database production (InfinityFree) yang hardcoded di repository. **Sudah difix**: file telah ditambahkan ke `.gitignore` dan dihapus dari git tracking (`git rm --cached`). File tetap ada di lokal untuk referensi. **Disarankan rotasi password database production** karena kredensial lama sudah terlanjur tersimpan di git history.

### `APP_NAME` Default

- `APP_NAME` di `.env` masih `Laravel` (default). Untuk production, sebaiknya diubah menjadi `"Kos Putri Gardenia"` (seperti di `.env_hosting`).

### Tidak Ada TODO/FIXME di Source Code

- Tidak ditemukan komentar `TODO`, `FIXME`, `HACK`, atau `XXX` di source code aplikasi (`app/` dan `resources/`).

### Tidak Ada Automated Tests

- Folder `tests/` ada tetapi belum berisi test khusus project. Hanya terdapat file default dari Laravel.

### Fasilitas Standar vs Fasilitas Database

- Terdapat dualitas: fasilitas bisa dikelola via database (tabel `facilities` + pivot `room_facility`) **dan** ada fasilitas standar hardcoded di `App\Support\RoomFacilities`. Perlu diperhatikan mana yang digunakan di masing-masing halaman untuk menghindari duplikasi informasi.

### Durasi Booking Tetap 1 Bulan

- Durasi booking saat ini di-hardcode menjadi `1` bulan (`'duration_months' => 1`). Field `duration_months` ada di database tetapi user tidak bisa memilih durasi sewa yang berbeda dari form booking.
