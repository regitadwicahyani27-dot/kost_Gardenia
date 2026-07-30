@echo off
cls
echo.
echo ╔══════════════════════════════════════════════════════════════════╗
echo ║         PLAYWRIGHT E2E TESTS - Kos Putri Gardenia                ║
echo ║          Manual Payment Verification System Testing              ║
echo ╚══════════════════════════════════════════════════════════════════╝
echo.

REM Check if Node.js is installed
where node >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Node.js is not installed!
    echo Please install Node.js from https://nodejs.org/
    pause
    exit /b 1
)

echo ✅ Node.js detected: 
node --version
echo.

REM Check if npm packages are installed
if not exist "node_modules\" (
    echo 📦 Installing npm dependencies...
    call npm install
    if %errorlevel% neq 0 (
        echo ❌ npm install failed
        pause
        exit /b 1
    )
    echo.
)

REM Check if Playwright browsers are installed
if not exist "node_modules\playwright\.local-browsers\" (
    echo 🎭 Installing Playwright browsers...
    call npm run test:install
    if %errorlevel% neq 0 (
        echo ❌ Playwright installation failed
        pause
        exit /b 1
    )
    echo.
)

REM Check if Laravel server is running
echo 🔍 Checking Laravel server...
curl -s http://localhost:8000 >nul 2>nul
if %errorlevel% neq 0 (
    echo ⚠️  Laravel server not running!
    echo Starting Laravel server...
    start "Laravel Server" cmd /c "php artisan serve --port=8000"
    timeout /t 5 /nobreak >nul
    echo ✅ Laravel server started
    echo.
)

echo ══════════════════════════════════════════════════════════════════
echo Select test mode:
echo ══════════════════════════════════════════════════════════════════
echo 1. Run all tests (headless)
echo 2. Run tests with browser visible (headed)
echo 3. Run tests in debug mode (step-by-step)
echo 4. Run tests in UI mode (interactive)
echo 5. View last test report
echo 6. Exit
echo ══════════════════════════════════════════════════════════════════
echo.

set /p choice="Enter your choice (1-6): "

if "%choice%"=="1" goto run_headless
if "%choice%"=="2" goto run_headed
if "%choice%"=="3" goto run_debug
if "%choice%"=="4" goto run_ui
if "%choice%"=="5" goto show_report
if "%choice%"=="6" goto end

:run_headless
echo.
echo 🧪 Running E2E tests (headless mode)...
echo ══════════════════════════════════════════════════════════════════
call npm run test:e2e
goto after_test

:run_headed
echo.
echo 🧪 Running E2E tests (with browser visible)...
echo ══════════════════════════════════════════════════════════════════
call npm run test:e2e:headed
goto after_test

:run_debug
echo.
echo 🐛 Running E2E tests (debug mode)...
echo ══════════════════════════════════════════════════════════════════
call npm run test:e2e:debug
goto after_test

:run_ui
echo.
echo 🎨 Running E2E tests (UI mode)...
echo ══════════════════════════════════════════════════════════════════
call npm run test:e2e:ui
goto after_test

:show_report
echo.
echo 📊 Opening test report...
call npm run test:e2e:report
goto end

:after_test
echo.
echo ══════════════════════════════════════════════════════════════════
if %errorlevel% equ 0 (
    echo ✅ ALL TESTS PASSED!
) else (
    echo ❌ SOME TESTS FAILED
    echo Check the report for details: npm run test:e2e:report
)
echo ══════════════════════════════════════════════════════════════════
echo.
set /p open_report="Open test report? (y/n): "
if /i "%open_report%"=="y" (
    call npm run test:e2e:report
)

:end
echo.
echo Thank you for using Playwright E2E Tests! 🎭
pause
