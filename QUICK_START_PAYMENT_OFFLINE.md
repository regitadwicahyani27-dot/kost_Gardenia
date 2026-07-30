# ⚡ Quick Start - Fitur Pembayaran Offline

Panduan cepat untuk menjalankan dan testing fitur pembayaran offline.

---

## 🚀 Installation (3 Langkah)

### 1️⃣ Jalankan Migration
```bash
cd c:\laragon\www\gardenia-kosla122
php artisan migrate
```

✅ **Expected:** Migration `add_cash_to_payment_method_enum` berhasil

### 2️⃣ (Optional) Setup Data Testing
```bash
php setup-test-data.php
```

✅ **Expected:** Booking test dengan status "Confirmed" dibuat

### 3️⃣ (Optional) Run Automated Test
```bash
php test-manual-payment.php
```

✅ **Expected:** All tests PASSED (7/7)

---

## 🎯 Cara Menggunakan

### Untuk Admin:

#### **Via Dashboard:**
1. Login sebagai admin
2. Dashboard → Tabel "Booking Terbaru"
3. Klik **"Detail"** pada booking berstatus **"Dikonfirmasi"**
4. Scroll ke **"Catat Pembayaran Offline"** (background hijau)
5. Klik untuk expand form
6. Isi nominal (auto-fill) & catatan
7. Klik **"Simpan Pembayaran"**

#### **Via Detail Booking:**
1. Login sebagai admin
2. Akses `/admin/booking/{id}`
3. Scroll ke section **"Catat Pembayaran Offline"**
4. Isi form & submit

### Untuk User:

User dapat melihat pembayaran offline di:
- `/user/booking/{id}` → Section "Riwayat Pembayaran"
- Label: **"FULL · Tunai (Offline)"**

---

## ✅ Verification Checklist

Setelah catat pembayaran Rp 500.000:

- [ ] Dashboard "Pendapatan Bulan Ini" **+Rp 500.000**
- [ ] Counter "X pembayaran terverifikasi" **+1**
- [ ] Booking status jadi **"Selesai"** (Completed)
- [ ] Muncul di **Riwayat Pembayaran** (admin & user)
- [ ] Label metode: **"Tunai (Offline)"** (bukan "CASH")
- [ ] Status payment: **"Terverifikasi"** (badge hijau)

---

## 🐛 Troubleshooting

### Migration Error?
```bash
# Cek status migration
php artisan migrate:status

# Rollback & re-run (jika perlu)
php artisan migrate:rollback --step=1
php artisan migrate
```

### Form tidak muncul?
- Pastikan booking status = **"Dikonfirmasi"** atau **"Aktif"**
- Booking "Pending", "Cancelled", "Completed" tidak bisa dicatat lagi

### Pendapatan tidak bertambah?
- Refresh halaman dashboard
- Periksa payment: `status = 'verified'` dan `verified_at` bulan ini

### Label masih "CASH"?
```bash
# Clear view cache
php artisan view:clear
```

---

## 📊 Test Data Info

Jika sudah run `php setup-test-data.php`:

**Login:**
- Admin: `admin@gardenia.com` / `admin123`
- User: `user@test.com` / `password`

**Booking:**
- Status: Confirmed
- Total: Rp 750.000
- DP: Rp 250.000
- **Sisa: Rp 500.000** ← ini yang dicatat offline

---

## 🎉 Success!

Jika semua checklist ✅, maka fitur **siap digunakan di production**!

**Next Steps:**
- Deploy ke server production
- Training admin cara menggunakan fitur
- Monitor Laravel logs untuk error

---

**Butuh bantuan?** Baca `TESTING_MANUAL_PAYMENT.md` untuk detail lengkap.
