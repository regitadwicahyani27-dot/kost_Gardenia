# ============================================================
# Build Release ZIP untuk InfinityFree
# ============================================================
# Jalankan script ini di root project Laravel
# Output: deploy-infinityfree/gardenia-infinityfree-release.zip
# ============================================================

$ErrorActionPreference = "Stop"
$projectRoot = "c:\laragon\www\gardenia-kosla122"
$deployDir = "$projectRoot\deploy-infinityfree"
$stagingDir = "$deployDir\_staging"
$zipOutput = "$deployDir\gardenia-infinityfree-release.zip"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Building InfinityFree Release Package" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

# Bersihkan staging sebelumnya
if (Test-Path $stagingDir) { Remove-Item $stagingDir -Recurse -Force }
if (Test-Path $zipOutput) { Remove-Item $zipOutput -Force }

New-Item -ItemType Directory -Path $stagingDir -Force | Out-Null

Write-Host "`n[1/7] Menyalin file-file Laravel utama..." -ForegroundColor Yellow

# Folder yang perlu disalin
$folders = @("app", "bootstrap", "config", "database", "public", "resources", "routes", "storage", "vendor")
foreach ($folder in $folders) {
    $src = "$projectRoot\$folder"
    $dst = "$stagingDir\$folder"
    if (Test-Path $src) {
        Write-Host "  -> $folder" -ForegroundColor Gray
        Copy-Item $src $dst -Recurse -Force
    }
}

# File root yang diperlukan
$rootFiles = @("artisan", "composer.json", "composer.lock", "vite.config.js", "tailwind.config.js", "postcss.config.js", "package.json")
foreach ($file in $rootFiles) {
    $src = "$projectRoot\$file"
    if (Test-Path $src) {
        Copy-Item $src "$stagingDir\$file" -Force
    }
}

Write-Host "`n[2/7] Memasang .htaccess root redirect..." -ForegroundColor Yellow
Copy-Item "$deployDir\.htaccess.root" "$stagingDir\.htaccess" -Force

Write-Host "`n[3/7] Memasang .env template InfinityFree..." -ForegroundColor Yellow
Copy-Item "$deployDir\.env.infinityfree" "$stagingDir\.env" -Force

Write-Host "`n[4/7] Menyiapkan storage directory structure..." -ForegroundColor Yellow
# Pastikan struktur storage ada
$storageDirs = @(
    "$stagingDir\storage\app\public",
    "$stagingDir\storage\framework\cache\data",
    "$stagingDir\storage\framework\sessions",
    "$stagingDir\storage\framework\views",
    "$stagingDir\storage\logs"
)
foreach ($dir in $storageDirs) {
    New-Item -ItemType Directory -Path $dir -Force | Out-Null
}
# Buat .gitignore di storage agar folder tidak kosong
foreach ($dir in $storageDirs) {
    Set-Content -Path "$dir\.gitignore" -Value "*`n!.gitignore"
}

Write-Host "`n[5/7] Menyertakan file SQL database..." -ForegroundColor Yellow
$sqlFile = "$projectRoot\database\gardenia_kos.sql"
if (Test-Path $sqlFile) {
    Copy-Item $sqlFile "$stagingDir\database\gardenia_kos.sql" -Force
    Write-Host "  -> gardenia_kos.sql disertakan" -ForegroundColor Gray
} else {
    Write-Host "  -> WARNING: gardenia_kos.sql tidak ditemukan!" -ForegroundColor Red
}

Write-Host "`n[6/7] Membersihkan file yang tidak perlu..." -ForegroundColor Yellow
# Hapus file-file dev
$removeItems = @(
    "$stagingDir\storage\logs\*.log",
    "$stagingDir\.env.example",
    "$stagingDir\database\database.sqlite",
    "$stagingDir\public\storage"
)
foreach ($item in $removeItems) {
    if (Test-Path $item) { Remove-Item $item -Force -ErrorAction SilentlyContinue }
}

# Hapus folder-folder dev dari vendor (hemat space)
Get-ChildItem "$stagingDir\vendor" -Directory -Recurse -Filter "tests" | Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
Get-ChildItem "$stagingDir\vendor" -Directory -Recurse -Filter "Tests" | Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
Get-ChildItem "$stagingDir\vendor" -Directory -Recurse -Filter ".git" | Remove-Item -Recurse -Force -ErrorAction SilentlyContinue

Write-Host "`n[7/7] Membuat ZIP file..." -ForegroundColor Yellow
Compress-Archive -Path "$stagingDir\*" -DestinationPath $zipOutput -Force

# Bersihkan staging
Remove-Item $stagingDir -Recurse -Force

$zipSize = [math]::Round((Get-Item $zipOutput).Length / 1MB, 2)
Write-Host "`n========================================" -ForegroundColor Green
Write-Host " BERHASIL!" -ForegroundColor Green
Write-Host " Output: $zipOutput" -ForegroundColor Green
Write-Host " Ukuran: $zipSize MB" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
