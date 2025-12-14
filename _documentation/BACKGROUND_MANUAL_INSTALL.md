# 📸 Cara Manual: Tambahkan Background Tanpa Upload

Jika Anda ingin menambahkan background secara manual (tanpa melalui form upload), ikuti langkah ini.

## 📂 Persiapan File

### **1. Siapkan Gambar Anda**
- Lokasi: Gambar yang sudah Anda buat
- Format yang didukung: JPG, PNG, WebP
- Ukuran: minimal 1920×1080px
- Ukuran file: < 5MB (rekomendasi < 1MB)

### **2. Ubah Nama File**
Rename gambar Anda menjadi:
```
background-login.jpg
```
atau
```
background-login.png
```
atau
```
background-login.webp
```

**Penting**: Nama harus tepat `background-login` dengan extension yang sesuai.

## 📍 Lokasi Penyimpanan

### **Cara 1: Via FTP/File Manager**
1. Buka FTP client atau file manager web hosting
2. Navigasi ke folder: `sipap/assets/images/`
3. Upload file `background-login.jpg` (atau format lainnya)
4. Pastikan file berhasil ter-upload

### **Cara 2: Via File Explorer Lokal (XAMPP)**
1. Buka Windows File Explorer
2. Navigasi ke: `C:\xampp\htdocs\sipap\assets\images\`
3. Paste file `background-login.jpg` ke folder tersebut
4. Pastikan folder `images` sudah ada (jika belum, buat folder baru)

### **Cara 3: Via Command Line**
```powershell
# Navigate to images folder
cd C:\xampp\htdocs\sipap\assets\images

# Jika folder tidak ada, buat folder
mkdir images

# Copy file (ganti path dengan lokasi file Anda)
copy "C:\Users\YourName\Downloads\background-login.jpg" "C:\xampp\htdocs\sipap\assets\images\"
```

## ✅ Verifikasi Upload

1. **Check File Exists**
   ```
   C:\xampp\htdocs\sipap\assets\images\background-login.jpg
   ```
   Pastikan file ada di lokasi ini dengan nama yang tepat.

2. **Test di Browser**
   - Logout dari SIPAP
   - Buka `http://localhost/sipap/`
   - Lihat apakah background sudah berubah

3. **Check Browser Console** (jika tidak muncul)
   - Tekan F12 untuk buka Developer Tools
   - Cek tab "Console" untuk error messages
   - Cek tab "Network" untuk request ke gambar

## 🎨 Struktur Folder Yang Benar

```
sipap/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── images/
│   │   └── background-login.jpg  ← File di sini!
│   └── js/
│       └── script.js
├── admin/
├── modules/
├── includes/
├── config/
├── index.php
├── dashboard.php
└── ... file lainnya
```

## 🔄 Update/Ganti Background

Jika ingin mengganti background:
1. Hapus file `background-login.jpg` lama
2. Upload file `background-login.jpg` baru ke folder yang sama
3. Refresh browser (Ctrl+F5 untuk hard refresh)

**Nama file harus sama** - sistem akan otomatis mendeteksi file `background-login.*`

## 💾 Format File Alternatif

Anda bisa gunakan salah satu dari ini:
- `background-login.jpg`
- `background-login.jpeg`
- `background-login.png`
- `background-login.webp`

Sistem akan mendeteksi format apapun yang sesuai dengan pola `background-login.*`

## 🚀 Cara Kerja Sistem

Halaman login (`index.php`) bekerja sebagai berikut:

```php
// Cari file background
if (file exists: background-login.jpg/png/webp) {
    Gunakan background image tersebut
} else {
    Gunakan gradient biru-ungu default
}
```

Jadi tidak perlu edit code, cukup simpan file dengan nama yang tepat dan sistem akan otomatis mendeteksinya!

## ⚙️ Setting Permanen via CSS

Jika ingin menggunakan CSS custom, edit file:
```
assets/css/style.css
```

Lihat dokumentasi [CARA_MENGGUNAKAN_BACKGROUND.md](./CARA_MENGGUNAKAN_BACKGROUND.md) untuk info lengkap.

## 🔍 Debugging

### **Gambar tidak muncul?**
1. Check nama file: harus `background-login.jpg` (atau .png/.webp)
2. Check path: harus di `assets/images/` folder
3. Check permissions: file harus readable (644 atau 755)
4. Hard refresh: Ctrl+Shift+Delete untuk clear cache

### **Gambar blur/pixelated?**
- Resolusi gambar terlalu rendah
- Gunakan minimal 1920×1080px

### **Loading lambat?**
- File terlalu besar
- Kompres ke < 1MB
- Gunakan format WebP

## 📊 Contoh Hasil Akhir

Setelah file `background-login.jpg` di-upload ke folder yang tepat:

```
Sebelum: Halaman login menampilkan gradient biru-ungu
Sesudah: Halaman login menampilkan gambar background custom Anda
```

---

**Catatan**: Cara manual ini sama efektifnya dengan upload via form di `/admin/background.php`. Pilih cara yang paling mudah untuk Anda!
