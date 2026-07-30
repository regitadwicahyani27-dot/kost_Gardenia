@echo off
cls
echo.
echo ╔══════════════════════════════════════════════════════════════════╗
echo ║     TEST LENGKAP - FITUR PEMBAYARAN OFFLINE (DP + PELUNASAN)     ║
echo ╚══════════════════════════════════════════════════════════════════╝
echo.
echo Step 1: Refresh Database + Seed Data
echo ══════════════════════════════════════════════════════════════════
php refresh-db.php
if %errorlevel% neq 0 goto :error
echo.
echo.

echo Step 2: Jalankan Migration (Tambah enum 'cash')
echo ══════════════════════════════════════════════════════════════════
php artisan migrate --force
if %errorlevel% neq 0 goto :error
echo.
echo.

echo Step 3: Verifikasi Dashboard Calculation
echo ══════════════════════════════════════════════════════════════════
php verify-dashboard-calculation.php
echo.
echo.

echo Step 4: DEMO Pelunasan (DP + Pelunasan Update Dashboard)
echo ══════════════════════════════════════════════════════════════════
php DEMO_PELUNASAN.php
if %errorlevel% neq 0 goto :error
echo.
echo.

echo ╔══════════════════════════════════════════════════════════════════╗
echo ║                      ✅ ALL TESTS PASSED!                         ║
echo ╚══════════════════════════════════════════════════════════════════╝
echo.
echo 🎉 Dashboard sekarang menampilkan:
echo    • DP 250k + Pelunasan 500k = Total 750k per booking
echo    • Pendapatan otomatis update setiap ada pelunasan
echo.
echo 🔐 Login dan verifikasi:
echo    Admin: admin@gardenia.com / admin123
echo.
echo 📱 Akses: http://localhost/gardenia-kosla122/public
echo          atau sesuai konfigurasi Laragon Anda
echo.
goto :end

:error
echo.
echo ❌ TEST GAGAL!
echo Silakan periksa error di atas dan coba lagi.
echo.

:end
pause
