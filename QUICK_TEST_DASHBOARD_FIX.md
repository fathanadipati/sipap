# 🎬 Quick Test: Lihat Perbaikan Dashboard

## Cara Memverifikasi Fix

### ✅ Test 1: Resident Tanpa Profil (Expected: Alert + Cards Faded)

1. **Login dengan akun resident yang BELUM punya profil penghuni**
   - URL: `http://localhost/sipap/dashboard.php`
   - Contoh user: "resident_baru" (jika ada)

2. **Yang seharusnya terlihat:**
   - ⚠️ Alert Warning KUNING dengan icon `⚠️`
   - Judul: "Profil Penghuni Belum Dibuat"
   - Penjelasan: "Untuk menggunakan sistem ini, admin perlu membuat profil..."
   - Link: "Debug Info" (untuk development)
   - Tombol close (X)

3. **Cards di bawah alert:**
   - Terlihat 3 card (Paket Menunggu, Paket Diambil, Panduan)
   - Tapi dengan opacity lebih rendah (terlihat faded/disabled)
   - Tidak ada tombol atau interaksi

---

### ✅ Test 2: Admin Lihat Alert (Expected: Alert + Link ke Residents)

1. **Login sebagai admin**
   - URL: `http://localhost/sipap/dashboard.php`

2. **Jika ada resident tanpa profil, akan melihat:**
   - ⚠️ Alert Warning di atas cards
   - Text: "Ada X Resident Tanpa Profil Penghuni"
   - Tombol/Link: "Lihat Daftar Resident" (warna warning)

3. **Klik link tersebut:**
   - Akan membuka: `http://localhost/sipap/admin/residents_without_profile.php`
   - Menampilkan tabel resident tanpa profil
   - Tombol `+` untuk membuat profil

---

### ✅ Test 3: Resident Dengan Profil (Expected: Dashboard Normal)

1. **Login dengan resident yang SUDAH punya profil**
   - Harus ada di tabel `penghuni` dengan `user_id` sesuai

2. **Yang seharusnya terlihat:**
   - ✅ TIDAK ada alert warning
   - Dashboard normal dengan statistik
   - Statistik: "Paket Menunggu", "Paket Diambil", etc.
   - Tabel "Paket Saya (5 Terbaru)" dengan data

---

### ✅ Test 4: Access residents_without_profile.php (Admin Only)

1. **Link: `http://localhost/sipap/admin/residents_without_profile.php`**

2. **Jika sudah login sebagai admin:**
   - Halaman terbuka normal
   - Jika ada resident tanpa profil: Tampil tabel
   - Jika tidak ada: Tampil pesan "Sempurna! Semua resident punya profil"

3. **Jika belum login atau bukan admin:**
   - Redirect ke login
   - Atau error "Akses Ditolak"

---

### ✅ Test 5: Debug Page (Development Only)

1. **Link: `http://localhost/sipap/debug_resident_profile.php`**

2. **Halaman menampilkan:**
   - Session Information (User ID, Role, Username)
   - User Data in Database
   - Penghuni Profile Data
   - Total Paket untuk penghuni ini
   - Solusi jika profil belum dibuat

3. **Berguna untuk:**
   - Check apakah user ada di database
   - Check apakah profil penghuni ada
   - Debugging masalah connectivity

---

## 🔗 Navbar Menu Check

### Admin Menu
1. **Buka dropdown: "Kelola"**
2. **Seharusnya muncul opsi:**
   - ✅ Kelola Penghuni
   - ✅ **Resident Tanpa Profil** ⬅️ BARU (warning color)
   - ✅ Kelola Paket
   - ✅ Kelola Staff
   - ✅ Kelola Tampilan

---

## 📊 Verification Checklist

- [ ] Login dengan resident tanpa profil → Alert muncul
- [ ] Dashboard tidak kosong → Ada card & alert
- [ ] Login sebagai admin → Alert resident tanpa profil muncul
- [ ] Klik link → residents_without_profile.php terbuka
- [ ] Navbar punya menu "Resident Tanpa Profil"
- [ ] Debug page accessible via link
- [ ] Resident dengan profil → Dashboard normal
- [ ] Clear cache → Alert tetap muncul

---

## 🐛 Jika Masih Ada Masalah

1. **Alert tidak muncul?**
   - Clear cache: Ctrl+Shift+Delete (pilih "All time")
   - Refresh: Ctrl+F5 (hard refresh)
   - Check PHP error log

2. **Menu tidak muncul?**
   - Verify navbar.php sudah disave
   - Restart server Apache
   - Check error di browser console (F12)

3. **Database issue?**
   - Run query di phpmyadmin
   - Check tabel users & penghuni exist
   - Verify kolom user_id ada di penghuni

---

**Tested on:** December 14, 2025
**Browser Recommended:** Chrome, Firefox, Edge (latest)

