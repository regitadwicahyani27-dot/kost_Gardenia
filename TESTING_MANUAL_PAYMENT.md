# 🧪 PANDUAN TESTING FITUR PEMBAYARAN OFFLINE

## 📋 Persiapan

### 1. Jalankan Migration
```bash
cd c:\laragon\www\gardenia-kosla122
php artisan migrate
```

**Expected Output:**
```
INFO  Running migrations.

2026_07_29_090521_add_cash_to_payment_method_enum ........... DONE
```

### 2. Pastikan Server Running
```bash
php artisan serve
# atau gunakan Laragon
```

---

## 🧪 SKENARIO TESTING

### Test Case 1: Catat Pembayaran Offline dari Dashboard Admin

**Langkah:**
1. Login sebagai admin (`admin@gardenia.com` / `admin123`)
2. Buka Dashboard Admin
3. Pastikan ada booking dengan status **"Dikonfirmasi"** atau **"Aktif"**
   - Jika belum ada, buat booking baru sebagai user terlebih dahulu
   - Login sebagai user, buat booking, upload bukti DP
   - Login kembali sebagai admin, verifikasi DP → status jadi "Dikonfirmasi"
4. Di tabel "Booking Terbaru", klik tombol **"Detail"** pada booking yang berstatus "Dikonfirmasi"
5. Modal akan muncul, scroll ke bagian **"Catat Pembayaran Offline"** (background hijau)
6. Klik untuk expand form
7. Perhatikan nominal sudah otomatis terisi = **sisa pembayaran** (misal: Rp 500.000)
8. Isi catatan: "Pelunasan tunai saat check-in"
9. Klik **"Simpan Pembayaran"**
10. Konfirmasi dialog → klik OK

**Expected Result:**
- ✅ Modal tertutup otomatis
- ✅ Muncul notifikasi success: "Pembayaran offline berhasil dicatat."
- ✅ Refresh halaman → statistik **"Pendapatan Bulan Ini"** bertambah Rp 500.000
- ✅ Booking status berubah menjadi **"Selesai"** (completed)

---

### Test Case 2: Catat Pembayaran dari Halaman Detail Booking

**Langkah:**
1. Dari dashboard admin, klik booking yang berstatus "Dikonfirmasi" atau "Aktif"
2. Atau akses langsung: `/admin/booking/{id}`
3. Scroll ke section **"Catat Pembayaran Offline"** (kotak hijau di bawah "Riwayat Pembayaran")
4. Isi nominal: `500000` (atau biarkan default)
5. Isi catatan: "Dibayar tunai saat check-in hari ini"
6. Klik **"Simpan Pembayaran Offline"**
7. Konfirmasi → klik OK

**Expected Result:**
- ✅ Redirect kembali ke halaman yang sama
- ✅ Notifikasi success muncul
- ✅ Section "Catat Pembayaran Offline" hilang (karena booking sudah completed)
- ✅ Di "Riwayat Pembayaran" muncul entry baru:
  - **Tipe:** "Penuh"
  - **Metode:** "Tunai (Offline)" ← penting!
  - **Status:** Badge hijau "Terverifikasi"
  - **Jumlah:** Rp 500.000
  - **Catatan:** Tampil di bawah info payment

---

### Test Case 3: Verifikasi Dashboard Terupdate

**Langkah:**
1. Setelah catat pembayaran offline, kembali ke Dashboard Admin
2. Perhatikan card **"Pendapatan Bulan Ini"**

**Expected Result:**
- ✅ Angka pendapatan bertambah Rp 500.000
- ✅ Counter "X pembayaran terverifikasi" bertambah +1
- ✅ Jika masuk bulan yang berbeda, pastikan hanya payment bulan ini yang dihitung

---

### Test Case 4: Verifikasi Halaman Riwayat Pembayaran

**Langkah:**
1. Dari navbar admin, klik tab **"Pembayaran"**
2. Atau akses: `/admin/pembayaran`
3. Klik tab **"Terverifikasi"**

**Expected Result:**
- ✅ Payment offline (cash) muncul di list
- ✅ Label metode: **"Tunai (Offline)"** (bukan "CASH")
- ✅ Status: Badge hijau "Terverifikasi"
- ✅ Tidak ada gambar bukti bayar (kotak abu-abu dengan icon credit card)
- ✅ Catatan ditampilkan (jika ada)
- ✅ Tertulis "Diverifikasi: [tanggal] · oleh [nama admin]"

---

### Test Case 5: View dari Sisi User

**Langkah:**
1. Logout dari admin
2. Login sebagai user yang bookingnya tadi dibuatkan payment offline
3. Klik **"Riwayat Booking"**
4. Klik booking yang sudah selesai
5. Atau akses: `/user/booking/{id}`
6. Scroll ke **"Riwayat Pembayaran"**

**Expected Result:**
- ✅ Muncul 2 payment:
  - **Payment 1:** DP · QRIS/DANA/BCA (tergantung pilihan user)
  - **Payment 2:** FULL · **Tunai (Offline)** ← ini yang dicatat admin
- ✅ Kedua payment berstatus "Terverifikasi" (badge hijau)
- ✅ Total kedua payment = total harga booking

---

### Test Case 6: Validasi Amount

**Langkah:**
1. Buka form catat pembayaran offline
2. Ubah nominal menjadi `0` atau `-100`
3. Submit form

**Expected Result:**
- ✅ Error validation muncul: "The amount field must be at least 0" atau sejenisnya
- ✅ Form tidak ter-submit

---

### Test Case 7: Validasi Status Booking

**Langkah:**
1. Coba akses form catat pembayaran untuk booking dengan status:
   - **"Pending"** → form tidak muncul ✅
   - **"Cancelled"** → form tidak muncul ✅
   - **"Completed"** → form tidak muncul ✅
2. Form **HANYA** muncul untuk status "Dikonfirmasi" atau "Aktif"

**Expected Result:**
- ✅ Form hanya visible untuk status yang tepat
- ✅ Tidak ada cara manual inject request untuk booking yang salah status

---

### Test Case 8: Transaction Atomicity

**Langkah:**
1. (Advanced) Sementara matikan database atau ubah konfigurasi untuk memicu error
2. Submit form catat pembayaran offline
3. Periksa database

**Expected Result:**
- ✅ Jika terjadi error, baik payment maupun booking status tidak berubah (rollback)
- ✅ Tidak ada data inconsistency

---

## 📊 CHECKLIST HASIL TESTING

| No | Test Case | Status | Catatan |
|----|-----------|--------|---------|
| 1 | Catat dari Modal Dashboard | ⬜ Pass / ⬜ Fail | |
| 2 | Catat dari Halaman Detail | ⬜ Pass / ⬜ Fail | |
| 3 | Dashboard Terupdate | ⬜ Pass / ⬜ Fail | |
| 4 | Riwayat Pembayaran Admin | ⬜ Pass / ⬜ Fail | |
| 5 | View dari Sisi User | ⬜ Pass / ⬜ Fail | |
| 6 | Validasi Amount | ⬜ Pass / ⬜ Fail | |
| 7 | Validasi Status Booking | ⬜ Pass / ⬜ Fail | |
| 8 | Transaction Atomicity | ⬜ Pass / ⬜ Fail | |

---

## 🐛 COMMON ISSUES & SOLUTIONS

### Issue 1: Migration Error "Column not found"
**Solution:** Pastikan migration dijalankan dengan benar. Cek dengan:
```bash
php artisan migrate:status
```

### Issue 2: Form tidak muncul
**Solution:** Pastikan booking memiliki status "confirmed" atau "active"

### Issue 3: Pendapatan tidak bertambah
**Solution:** Periksa:
- Payment status harus "verified" ✅
- Payment verified_at harus bulan ini ✅
- Refresh halaman dashboard

### Issue 4: Label masih "CASH" bukan "Tunai (Offline)"
**Solution:** Clear cache view:
```bash
php artisan view:clear
```

---

## ✅ SUCCESS CRITERIA

Fitur dinyatakan **BERHASIL** jika:
1. ✅ Migration berjalan tanpa error
2. ✅ Form catat pembayaran offline muncul di tempat yang tepat
3. ✅ Payment tercatat dengan `payment_method = 'cash'` dan `status = 'verified'`
4. ✅ Booking status otomatis jadi "completed"
5. ✅ Dashboard pendapatan bulan ini terupdate
6. ✅ Label "Tunai (Offline)" tampil di semua view (admin & user)
7. ✅ Tidak ada error di console browser atau Laravel log

---

## 📝 NOTES

- Setiap kali submit form, periksa file `storage/logs/laravel.log` untuk memastikan tidak ada error tersembunyi
- Gunakan browser DevTools (F12) → Network tab untuk monitoring request/response
- Test dengan berbagai browser (Chrome, Firefox, Edge) untuk compatibility

---

**Happy Testing! 🚀**
