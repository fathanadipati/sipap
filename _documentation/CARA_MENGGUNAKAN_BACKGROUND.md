# 🎨 Panduan Menggunakan Background Image di SIPAP

## 📁 Struktur Folder

Semua file gambar harus disimpan di:
```
assets/
  └── images/
      ├── background-dashboard.png     (Contoh nama file)
      ├── logo.png
      └── (gambar lainnya)
```

## 📸 Langkah-Langkah Menambahkan Background Image

### **Step 1: Siapkan File Gambar**
1. Persiapkan gambar ilustrasi dengan format:
   - **Format**: PNG, JPG, atau WebP (WebP paling efisien)
   - **Ukuran**: Minimal 1920×1080px (untuk tampilan desktop)
   - **File Size**: < 500KB (untuk performa optimal)

2. Letakkan file gambar ke folder: `assets/images/`
   - Contoh: `assets/images/background-dashboard.png`

### **Step 2: Aktifkan Background Image di CSS**

Buka file: `assets/css/style.css`

Cari bagian `body` (baris 13-16):

```css
body {
    background-color: #f8f9fa;
    /* Uncomment below to use a background image instead of solid color */
    /* background-image: url('/assets/images/background-dashboard.png');
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat; */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
```

**Hapus tanda `/*` dan `*/`** untuk mengaktifkan:

```css
body {
    background-color: #f8f9fa;
    background-image: url('/assets/images/background-dashboard.png');
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
```

**GANTI** `/assets/images/background-dashboard.png` dengan **nama file Anda sendiri**.

### **Step 3: (Opsional) Tambahkan Overlay Transparan**

Jika gambar latar terlalu gelap/terang sehingga konten sulit dibaca, tambahkan overlay di atas `body`:

```css
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8); /* White overlay 80% transparent */
    pointer-events: none;
    z-index: -1;
}
```

Opsi overlay:
- `rgba(255, 255, 255, 0.8)` = White overlay (terang)
- `rgba(0, 0, 0, 0.3)` = Black overlay (gelap)
- `rgba(0, 0, 0, 0.5)` = Black overlay (lebih gelap)

## 🎯 Contoh Implementasi Lengkap

### **Tanpa Overlay:**
```css
body {
    background-color: #f8f9fa;
    background-image: url('/assets/images/background-dashboard.png');
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
```

### **Dengan Overlay White:**
```css
body {
    background-color: #f8f9fa;
    background-image: url('/assets/images/background-dashboard.png');
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.85);
    pointer-events: none;
    z-index: -1;
}
```

### **Dengan Overlay Dark:**
```css
body {
    background-color: #f8f9fa;
    background-image: url('/assets/images/background-dashboard.png');
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    pointer-events: none;
    z-index: -1;
}
```

## ⚙️ Opsi CSS Lainnya

### **background-attachment**
- `fixed` = Gambar tidak bergerak saat scroll (parallax effect)
- `scroll` = Gambar bergerak saat scroll (default)

### **background-size**
- `cover` = Gambar menutupi seluruh area (bisa ter-crop)
- `contain` = Gambar tampil utuh (bisa ada space kosong)
- `100% 100%` = Stretch gambar (bisa distorsi)

### **background-position**
- `center` = Gambar di tengah
- `top` = Gambar di atas
- `bottom` = Gambar di bawah
- `left` = Gambar di kiri
- `right` = Gambar di kanan

## 💾 Format Gambar yang Direkomendasikan

| Format | Kelebihan | Kekurangan | Ukuran |
|--------|-----------|-----------|--------|
| **PNG** | Kualitas tinggi, transparan | File besar | 200-500KB |
| **JPG** | File kecil, umum | Tidak support transparan | 50-150KB |
| **WebP** | Kualitas tinggi, file kecil | Support browser terbatas | 30-100KB |

## 🔍 Tips Pemilihan Gambar

1. **Pilih gambar yang netral** - Hindari gambar dengan warna terlalu cerah/gelap
2. **Gunakan opacity/overlay** - Agar teks tetap terbaca
3. **Optimasi ukuran** - Gunakan tools seperti:
   - TinyPNG.com (compress JPG/PNG)
   - CloudConvert.com (convert ke WebP)
4. **Test di berbagai device** - Desktop, tablet, mobile

## 🚀 Troubleshooting

### **Gambar tidak muncul?**
- Periksa path file benar: `/assets/images/nama-file.png`
- Pastikan file ada di folder yang benar
- Cek nama file case-sensitive (Linux)
- Refresh browser (Ctrl+Shift+Del)

### **Konten tidak terbaca?**
- Tambahkan overlay transparan (lihat di atas)
- Gunakan `background-size: contain` jika gambar kecil
- Ubah opacity overlay

### **Performa lambat?**
- Kompres gambar ke ukuran < 200KB
- Gunakan format WebP
- Ubah `background-attachment: scroll` untuk performa mobile lebih baik

## 📝 Contoh File yang Sudah Disiapkan

File CSS sudah di-update dengan template siap pakai di:
```
assets/css/style.css (baris 13-22)
```

Cukup uncomment dan ganti nama file sesuai kebutuhan Anda!

---

**Pertanyaan?** Hubungi admin sistem untuk bantuan lebih lanjut.
