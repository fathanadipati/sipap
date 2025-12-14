# 🔐 AureliaBox LOGIN CREDENTIALS

Berikut adalah username dan password untuk mengakses sistem AureliaBox

---

## 👨‍💼 ADMIN ACCOUNT

**Untuk mengelola seluruh sistem**

```
Username: admin
Password: password
```

**Akses:**
- Dashboard admin dengan statistik lengkap
- Kelola penghuni (CRUD)
- Kelola paket (monitoring)
- Kelola pengguna sistem
- Lihat semua paket

**URL Login:** `http://localhost/sipap/login.php`

---

## 📋 RESEPSIONIS ACCOUNT

**Untuk menerima dan mencatat paket**

```
Username: resepsionis
Password: password
```

**Akses:**
- Dashboard resepsionis
- Terima paket baru (form input)
- Edit dan update status paket
- Lihat paket di loker
- List semua paket

**Tanggung Jawab:**
1. Menerima paket dari kurir
2. Mencatat detail paket di sistem
3. Menentukan nomor loker penyimpanan
4. Update status saat penghuni mengambil

---

## 🏠 PENGHUNI ACCOUNT

**Untuk penghuni apartemen (Demo)**

```
Username: penghuni
Password: password
```

**Akses:**
- Dashboard penghuni dengan paket pribadi
- Lihat notifikasi paket masuk
- Detail paket dan lokasi loker
- Tracking status paket

**Fitur:**
- Menerima notifikasi otomatis saat ada paket
- Lihat nomor loker tempat paket disimpan
- Tracking kapan paket diambil

**Note:** Username ini adalah data demo. Penghuni baru akan ditambahkan oleh admin melalui "Kelola Penghuni".

---

## 📊 TABEL RINGKAS

| Role | Username | Password | Fungsi |
|------|----------|----------|--------|
| Admin | `admin` | `password` | Kelola sistem & pengguna |
| Resepsionis | `resepsionis` | `password` | Terima & catat paket |
| Penghuni | `penghuni` | `password` | Lihat paket pribadi |

---

## 🔄 WORKFLOW LOGIN

### 1. Buka Browser
```
http://localhost/sipap
```

### 2. Klik Login
```
atau langsung ke: http://localhost/sipap/login.php
```

### 3. Masukkan Credentials
```
Username: [sesuai role]
Password: password
```

### 4. Klik "Login"
```
Akan redirect ke dashboard sesuai role
```

---

## ⚠️ CATATAN PENTING

### Password Default
- **Jangan gunakan di production!**
- Ganti segera setelah instalasi
- Gunakan password yang kuat
- Minimal 8 karakter dengan kombinasi

### Cara Ganti Password
1. Login dengan akun yang ingin diganti
2. Klik menu "Profil" → "Profil Saya"
3. Hubungi administrator untuk mengubah password
4. (Feature lengkap akan ditambahkan di versi berikutnya)

### Keamanan
- Username & password sudah di-hash di database
- Jangan share password dengan orang lain
- Logout setelah selesai menggunakan
- Gunakan browser yang aman

---

## 📝 TESTING CREDENTIALS

Untuk testing berbagai fitur, gunakan akun sesuai role:

### Test Sebagai Admin
```
Login: admin / password

Testing:
- Lihat statistik di dashboard
- Kelola penghuni (add, edit, delete)
- Kelola paket (view, edit)
- Kelola pengguna (add, edit, delete)
- Monitor semua aktivitas
```

### Test Sebagai Resepsionis
```
Login: resepsionis / password

Testing:
- Lihat dashboard resepsionis
- Buka "Terima Paket Baru"
- Input data paket
- Pilih unit penghuni
- Masukkan nomor loker
- Simpan paket
- Cek notifikasi ke penghuni
```

### Test Sebagai Penghuni
```
Login: penghuni / password

Testing:
- Lihat dashboard penghuni
- Cek paket yang masuk
- Lihat bell icon notifikasi
- Klik notifikasi untuk detail paket
- Lihat lokasi loker & status paket
```

---

## 🛠️ TROUBLESHOOTING

### "Login Gagal - Username Tidak Ditemukan"
**Penyebab:** Database belum di-import
**Solusi:**
1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Import file `database.sql`
3. Refresh halaman login
4. Coba lagi dengan username di atas

### "Login Gagal - Password Salah"
**Penyebab:** Password salah atau database rusak
**Solusi:**
1. Pastikan password adalah: `password` (kecil semua)
2. Hapus database dan import ulang `database.sql`
3. Coba lagi

### "Session Expired"
**Penyebab:** Terlalu lama tidak ada aktivitas
**Solusi:**
1. Refresh halaman atau login ulang
2. Gunakan akun lagi
3. Browser sudah clear cache (opsional)

---

## 📚 NEXT STEPS

Setelah berhasil login:

1. **Jelajahi Dashboard**
   - Lihat statistik sesuai role
   - Familiar dengan menu navigasi

2. **Coba Fitur Utama**
   - Admin: Kelola penghuni
   - Resepsionis: Terima paket
   - Penghuni: Lihat notifikasi

3. **Baca Dokumentasi**
   - README.md - Dokumentasi lengkap
   - QUICK_START.md - Panduan cepat
   - INSTALASI.md - Troubleshooting

4. **Setup Data Nyata**
   - Tambah penghuni sesuai data actual
   - Ganti password default
   - Setup resepsionis tambahan jika diperlukan

---

## 💡 TIPS

- **Gunakan akun admin** untuk setup awal
- **Gunakan akun resepsionis** untuk operasional harian
- **Gunakan akun penghuni** untuk melihat paket pribadi
- **Buat penghuni baru** melalui menu "Kelola Penghuni"
- **Jangan hapus akun admin** agar sistem tetap bisa diakses

---

## 📞 BANTUAN

Jika ada masalah:
1. Baca INSTALASI.md untuk troubleshooting lengkap
2. Cek error message yang muncul
3. Verifikasi database sudah ter-import
4. Coba clear browser cache (Ctrl+Shift+Del)

---

**SIPAP v1.0 - Login Credentials Ready** ✅

Silakan login dan nikmati sistem penerimaan paket apartemen yang profesional! 🚀
