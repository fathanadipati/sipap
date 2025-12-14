# Panduan Menambah Akun Resident Baru

## ⚠️ Penting: Flow Pembuatan Resident yang Benar

Sistem AureliaBox memiliki cara khusus untuk menambah akun resident (penghuni) baru. Ada **dua cara** yang bisa dilakukan:

---

## Cara 1: Melalui Menu Penghuni (Recommended ✅)

### Langkah-langkah:

1. **Login sebagai Admin**
   - Masukkan username dan password admin

2. **Buka Menu Penghuni**
   - Di navbar, klik **"Data Master"** → **"Penghuni"**
   - Atau buka URL: `http://localhost/sipap/modules/penghuni/list.php`

3. **Klik Tombol "Tambah Penghuni Baru"**
   - Lokasi: pojok kanan atas halaman list penghuni
   - Warna: tombol biru dengan icon `+`

4. **Isi Form Data Penghuni**
   
   **Bagian 1: Data Akun Login**
   - **Nama Lengkap**: Nama lengkap penghuni (contoh: "Budi Santoso")
   - **Username**: Username unik untuk login (contoh: "budi.santoso")
   - **Email**: Email yang valid (contoh: "budi@email.com")
   - **Password**: Password untuk login penghuni (minimal 8 karakter, gunakan kombinasi huruf & angka)

   **Bagian 2: Data Unit Hunian**
   - **Nomor Unit**: Nomor unit yang ditinggali (contoh: "A-101", "B-205")
   - **Blok**: Nama blok (contoh: "A", "B", "C")
   - **Lantai**: Nomor lantai (contoh: "1", "2", "10")

   **Bagian 3: Data Kontak**
   - **Nomor HP**: Nomor telepon/whatsapp penghuni
   - **Nama Kontak Darurat**: Nama orang yang dapat dihubungi jika ada masalah
   - **Nomor Kontak Darurat**: Nomor telepon kontak darurat

5. **Klik Tombol "Simpan"**
   - Sistem akan membuat akun pengguna dan profil penghuni sekaligus
   - Akan diarahkan ke daftar penghuni dengan pesan sukses

6. **Penghuni Bisa Login**
   - Penghuni dapat login dengan username dan password yang telah dibuat
   - Dashboard akan menampilkan statistik paket mereka

---

## Cara 2: Melalui Menu Users Admin (Tidak Recommended ❌)

⚠️ **PERHATIAN**: Metode ini **TIDAK DISARANKAN** karena:
- Hanya membuat akun user, tidak membuat profil penghuni
- Resident tidak akan bisa melihat data paket mereka
- Dashboard akan menampilkan pesan "Profil Belum Dibuat"

Jika Anda secara tidak sengaja menggunakan cara ini:
1. Harus kembali ke menu Penghuni dan membuat profil secara terpisah
2. Atau hapus akun user dan buat ulang melalui Cara 1

---

## 📋 Checklist Sebelum Menambah Resident

- ✅ Login sebagai admin
- ✅ Siapkan data lengkap penghuni (nama, username, email, no unit, etc.)
- ✅ Password yang kuat (minimal 8 karakter)
- ✅ Gunakan **Cara 1** (melalui menu Penghuni)

---

## ❓ Jika Resident Melihat Pesan "Profil Belum Dibuat"

Ini berarti akun user sudah ada, tapi profil penghuni belum dibuat.

### Solusi:

**Untuk Admin:**
1. Buka menu **"Data Master"** → **"Penghuni"**
2. Klik **"Tambah Penghuni Baru"**
3. Isi **user_id** dengan ID dari user yang sudah ada
4. Atau hubungi dengan developer untuk membuat profil manual

**Atau gunakan Query SQL langsung (jika tahu SQL):**
```sql
INSERT INTO penghuni (user_id, nomor_unit, nomor_hp, blok, lantai) 
VALUES (user_id_yang_ingin_ditambahkan, 'A-101', '081234567890', 'A', '1');
```

---

## 🔄 Alur Data yang Benar

```
┌─────────────────────────────────────┐
│  Admin Klik "Tambah Penghuni Baru"  │
└────────────────┬────────────────────┘
                 │
                 ▼
    ┌────────────────────────────┐
    │   Isi Form Data Penghuni   │
    │  (nama, unit, kontak, dll) │
    └────────────┬───────────────┘
                 │
                 ▼
    ┌────────────────────────────┐
    │  INSERT users table        │ ← Buat akun login
    │  + INSERT penghuni table   │ ← Buat profil penghuni
    └────────────┬───────────────┘
                 │
                 ▼
    ┌────────────────────────────┐
    │  Resident Bisa Login       │
    │  Dashboard Penuh Fitur ✅  │
    └────────────────────────────┘
```

---

## 📞 Troubleshooting

### P: Resident login tapi dashboard kosong/blank
**J:** Profil penghuni belum dibuat. Admin harus membuat profil melalui menu Penghuni.

### P: Username sudah terdaftar padahal baru pertama kali
**J:** Check username di database atau coba username lain yang lebih unik.

### P: Password lupa
**J:** Admin bisa reset password melalui menu Users → Edit User.

### P: Resident tidak bisa melihat menu Penghuni/Paket mereka
**J:** Check role pengguna, harus "penghuni" (resident) bukan "admin" atau "receptionist".

---

## 📝 Catatan Penting

- **Nomor Unit** harus unik, tidak boleh duplikat
- **Username** harus unik dan mudah diingat
- **Password** disimpan dengan hash, tidak bisa dilihat kembali
- Data **Blok** dan **Lantai** berguna untuk manajemen fisik
- **Kontak Darurat** penting untuk notifikasi penting

---

**Terakhir diupdate:** December 14, 2025  
**Versi:** 1.0
