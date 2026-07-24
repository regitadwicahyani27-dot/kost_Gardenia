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
