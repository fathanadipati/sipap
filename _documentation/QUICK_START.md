# AureliaBox - Quick Start Guide

Panduan cepat untuk mulai menggunakan AureliaBox setelah instalasi.

## ⚡ Quick Setup (5 Menit)

### 1. Jalankan Setup Wizard
- Buka: `http://localhost/sipap/setup.php`
- Klik "Buat Data Demo" untuk membuat resident demo
- Sistem otomatis membuat tabel database

### 2. Login
- URL: `http://localhost/sipap/login.php`
- Gunakan salah satu akun:
  - Admin: `admin` / `password`
  - Receptionist: `resepsionis` / `password`
  - Resident: `penghuni` / `password`

### 3. Jelajahi Aplikasi
- Klik "Dashboard" untuk melihat overview
- Menu navigation di bagian atas

---

## 🎯 Panduan Penggunaan Cepat

### Untuk Admin 👨‍💼

```
Dashboard Admin
├── Lihat statistik paket, penghuni, pengguna
├── Akses: Admin → Kelola Penghuni (CRUD)
├── Akses: Admin → Kelola Paket (view)
└── Akses: Admin → Kelola Pengguna (CRUD)
```

**Tugas Utama:**
1. Tambah/Edit/Hapus penghuni
2. Manage user resepsionis
3. Monitor semua paket yang masuk
4. Check statistik sistem

### Untuk Resepsionis 📋

```
Dashboard Resepsionis
├── Lihat paket di loker
├── Terima Paket Baru
│   ├── Isi data pengirim & kurir
│   ├── Pilih unit penghuni
│   ├── Masukkan nomor loker
│   └── Sistem kirim notifikasi otomatis
└── Edit status paket (diterima → disimpan → diambil)
```

**Workflow Penerimaan Paket:**
1. Kurir menyerahkan paket
2. Buka "Terima Paket Baru"
3. Isi form dengan detail paket
4. Tentukan unit penghuni penerima
5. Masukkan nomor loker
6. Klik "Simpan Paket"
7. Notifikasi otomatis terkirim ke penghuni

### Untuk Penghuni 🏠

```
Dashboard Penghuni
├── Lihat paket yang masuk
├── Lihat notifikasi baru (bell icon)
├── Klik notifikasi untuk detail paket
├── Lihat lokasi loker penyimpanan
└── Tracking status paket
```

**Aktivitas Penghuni:**
1. Login kapan saja
2. Lihat dashboard untuk paket menunggu
3. Buka bell icon untuk notifikasi
4. Klik notifikasi untuk detail paket
5. Lihat nomor loker dan status
6. Ambil paket di loker yang ditunjukkan

---

## 🔄 Alur Kerja Sistem

```
Kurir Tiba
    ↓
Resepsionis Menerima Paket
    ↓
Input Data Paket
    ↓
Pilih Unit Penghuni
    ↓
Masukkan Nomor Loker
    ↓
Klik Simpan
    ↓
Notifikasi Otomatis ke Penghuni
    ↓
Penghuni Datang Mengambil Paket
    ↓
Resepsionis Update Status → "Diambil"
    ↓
Paket Selesai
```

---

## 📱 Fitur Utama

| Fitur | Admin | Resepsionis | Penghuni |
|-------|-------|------------|----------|
| Dashboard | ✅ | ✅ | ✅ |
| Kelola Penghuni | ✅ | ❌ | ❌ |
| Kelola Paket | ✅ | ✅ | ❌ |
| Terima Paket Baru | ❌ | ✅ | ❌ |
| Lihat Paket Saya | ✅ | ✅ | ✅ |
| Notifikasi Real-time | ✅ | ✅ | ✅ |
| Kelola Pengguna | ✅ | ❌ | ❌ |
| Profil Saya | ✅ | ✅ | ✅ |

---

## 🔧 Menu Navigation

### Menu Admin
```
Dashboard
Admin ▼
  ├── Kelola Penghuni
  ├── Kelola Paket
  └── Kelola Pengguna
Notifikasi 🔔
Profil ▼
  ├── Profil Saya
  └── Logout
```

### Menu Resepsionis
```
Dashboard
Terima Paket
Notifikasi 🔔
Profil ▼
  ├── Profil Saya
  └── Logout
```

### Menu Penghuni
```
Dashboard
Notifikasi 🔔
Profil ▼
  ├── Profil Saya
  └── Logout
```

---

## 💡 Tips Penggunaan

### Notifikasi
- Bell icon di navbar menampilkan notifikasi terbaru
- Klik notifikasi untuk melihat detail paket
- Auto-refresh setiap 5 detik
- Klik "Lihat semua" untuk riwayat lengkap

### Paket
- Filter berdasarkan status (Diterima/Disimpan/Diambil)
- Cari paket dengan no. paket, unit, atau pengirim
- Edit paket untuk update status
- View detail paket dengan timeline lengkap

### Penghuni
- Edit data penghuni kapan saja
- Nomor unit tidak bisa diubah
- Simpan kontak darurat untuk emergency

---

## ⚠️ Catatan Penting

1. **Password Default:** Ganti segera setelah setup
2. **Data Demo:** Hapus/edit setelah testing
3. **Backup:** Backup database secara berkala
4. **Browser:** Gunakan browser modern (Chrome/Firefox terbaru)
5. **Notifikasi:** Hanya untuk penghuni penerima paket

---

## 📞 Bantuan

Untuk pertanyaan:
- Baca README.md untuk dokumentasi lengkap
- Baca INSTALASI.md untuk troubleshooting
- Hubungi administrator sistem

---

**Selamat menggunakan SIPAP!** 🎉

SIPAP v1.0 - Sistem Penerimaan Paket Apartemen
