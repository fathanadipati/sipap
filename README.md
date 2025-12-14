# AureliaBox - Smart Package Management System

## Deskripsi Sistem
AureliaBox adalah sebuah sistem manajemen paket yang modern dan elegan untuk mengelola alur pengiriman paket di apartemen premium. Sistem ini memudahkan pengelolaan paket dari penerimaan kurir hingga pengambilan oleh penghuni dengan antarmuka yang user-friendly dan fitur notifikasi real-time.

Dikembangkan khusus untuk **THE GRAND AURELIA RESIDENCE** - Tempat tinggal dengan standar premium dan layanan terbaik.

## Fitur Utama

### 1. Autentikasi & Role Management
- Login dengan 3 role: Admin, Receptionist, Resident
- Password menggunakan bcrypt hashing
- Session management yang aman
- Kontrol akses berdasarkan role

### 2. Dashboard
- Dashboard khusus untuk setiap role
- Statistik paket real-time
- Quick access ke fitur utama
- Informasi ringkas untuk setiap pengguna

### 3. Kelola Penghuni (Admin)
- CRUD lengkap untuk data penghuni
- Informasi unit, kontak, dan kontak darurat
- Integrasi dengan akun pengguna

### 4. Kelola Paket (Admin & Resepsionis)
- Menerima paket baru dengan detail lengkap
- Pencatatan kurir, ekspedisi, dan jenis paket
- Penyimpanan di loker dengan nomor unik
- Update status paket (Diterima → Disimpan → Diambil)
- Filter dan pencarian paket

### 5. Sistem Notifikasi
- Notifikasi otomatis ke penghuni saat paket tiba
- Bell icon di navbar dengan badge count
- Daftar notifikasi dengan status baca/belum baca
- Notifikasi real-time setiap 5 detik

### 6. Profil Pengguna
- Edit profil (nama, email)
- Lihat informasi akun
- Manajemen password

## Struktur Database

### Tabel Users
- id (PK)
- username (UNIQUE)
- email (UNIQUE)
- password (hashed)
- role (admin, resepsionis, penghuni)
- nama_lengkap
- is_active
- created_at, updated_at

### Tabel Penghuni
- id (PK)
- user_id (FK)
- nomor_unit (UNIQUE)
- nomor_hp
- blok
- lantai
- nama_kontak_darurat
- nomor_kontak_darurat
- created_at, updated_at

### Tabel Paket
- id (PK)
- nomor_paket (UNIQUE)
- penghuni_id (FK)
- nama_pengirim
- nama_kurir
- nama_ekspedisi
- jenis_paket (makanan_minuman, barang_umum, barang_khusus)
- deskripsi
- nomor_loker
- status (diterima, disimpan, diambil)
- resepsionis_id (FK)
- tanggal_terima
- tanggal_diambil
- catatan
- created_at, updated_at

### Tabel Notifikasi
- id (PK)
- penghuni_id (FK)
- paket_id (FK)
- pesan
- is_read
- created_at

## Struktur Folder

```
sipap/
├── config/
│   ├── database.php        (Koneksi MySQL)
│   └── session.php         (Session & autentikasi)
├── includes/
│   ├── header.php          (HTML header)
│   ├── navbar.php          (Navigation bar)
│   └── footer.php          (HTML footer)
├── modules/
│   ├── penghuni/
│   │   ├── list.php        (Daftar penghuni)
│   │   ├── add.php         (Tambah penghuni)
│   │   ├── edit.php        (Edit penghuni)
│   │   └── delete.php      (Hapus penghuni)
│   ├── paket/
│   │   ├── list.php        (Daftar paket)
│   │   ├── add.php         (Terima paket baru)
│   │   ├── edit.php        (Edit paket)
│   │   ├── view.php        (Detail paket)
│   │   └── delete.php      (Hapus paket)
│   └── notifikasi/
│       ├── list.php        (Daftar notifikasi)
│       ├── get_notifikasi.php (API get notifikasi)
│       ├── mark_read.php   (API tandai baca)
│       └── clear_all.php   (Tandai semua baca)
├── admin/
│   ├── users.php           (Kelola pengguna)
│   ├── users_add.php       (Tambah pengguna)
│   ├── users_edit.php      (Edit pengguna)
│   └── users_delete.php    (Hapus pengguna)
├── assets/
│   ├── css/
│   │   └── style.css       (Custom styling)
│   ├── js/
│   │   └── script.js       (Custom JavaScript)
│   └── images/             (Folder untuk gambar)
├── login.php               (Halaman login)
├── logout.php              (Logout process)
├── dashboard.php           (Dashboard utama)
├── profile.php             (Profil pengguna)
├── forbidden.php           (Halaman akses ditolak)
├── index.php               (Home page)
├── database.sql            (SQL schema)
└── README.md               (Dokumentasi ini)
```

## Akun Demo

### Admin
- Username: `admin`
- Password: `password`

### Resepsionis
- Username: `resepsionis`
- Password: `password`

### Penghuni
- Username: `penghuni`
- Password: `password`

## Instalasi

1. **Copy folder sipap ke htdocs**
   ```
   C:\xampp\htdocs\sipap\
   ```

2. **Buat database MySQL**
   - Buka phpMyAdmin atau MySQL Workbench
   - Jalankan script `database.sql`
   - Atau import file database.sql langsung

3. **Konfigurasi database**
   - Edit `config/database.php`
   - Sesuaikan DB_HOST, DB_USER, DB_PASS, DB_NAME

4. **Akses aplikasi**
   - Buka browser: `http://localhost/sipap`
   - Login dengan akun demo

## Penggunaan

### Sebagai Admin
1. Login dengan akun admin
2. Akses dashboard untuk melihat statistik
3. Kelola penghuni di "Kelola Penghuni"
4. Kelola pengguna di "Kelola Pengguna"
5. Monitor semua paket di "Kelola Paket"

### Sebagai Resepsionis
1. Login dengan akun resepsionis
2. Terima paket baru melalui "Terima Paket"
3. Isikan detail paket dan pilih unit penghuni
4. Sistem otomatis mengirim notifikasi ke penghuni
5. Update status paket saat penghuni mengambil

### Sebagai Penghuni
1. Login dengan akun penghuni
2. Lihat paket yang masuk di dashboard
3. Terima notifikasi real-time saat ada paket baru
4. Akses detail paket dan lokasi lokernya
5. Tracking status paket

## Fitur Keamanan

- Password hashing dengan bcrypt (password_hash)
- SQL Injection prevention dengan prepared statements
- Session-based authentication
- CSRF protection dengan form handling
- XSS prevention dengan htmlspecialchars
- Role-based access control
- Input validation

## Teknologi yang Digunakan

- **Backend:** PHP Native (OOP)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 5
- **Icons:** Bootstrap Icons
- **Features:** AJAX, JSON API

## Changelog

### v1.0 (Initial Release)
- Autentikasi & login system
- Modul penghuni CRUD
- Modul paket CRUD
- Sistem notifikasi real-time
- Dashboard untuk 3 role
- Manajemen pengguna admin
- Profile management

## Pengembangan Selanjutnya (Optional)

- [ ] Export laporan ke Excel/PDF
- [ ] Email notification
- [ ] SMS notification
- [ ] QR code tracking
- [ ] Mobile app
- [ ] Analytics & reporting
- [ ] Multi-bahasa support
- [ ] Dark mode
- [ ] API documentation
- [ ] Unit testing

## Support & Kontribusi

Untuk masalah atau saran pengembangan, silakan hubungi administrator sistem.

## Lisensi

SIPAP © 2025 - Sistem Penerimaan Paket Apartemen

---

**Dokumentasi ini dibuat untuk memudahkan pemahaman dan penggunaan sistem SIPAP.**
