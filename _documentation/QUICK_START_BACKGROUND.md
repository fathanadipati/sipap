# 🚀 Quick Start: Upload Background Login SIPAP

## 📋 Ringkasan
Anda telah membuat gambar background untuk halaman login SIPAP. Sekarang ikuti langkah-langkah berikut untuk menerapkannya.

## ⚡ Langkah-Langkah Cepat

### **1️⃣ Login ke Admin Dashboard**
```
URL: http://localhost/sipap/
Username: [akun admin]
Password: [password admin]
Login
```

### **2️⃣ Akses Halaman Upload Background**
Opsi A - Langsung:
```
http://localhost/sipap/admin/background.php
```

Opsi B - Via Menu (jika tersedia):
- Cari menu "Background" atau "Kelola Tampilan" di sidebar admin

### **3️⃣ Upload Gambar Anda**
1. Klik tombol **"Pilih File Gambar"**
2. Cari file gambar yang sudah Anda buat
3. Pastikan format adalah: **JPG, PNG, atau WebP**
4. Klik **"Upload Background"**
5. Tunggu pesan sukses ✅

### **4️⃣ Verifikasi Hasilnya**
1. **Logout** dari sistem
2. Buka `http://localhost/sipap/`
3. Lihat halaman login dengan background baru Anda!

## ✅ Checklist

- [ ] File gambar sudah disiapkan
- [ ] Format: JPG, PNG, atau WebP
- [ ] Ukuran file: < 5MB
- [ ] Resolusi: minimal 1920×1080px
- [ ] Teks login tetap terbaca (kontras baik)
- [ ] Sudah login sebagai admin
- [ ] Upload file melalui `/admin/background.php`
- [ ] Melihat pesan "Background berhasil di-upload"
- [ ] Logout dan verifikasi halaman login

## 🎨 Tips Kualitas Gambar

Jika gambar tidak sesuai, pertimbangkan:

**Terlalu Gelap?**
- Upload gambar dengan warna lebih cerah
- Pastikan teks putih tetap terlihat jelas

**Tidak Tertampil Sepenuhnya?**
- Gunakan resolusi minimal 1920×1080px
- Pastikan proporsi aspect ratio wajar

**Loading Lambat?**
- Kompres gambar menggunakan [TinyPNG.com](https://tinypng.com)
- Konversi ke format WebP untuk ukuran lebih kecil

## 🔍 Troubleshooting Cepat

### **Error: "Tipe file tidak diizinkan"**
✅ Solusi: Gunakan JPG, PNG, atau WebP (jangan BMP, GIF, SVG)

### **Error: "Ukuran file terlalu besar"**
✅ Solusi: Kompres gambar ke ukuran < 5MB (rekomendasi < 1MB)

### **Background tidak muncul setelah upload**
✅ Solusi:
1. Refresh halaman login (F5)
2. Clear cache browser: **Ctrl+Shift+Delete**
3. Cek browser console (F12) untuk error

### **Background masih menunjukkan gradient biru lama**
✅ Solusi:
1. Pastikan upload benar-benar sukses (lihat pesan)
2. Logout dan login ulang
3. Coba buka browser tab baru
4. Clear cache browser

## 📁 Informasi Teknis

- **Upload Path**: `/admin/background.php`
- **Storage**: `/assets/images/background-login.[ext]`
- **Login Page**: `/index.php`
- **Fallback**: Jika tidak ada background, tetap tampil gradient default (tidak error)

## 💡 Fitur Bonus

- ✅ **Instant Preview** - Lihat preview halaman login di halaman upload
- ✅ **File Size Monitor** - Lihat ukuran file background yang terpakai
- ✅ **Fallback System** - Jika ada masalah, tetap ada gradient default
- ✅ **Easy Replace** - Upload file baru otomatis mengganti yang lama

## 📞 Bantuan Lebih Lanjut

Untuk info lengkap, baca:
- 📖 [UPLOAD_BACKGROUND_GUIDE.md](./UPLOAD_BACKGROUND_GUIDE.md) - Panduan detail
- 📖 [CARA_MENGGUNAKAN_BACKGROUND.md](./CARA_MENGGUNAKAN_BACKGROUND.md) - Tips CSS custom

## 🎉 Selesai!

Selamat! Background login SIPAP Anda sudah siap. Tampilan awal sistem sekarang lebih menarik dengan branding Anda sendiri! 

---

**Pertanyaan?** Lihat file dokumentasi atau hubungi admin sistem.
