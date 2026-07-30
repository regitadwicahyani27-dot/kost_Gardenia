@echo off
echo ========================================
echo FORCE CLEAR ALL CACHE
echo ========================================
echo.

cd /d c:\laragon\www\gardenia-kosla122

echo [Step 1/5] Deleting compiled views...
del /f /s /q storage\framework\views\*.php 2>nul

echo [Step 2/5] Clearing view cache...
php artisan view:clear

echo [Step 3/5] Clearing application cache...
php artisan cache:clear

echo [Step 4/5] Clearing config cache...
php artisan config:clear

echo [Step 5/5] Clearing route cache...
php artisan route:clear

echo.
echo ========================================
echo ✅ ALL CACHE CLEARED!
echo ========================================
echo.
echo NEXT STEPS:
echo 1. Close this window
echo 2. Restart Laragon (Stop All, then Start All)
echo 3. Close ALL browser windows
echo 4. Open browser in INCOGNITO mode
echo 5. Login as user and test
echo.
pause
