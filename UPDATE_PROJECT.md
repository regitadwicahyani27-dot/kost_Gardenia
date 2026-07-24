# 🔄 Panduan Update Project Gardenia Kos

## Untuk Komputer/Server yang Sudah Ada Project-nya

Jika Anda sudah punya project ini di komputer lain dan ingin update ke versi terbaru, ikuti langkah berikut:

---

## 📋 Update Otomatis (Recommended)

### Windows (PowerShell/CMD)

1. **Buka Terminal** di folder project
2. **Copy dan jalankan command ini**:

```powershell
# Pull update terbaru dari GitHub
git pull origin main

# Jalankan migration baru
php artisan migrate

# Clear cache (opsional tapi recommended)
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "✅ Project berhasil diupdate!"
```

### Linux/Mac (Bash)

```bash
# Pull update terbaru dari GitHub
git pull origin main

# Jalankan migration baru
php artisan migrate

# Clear cache (opsional tapi recommended)
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "✅ Project berhasil diupdate!"
```

---

## 🚀 Script Otomatis

### Windows: `update.bat`

Buat file `update.bat` di root project dengan isi:

```batch
@echo off
echo ================================================
echo  UPDATE PROJECT GARDENIA KOS
echo ================================================
echo.

echo [1/4] Pulling dari GitHub...
git pull origin main
if %errorlevel% neq 0 (
    echo ❌ Git pull gagal!
    pause
    exit /b 1
)
echo ✅ Git pull berhasil!
echo.

echo [2/4] Menjalankan migration...
php artisan migrate
if %errorlevel% neq 0 (
    echo ❌ Migration gagal!
    pause
    exit /b 1
)
echo ✅ Migration berhasil!
echo.

echo [3/4] Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo ✅ Cache cleared!
echo.

echo [4/4] Checking status...
git status
echo.

echo ================================================
echo  ✅ PROJECT BERHASIL DIUPDATE!
echo ================================================
echo.
echo Update yang didapat:
echo - Migration untuk optimasi varchar columns
echo - Fix error upload image (data too long)
echo - Kolom path file tetap varchar(255)
echo.
pause
```

**Cara pakai**: Double-click file `update.bat`

---

### Linux/Mac: `update.sh`

Buat file `update.sh` di root project dengan isi:

```bash
#!/bin/bash

echo "================================================"
echo " UPDATE PROJECT GARDENIA KOS"
echo "================================================"
echo ""

echo "[1/4] Pulling dari GitHub..."
git pull origin main
if [ $? -ne 0 ]; then
    echo "❌ Git pull gagal!"
    exit 1
fi
echo "✅ Git pull berhasil!"
echo ""

echo "[2/4] Menjalankan migration..."
php artisan migrate
if [ $? -ne 0 ]; then
    echo "❌ Migration gagal!"
    exit 1
fi
echo "✅ Migration berhasil!"
echo ""

echo "[3/4] Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo "✅ Cache cleared!"
echo ""

echo "[4/4] Checking status..."
git status
echo ""

echo "================================================"
echo " ✅ PROJECT BERHASIL DIUPDATE!"
echo "================================================"
echo ""
echo "Update yang didapat:"
echo "- Migration untuk optimasi varchar columns"
echo "- Fix error upload image (data too long)"
echo "- Kolom path file tetap varchar(255)"
echo ""
```

**Cara pakai**:
```bash
chmod +x update.sh
./update.sh
```

---

## 📝 Update Manual (Step by Step)

Jika Anda ingin lebih hati-hati, ikuti langkah ini:

### Step 1: Backup Database (Opsional tapi Recommended)
```bash
# Export database dulu buat jaga-jaga
php artisan db:export gardenia_kos_backup.sql
# atau pakai mysqldump
mysqldump -u root -p kost_gardenia > backup_before_update.sql
```

### Step 2: Cek Status Git
```bash
git status
```
- Jika ada file yang belum di-commit, commit atau stash dulu
- Jika ada konflik, resolve dulu

### Step 3: Pull dari GitHub
```bash
git pull origin main
```

### Step 4: Cek Migration Baru
```bash
php artisan migrate:status
```
Akan muncul migration baru:
```
2026_07_24_072933_optimize_varchar_columns_length ........ Pending
```

### Step 5: Jalankan Migration
```bash
php artisan migrate
```

Output yang diharapkan:
```
INFO  Running migrations.
2026_07_24_072933_optimize_varchar_columns_length ...... DONE
```

### Step 6: Verify
```bash
# Cek tabel users
php artisan tinker
>>> DB::select("SHOW COLUMNS FROM users WHERE Field='avatar'");
# Seharusnya varchar(255)

>>> DB::select("SHOW COLUMNS FROM room_photos WHERE Field='photo_path'");
# Seharusnya varchar(255)

>>> exit
```

### Step 7: Test Upload
- Login ke admin panel
- Coba upload foto kamar
- Coba upload bukti pembayaran
- Seharusnya tidak ada error "data too long"

---

## ⚠️ Troubleshooting

### Problem 1: Git Pull Error (Konflik)
```bash
# Jika ada konflik
git stash              # Simpan perubahan lokal
git pull origin main   # Pull update
git stash pop          # Kembalikan perubahan lokal
```

### Problem 2: Migration Error
```bash
# Rollback migration terakhir
php artisan migrate:rollback --step=1

# Coba lagi
php artisan migrate
```

### Problem 3: "Nothing to Migrate"
```bash
# Cek status migration
php artisan migrate:status

# Jika sudah "Ran", berarti sudah jalan. Tidak perlu apa-apa.
```

### Problem 4: Database Connection Error
```bash
# Cek .env file
cat .env | grep DB_

# Pastikan DB_DATABASE, DB_USERNAME, DB_PASSWORD benar
```

---

## 🔍 Cara Cek Update Berhasil

### Cek 1: Git Status
```bash
git log --oneline -3
```
Seharusnya ada commit:
```
bd50796 Push Database Last - Optimize varchar columns untuk fix upload image error
```

### Cek 2: Migration Status
```bash
php artisan migrate:status
```
Seharusnya semua migration status "Ran"

### Cek 3: Database Structure
Jalankan query ini di MySQL/phpMyAdmin:
```sql
SHOW COLUMNS FROM users WHERE Field='avatar';
-- Type seharusnya: varchar(255)

SHOW COLUMNS FROM room_photos WHERE Field='photo_path';
-- Type seharusnya: varchar(255)

SHOW COLUMNS FROM payments WHERE Field='proof_path';
-- Type seharusnya: varchar(255)
```

### Cek 4: Functional Test
1. Login sebagai admin
2. Tambah kamar baru dengan foto
3. Upload bukti pembayaran
4. Seharusnya tidak ada error

---

## 📦 Yang Akan Didapat Setelah Update

### ✅ File Baru:
1. `database/migrations/2026_07_24_072933_optimize_varchar_columns_length.php`
2. `MIGRATION_REPORT.md`
3. `UPDATE_PROJECT.md` (file ini)

### ✅ Database Changes:
- Kolom path file tetap varchar(255) ✅
- Kolom non-path dioptimasi (name, phone, role, dll)
- Upload gambar tidak error lagi ✅

### ✅ Bug Fixes:
- ❌ Error "data too long for column" → ✅ Fixed
- ❌ Upload foto gagal → ✅ Fixed
- ❌ Database tidak efisien → ✅ Optimized

---

## 💡 Tips

### Untuk Development:
```bash
# Update setiap hari
git pull origin main
php artisan migrate
```

### Untuk Production:
```bash
# Backup dulu
php artisan down                    # Maintenance mode
mysqldump -u root -p kost_gardenia > backup.sql
git pull origin main
php artisan migrate --force         # Force di production
php artisan config:clear
php artisan cache:clear
php artisan up                      # Exit maintenance mode
```

### Untuk Team:
```bash
# Setiap pagi sebelum coding
git pull origin main
php artisan migrate
composer install  # Jika ada dependency baru
npm install       # Jika ada package baru
```

---

## 🆘 Butuh Bantuan?

Jika ada masalah:
1. Cek `MIGRATION_REPORT.md` untuk detail perubahan
2. Jalankan `git status` untuk cek kondisi project
3. Jalankan `php artisan migrate:status` untuk cek migration
4. Hubungi team lead atau admin

---

## 📞 Contact

**Project**: Gardenia Kos Management System  
**Repository**: https://github.com/regitadwicahyani27-dot/kost_Gardenia.git  
**Last Update**: 24 Juli 2026  

---

**Happy Coding! 🚀**
