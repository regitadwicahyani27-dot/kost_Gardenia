@echo off
echo ========================================
echo TEST LENGKAP - FITUR PEMBAYARAN OFFLINE
echo ========================================
echo.

echo Step 1: Refresh Database + Seed
echo ----------------------------------------
php refresh-db.php
if %errorlevel% neq 0 (
    echo FAILED at Step 1
    pause
    exit /b 1
)
echo.

echo Step 2: Run Migration (add cash enum)
echo ----------------------------------------
php artisan migrate --force
if %errorlevel% neq 0 (
    echo FAILED at Step 2
    pause
    exit /b 1
)
echo.

echo Step 3: Add Cash Payment Test
echo ----------------------------------------
php add-cash-payment-test.php
if %errorlevel% neq 0 (
    echo FAILED at Step 3
    pause
    exit /b 1
)
echo.

echo Step 4: Run Automated Test
echo ----------------------------------------
php test-manual-payment.php
if %errorlevel% neq 0 (
    echo FAILED at Step 4
    pause
    exit /b 1
)
echo.

echo ========================================
echo ALL TESTS PASSED!
echo ========================================
echo.
echo Dashboard sekarang tampil:
echo - Pendapatan bulan ini sudah include payment cash
echo - Semua booking dan payment tercatat lengkap
echo.
echo Login dan verifikasi:
echo Admin: admin@gardenia.com / admin123
echo.
pause
