# 📸 Panduan Upload Background Halaman Login

## 🎨 Fitur Background Upload

Sistem SIPAP kini dilengkapi dengan fitur upload background untuk halaman login. Admin dapat mengganti tampilan halaman awal dengan gambar custom.

## 🚀 Cara Menggunakan

### **Step 1: Login sebagai Admin**
- Masuk ke sistem dengan akun admin
- Akses: `http://localhost/sipap/admin/background.php`
- Atau melalui dashboard admin

### **Step 2: Upload Gambar Background**

1. **Pilih File Gambar**
   - Klik tombol "Pilih File Gambar"
   - Pilih file dari komputer Anda

2. **Format File yang Didukung**
   - JPG/JPEG (.jpg, .jpeg)
   - PNG (.png)
   - WebP (.webp) - **Recommended** (ukuran kecil, kualitas tinggi)

3. **Spesifikasi Gambar**
   - Ukuran minimal: **1920×1080px** (untuk tampilan desktop optimal)
   - Ukuran file: **< 5MB** (batas upload maksimal)
   - Rekomendasi: **< 1MB** (untuk performa lebih cepat)

### **Step 3: Upload**
- Klik tombol "Upload Background"
- Tunggu hingga proses selesai
- Anda akan melihat pesan sukses/error

### **Step 4: Verifikasi Perubahan**
- Logout dari sistem
- Buka halaman login di `http://localhost/sipap/`
- Background baru akan langsung terlihat

## 📝 Rekomendasi Gambar

### **Karakteristik Gambar yang Baik**
✅ **Warna Cerah** - Agar teks tetap terbaca
✅ **Kontras Tinggi** - Teks login harus jelas terlihat
✅ **Resolusi Tinggi** - Minimal 1920×1080px
✅ **Ukuran File Kecil** - < 1MB untuk loading cepat
✅ **Profesional** - Sesuai dengan brand/identitas

### **Yang Harus Dihindari**
❌ **Warna Gelap Pekat** - Teks putih tidak terlihat
❌ **Terlalu Banyak Detail** - Mengganggu fokus
❌ **Resolusi Rendah** - Terlihat buram/pixelated
❌ **File Terlalu Besar** - Loading lambat
❌ **Background dengan watermark** - Mengurangi profesionalisme

## 💾 Format File Terbaik

| Format | Ukuran | Kualitas | Kecepatan | Transparansi | Rekomendasi |
|--------|--------|----------|-----------|--------------|------------|
| **JPG** | Medium | Baik | Cepat | ❌ | ✅ Good |
| **PNG** | Besar | Excellent | Normal | ✅ | ⚠️ Okeh |
| **WebP** | Kecil | Excellent | Sangat Cepat | ✅ | ⭐ Best |

### **Konversi ke WebP**
Untuk hasil optimal, konversi gambar ke format WebP:
- **Online Tool**: [CloudConvert.com](https://cloudconvert.com)
- **Command Line**: `ffmpeg -i input.jpg output.webp`

## 🔄 Proses Otomatis

### **Bagaimana Sistem Bekerja?**

1. **Upload File**
   - Admin upload gambar melalui halaman `/admin/background.php`
   - File disimpan di: `assets/images/background-login.[ext]`

2. **Deteksi Otomatis**
   - Halaman login (`index.php`) mencari file background
   - Jika ditemukan: gunakan background image
   - Jika tidak ditemukan: gunakan gradient default

3. **Fallback System**
   - Jika ada error/gambar tidak ditemukan, sistem tetap menampilkan gradient biru-ungu (default)
   - Tidak ada halaman yang rusak/blank

4. **Cache Management**
   - Browser akan cache gambar untuk loading lebih cepat
   - Jika update gambar tidak terlihat, clear cache browser (Ctrl+Shift+Del)

## 📂 Lokasi File

| File/Folder | Lokasi | Fungsi |
|-------------|--------|--------|
| **Background Upload** | `/admin/background.php` | Panel upload untuk admin |
| **Background Storage** | `/assets/images/background-login.*` | Tempat penyimpanan file |
| **Login Page** | `/index.php` | Halaman yang menggunakan background |

## ✨ Fitur-Fitur

✅ **Upload Gambar Baru**
- Admin dapat upload gambar baru kapan saja
- Upload otomatis mengganti background sebelumnya

✅ **Preview Background**
- Lihat preview halaman login sebelum upload
- Lihat ukuran file yang sedang digunakan

✅ **Validasi Ketat**
- Cek tipe file (hanya gambar)
- Cek ukuran file (max 5MB)
- Cek error upload

✅ **Fallback System**
- Jika tidak ada background, gunakan gradient default
- Sistem tidak akan pernah error

## 🐛 Troubleshooting

### **Gambar tidak muncul setelah upload?**
**Solusi:**
1. Refresh halaman login (F5)
2. Clear browser cache (Ctrl+Shift+Del)
3. Cek apakah file sudah ada di `assets/images/`

### **Upload error: "Tipe file tidak diizinkan"**
**Solusi:**
- Pastikan format: JPG, PNG, atau WebP
- Jangan upload file selain gambar

### **Upload error: "Ukuran file terlalu besar"**
**Solusi:**
- Kompres gambar terlebih dahulu
- Gunakan tools online: [TinyPNG.com](https://tinypng.com)
- Konversi ke WebP untuk ukuran lebih kecil

### **Background tidak sesuai dengan teks**
**Solusi:**
- Gunakan gambar dengan warna cerah
- Pastikan kontras cukup tinggi
- Teks login harus mudah dibaca

## 📊 Contoh Spesifikasi Gambar

### **Gambar JPG Optimal**
```
Nama: background-login.jpg
Ukuran: 1920x1080px
File Size: 200-400KB
Format: JPG/JPEG
```

### **Gambar WebP Optimal** (Recommended)
```
Nama: background-login.webp
Ukuran: 1920x1080px
File Size: 80-150KB
Format: WebP
```

## 🔐 Keamanan

- ✅ Hanya admin yang bisa upload background
- ✅ Validasi tipe file ketat (hanya gambar)
- ✅ Validasi ukuran file (max 5MB)
- ✅ File disimpan di folder yang aman
- ✅ Tidak ada script injection

## 💡 Tips Tambahan

1. **Update Rutin** - Ganti background sesekali untuk tampilan fresh
2. **Sesuaikan Brand** - Gunakan warna/desain yang match dengan identitas
3. **Mobile-Friendly** - Pastikan gambar tetap bagus di layar kecil
4. **Load Time** - Monitor kecepatan loading halaman login
5. **Backup** - Simpan versi original di komputer Anda

## 📞 Bantuan

Jika ada pertanyaan atau masalah:
1. Cek halaman `/admin/background.php` untuk info lebih lanjut
2. Lihat error message yang ditampilkan sistem
3. Hubungi admin sistem untuk bantuan teknis

---

**Happy Branding!** 🎨✨
