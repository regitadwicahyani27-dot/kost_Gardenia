# 🚀 Panduan Deploy Kos Putri Gardenia ke InfinityFree

## Prasyarat
- Akun InfinityFree (free tier)
- FileZilla atau FTP Client lain
- File: `gardenia-infinityfree-release.zip`

---

## Langkah 1: Buat Akun & Hosting di InfinityFree

1. Buka [infinityfree.com](https://www.infinityfree.com/) dan daftar/login
2. Klik **"Create Account"** untuk membuat hosting baru
3. Pilih subdomain (contoh: `gardenia-kos.infinityfreeapp.com`) atau gunakan domain sendiri
4. Catat informasi berikut dari panel:
   - **FTP Username**
   - **FTP Password**
   - **FTP Server** (biasanya `ftpupload.net`)
   - **MySQL Host** (biasanya `sql306.infinityfree.com` atau mirip)

---

## Langkah 2: Buat Database MySQL

1. Di panel InfinityFree, buka **"MySQL Databases"**
2. Klik **"Create Database"**
3. Catat:
   - **Database Name** (contoh: `if0_12345678_gardenia`)
   - **Username** (contoh: `if0_12345678`)
   - **Password** (yang kamu set)
   - **Host** (contoh: `sql306.infinityfree.com`)

---

## Langkah 3: Import Database

1. Buka **phpMyAdmin** dari panel InfinityFree
2. Pilih database yang baru dibuat
3. Klik tab **"Import"**
4. Upload file `database/gardenia_kos.sql` (ada di dalam ZIP)
5. Klik **"Go"** / **"Import"**

> ⚠️ **Jika file SQL terlalu besar**, split file-nya atau gunakan tool import online.

---

## Langkah 4: Edit File .env

Sebelum upload, extract ZIP dan edit file `.env` di root:

```env
APP_URL=https://SUBDOMAIN-KAMU.infinityfreeapp.com

DB_HOST=sql306.infinityfree.com        ← Ganti dengan MySQL Host dari panel
DB_DATABASE=if0_XXXXXXX_gardenia       ← Ganti dengan nama database
DB_USERNAME=if0_XXXXXXX                ← Ganti dengan username database
DB_PASSWORD=PASSWORD_DARI_PANEL        ← Ganti dengan password database
```

---

## Langkah 5: Upload via FTP

1. Buka **FileZilla**
2. Connect ke FTP:
   - Host: `ftpupload.net`
   - Username: FTP username dari panel
   - Password: FTP password dari panel
   - Port: `21`

3. **Navigasi ke folder `htdocs/`** di server
4. **Hapus semua file default** di `htdocs/`
5. **Upload SEMUA isi** dari ZIP ke dalam `htdocs/`

### Struktur akhir di server:
```
htdocs/
├── .htaccess          ← ini yang redirect ke public/
├── .env               ← sudah diedit dengan credentials
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── .htaccess
│   ├── index.php
│   ├── build/
│   ├── images/
│   └── ...
├── resources/
├── routes/
├── storage/
├── vendor/
├── artisan
└── ...
```

---

## Langkah 6: Set Permission Storage (via FTP)

Di FileZilla, **klik kanan** folder-folder berikut dan set **File Permissions** ke **`777`**:

- `storage/` (recursive/semua subfolder)
- `bootstrap/cache/`

**Cara:**
1. Klik kanan folder → **File Permissions...**
2. Set numeric value ke `777`
3. Centang **"Recurse into subdirectories"**
4. Klik OK

---

## Langkah 7: Buat Symlink Storage (Opsional)

Karena InfinityFree tidak mendukung `php artisan`, kamu perlu buat symlink manual.

Buat file `symlink.php` di `htdocs/public/`:

```php
<?php
// Jalankan sekali lalu hapus!
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

if (file_exists($link)) {
    echo "Symlink sudah ada!";
} else {
    if (symlink($target, $link)) {
        echo "Symlink berhasil dibuat!";
    } else {
        // Fallback: copy file
        echo "Symlink gagal. Coba copy manual.";
    }
}
```

Buka di browser: `https://domain-kamu/symlink.php` lalu **hapus file tersebut**.

---

## Langkah 8: Test Website

1. Buka `https://SUBDOMAIN-KAMU.infinityfreeapp.com`
2. Jika muncul error 500:
   - Cek `.env` sudah benar
   - Cek permission `storage/` sudah 777
   - Cek log di `storage/logs/laravel.log`
3. Login admin: `admin@gardenia.com` / `admin123`

---

## ⚠️ Batasan InfinityFree Free Tier

| Batasan | Detail |
|---------|--------|
| PHP Version | 8.2 (kompatibel dengan Laravel 12) |
| MySQL | ✅ Tersedia |
| Upload Limit | 10 MB per file via FTP |
| Bandwidth | Terbatas (tidak cocok traffic tinggi) |
| Cron Jobs | ❌ Tidak tersedia |
| SSH Access | ❌ Tidak tersedia |
| `exec()`, `shell_exec()` | ❌ Diblokir |
| File Size Limit | Max 10MB per file |
| Storage | 5 GB |

---

## 🔧 Troubleshooting

### Error 403 Forbidden
- Pastikan `.htaccess` ada di root `htdocs/`
- Pastikan `public/.htaccess` juga ada

### Error 500 Internal Server Error
- Cek `.env` → pastikan DB credentials benar
- Set `APP_DEBUG=true` sementara untuk lihat error detail
- Pastikan `storage/` permission 777

### Halaman Blank / CSS Tidak Muncul
- Pastikan folder `public/build/` sudah ter-upload lengkap
- Cek `APP_URL` di `.env` sudah benar

### Database Error
- Pastikan sudah import `gardenia_kos.sql`
- Pastikan MySQL host, username, password benar
- Cek nama database (harus exact match)

---

## ✅ Checklist Deploy

- [ ] Database MySQL dibuat di panel
- [ ] File SQL di-import via phpMyAdmin
- [ ] File `.env` sudah diedit dengan credentials yang benar
- [ ] Semua file di-upload ke `htdocs/`
- [ ] Permission `storage/` dan `bootstrap/cache/` = 777
- [ ] Website bisa diakses
- [ ] Login admin berfungsi
- [ ] Gambar dan CSS tampil dengan benar
