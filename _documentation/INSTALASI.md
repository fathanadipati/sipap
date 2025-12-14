# PANDUAN INSTALASI SIPAP

## Persyaratan Sistem

- **Web Server:** Apache dengan PHP 7.4+
- **Database:** MySQL 5.7+
- **Browser:** Chrome, Firefox, Safari, Edge (versi terbaru)
- **OS:** Windows, Linux, atau MacOS

## Langkah Instalasi

### 1. Persiapan XAMPP

**Pastikan XAMPP sudah terinstall dan berjalan:**
- Apache berjalan
- MySQL berjalan
- Port 80 dan 3306 tersedia

### 2. Download/Clone Aplikasi

Tempatkan folder `sipap` di direktori:
```
C:\xampp\htdocs\sipap
```

Struktur folder harus terlihat seperti:
```
C:\xampp\htdocs\
├── sipap/
│   ├── config/
│   ├── includes/
│   ├── modules/
│   ├── admin/
│   ├── assets/
│   ├── login.php
│   ├── dashboard.php
│   └── ... (file lainnya)
```

### 3. Setup Database

**Metode A: Menggunakan phpMyAdmin (Recommended)**

1. Buka `http://localhost/phpmyadmin`
2. Login dengan user `root` (password kosong jika default XAMPP)
3. Klik "Import" di menu atas
4. Pilih file `database.sql` dari folder sipap
5. Klik "Go" untuk menjalankan import
6. Database dan tabel akan otomatis tercipta

**Metode B: Menggunakan MySQL Command Line**

```bash
# Buka command prompt dan masuk folder XAMPP
cd C:\xampp\mysql\bin

# Login ke MySQL
mysql -u root -p

# Di prompt MySQL, jalankan:
source C:\xampp\htdocs\sipap\database.sql;
```

**Metode C: Menggunakan Setup Wizard**

1. Buka `http://localhost/sipap/setup.php` di browser
2. Ikuti petunjuk di layar
3. Sistem akan otomatis membuat tabel dan data demo

### 4. Konfigurasi Aplikasi

Edit file `config/database.php` jika perlu:

```php
define('DB_HOST', 'localhost');  // Alamat database
define('DB_USER', 'root');        // Username MySQL
define('DB_PASS', '');            // Password MySQL (kosong default)
define('DB_NAME', 'sipap_db');    // Nama database
```

**Catatan:** Jika database tidak bernama `sipap_db`, ubah sesuai dengan nama yang Anda gunakan saat import.

### 5. Verifikasi Instalasi

1. Buka browser: `http://localhost/sipap`
2. Seharusnya melihat halaman welcome SIPAP
3. Klik "Login" atau buka `http://localhost/sipap/login.php`

### 6. Login Pertama Kali

Gunakan salah satu akun berikut:

**Admin:**
- Username: `admin`
- Password: `password`

**Resepsionis:**
- Username: `resepsionis`
- Password: `password`

**Penghuni Demo:**
- Username: `penghuni` (jika sudah membuat data demo)
- Password: `password`

## Troubleshooting

### Error: "Koneksi gagal: Connection refused"

**Penyebab:** MySQL tidak berjalan atau konfigurasi database salah

**Solusi:**
1. Pastikan MySQL di XAMPP sudah berjalan (lihat di XAMPP Control Panel)
2. Cek file `config/database.php`
3. Pastikan `DB_HOST`, `DB_USER`, `DB_PASS` sesuai dengan setting MySQL Anda

### Error: "No such file or directory: database.sql"

**Penyebab:** File database.sql tidak ditemukan

**Solusi:**
1. Pastikan file `database.sql` ada di folder `C:\xampp\htdocs\sipap\`
2. Buka file dengan text editor untuk verifikasi
3. Jika file rusak, download kembali

### Error: "Access denied for user 'root'@'localhost'"

**Penyebab:** Password MySQL tidak sesuai

**Solusi:**
1. Cek password MySQL Anda (default XAMPP biasanya kosong)
2. Edit `config/database.php` dan sesuaikan `DB_PASS`
3. Atau reset password MySQL via XAMPP

### Halaman Blank atau Error 500

**Penyebab:** PHP error atau syntax error

**Solusi:**
1. Cek file `php.ini` di XAMPP
2. Aktifkan error reporting: `display_errors = On`
3. Restart Apache
4. Periksa error log di `C:\xampp\apache\logs\error.log`

### Login Gagal

**Penyebab:** Data user tidak ada di database

**Solusi:**
1. Verifikasi database sudah import dengan benar
2. Buka phpMyAdmin dan cek tabel `users`
3. Jalankan setup.php untuk membuat data demo
4. Pastikan password hashing tidak salah

### Notifikasi Tidak Muncul

**Penyebab:** JavaScript atau AJAX tidak berjalan

**Solusi:**
1. Cek console browser (F12 → Console)
2. Pastikan file `assets/js/script.js` ada
3. Reload halaman (Ctrl + F5)
4. Cek apakah `modules/notifikasi/get_notifikasi.php` accessible

## Post-Installation

### 1. Ganti Password Default

1. Login dengan akun admin
2. Buka menu "Profil Saya"
3. Hubungi administrator untuk mengubah password
4. **PENTING:** Ubah password semua akun default sebelum production

### 2. Tambah Data Penghuni

1. Login sebagai Admin
2. Buka "Kelola Penghuni" → "Tambah Penghuni"
3. Isi form dengan data penghuni apartemen
4. Sistem otomatis membuat akun pengguna

### 3. Setup Resepsionis

1. Login sebagai Admin
2. Buka "Kelola Pengguna" → "Tambah Pengguna"
3. Buat akun baru dengan role "Resepsionis"
4. Berikan ke staff resepsionis apartemen

### 4. Test Fitur Lengkap

1. **Test Login:** Coba login dengan 3 role berbeda
2. **Test CRUD Penghuni:** Admin - tambah, edit, hapus penghuni
3. **Test CRUD Paket:** Resepsionis - terima paket baru
4. **Test Notifikasi:** Login sebagai penghuni, lihat notifikasi
5. **Test Dashboard:** Cek setiap dashboard berdasarkan role

## Tips Keamanan

1. **Ganti Password:** Ubah semua password default segera
2. **Backup Database:** Backup `sipap_db` secara berkala
3. **HTTPS:** Gunakan HTTPS jika production
4. **Update PHP:** Selalu gunakan versi PHP terbaru
5. **Firewall:** Batasi akses ke port MySQL jika online

## Update Aplikasi

Untuk update ke versi terbaru:

1. Backup folder `sipap` saat ini
2. Backup database: `mysqldump -u root sipap_db > sipap_backup.sql`
3. Download versi terbaru
4. Copy file baru, preserve folder `config/` jika ada perubahan custom
5. Jalankan database migration jika ada (lihat CHANGELOG)

## Support

Jika mengalami masalah:

1. Periksa file `README.md` untuk dokumentasi lengkap
2. Cek error log di XAMPP
3. Buka browser console (F12) untuk JavaScript error
4. Hubungi administrator sistem

---

**SIPAP v1.0 - Instalasi Berhasil!**

Selamat menggunakan Sistem Penerimaan Paket Apartemen.
