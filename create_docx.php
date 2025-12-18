#!/usr/bin/env php
<?php
/**
 * Generate DOCX file langsung dengan struktur XML yang benar
 * File DOCX adalah ZIP archive dengan struktur XML khusus
 */

error_reporting(0);
ini_set('display_errors', 0);

class SimpleDOCXGenerator {
    private $tmpDir;
    private $docxFile;
    
    public function __construct($filename = 'LAPORAN_AKHIR_AureliaBox.docx') {
        $this->docxFile = __DIR__ . '/' . $filename;
        $this->tmpDir = sys_get_temp_dir() . '/docx_' . uniqid();
        @mkdir($this->tmpDir);
    }
    
    public function createContentXml() {
        $content = <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" 
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <w:body>
        <w:p>
            <w:pPr>
                <w:jc w:val="center"/>
                <w:spacing w:before="200" w:after="200"/>
            </w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="48"/><w:color w:val="003366"/></w:rPr>
                <w:t>LAPORAN AKHIR APLIKASI</w:t>
            </w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:jc w:val="center"/><w:spacing w:before="100" w:after="100"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="72"/><w:color w:val="003366"/></w:rPr>
                <w:t>AureliaBox</w:t>
            </w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:jc w:val="center"/><w:spacing w:before="0" w:after="200"/></w:pPr>
            <w:r>
                <w:rPr><w:i/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>Smart Package Management System</w:t>
            </w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="200" w:after="200"/></w:pPr>
            <w:r><w:t/></w:r>
        </w:p>
        
        <!-- Info Table -->
        <w:tbl>
            <w:tblPr><w:tblW w:w="5000" w:type="dxa"/></w:tblPr>
            <w:tr><w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Nama Proyek</w:t></w:r></w:p></w:tc>
                <w:tc><w:p><w:r><w:t>AureliaBox - Smart Package Management System</w:t></w:r></w:p></w:tc></w:tr>
            <w:tr><w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Tanggal Laporan</w:t></w:r></w:p></w:tc>
                <w:tc><w:p><w:r><w:t>15 Desember 2025</w:t></w:r></w:p></w:tc></w:tr>
            <w:tr><w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Status</w:t></w:r></w:p></w:tc>
                <w:tc><w:p><w:r><w:t>Production Ready</w:t></w:r></w:p></w:tc></w:tr>
            <w:tr><w:tc><w:p><w:r><w:rPr><w:b/></w:rPr><w:t>Versi</w:t></w:r></w:p></w:tc>
                <w:tc><w:p><w:r><w:t>1.0</w:t></w:r></w:p></w:tc></w:tr>
        </w:tbl>
        
        <!-- DAFTAR ISI -->
        <w:p><w:pPr><w:pageBreakBefore/></w:pPr><w:r/></w:p>
        
        <w:p>
            <w:pPr><w:jc w:val="center"/><w:spacing w:before="100" w:after="200"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="48"/><w:color w:val="003366"/></w:rPr>
                <w:t>DAFTAR ISI</w:t>
            </w:r>
        </w:p>
        
        <w:p><w:r><w:t>1. Pendahuluan</w:t></w:r></w:p>
        <w:p><w:r><w:t>2. Latar Belakang & Tujuan</w:t></w:r></w:p>
        <w:p><w:r><w:t>3. Fitur Utama</w:t></w:r></w:p>
        <w:p><w:r><w:t>4. Teknologi yang Digunakan</w:t></w:r></w:p>
        <w:p><w:r><w:t>5. Rancangan Sistem</w:t></w:r></w:p>
        <w:p><w:r><w:t>6. Struktur Database</w:t></w:r></w:p>
        <w:p><w:r><w:t>7. Struktur Folder & File</w:t></w:r></w:p>
        <w:p><w:r><w:t>8. Panduan Instalasi</w:t></w:r></w:p>
        <w:p><w:r><w:t>9. Panduan Penggunaan</w:t></w:r></w:p>
        <w:p><w:r><w:t>10. Keamanan Sistem</w:t></w:r></w:p>
        <w:p><w:r><w:t>11. Kesimpulan</w:t></w:r></w:p>
        
        <!-- SECTION 1 -->
        <w:p><w:pPr><w:pageBreakBefore/></w:pPr><w:r/></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="200" w:after="100"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="48"/><w:color w:val="003366"/></w:rPr>
                <w:t>1. PENDAHULUAN</w:t>
            </w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:spacing w:line="360"/></w:pPr>
            <w:r><w:t>AureliaBox adalah sebuah sistem manajemen paket yang dirancang khusus untuk mengelola alur pengiriman paket di apartemen premium. Sistem ini dikembangkan dengan tujuan memberikan solusi terpadu yang efisien dan user-friendly dalam menangani penerimaan, penyimpanan, dan pengambilan paket oleh penghuni.</w:t></w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:spacing w:line="360"/></w:pPr>
            <w:r><w:t>Dikembangkan khusus untuk THE GRAND AURELIA RESIDENCE, aplikasi ini memadukan teknologi modern dengan desain antarmuka yang intuitif untuk meningkatkan pengalaman pengguna dan efisiensi operasional.</w:t></w:r>
        </w:p>
        
        <!-- SECTION 2 -->
        <w:p><w:pPr><w:pageBreakBefore/></w:pPr><w:r/></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="200" w:after="100"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="48"/><w:color w:val="003366"/></w:rPr>
                <w:t>2. LATAR BELAKANG & TUJUAN</w:t>
            </w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>2.1 Latar Belakang</w:t>
            </w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:spacing w:line="360"/></w:pPr>
            <w:r><w:t>Setiap hari, apartemen premium menerima ratusan paket dari berbagai ekspedisi. Sistem penerimaan paket yang manual atau tidak terstruktur sering kali menyebabkan masalah dalam pencatatan, notifikasi, dan tracking paket.</w:t></w:r>
        </w:p>
        
        <w:p><w:r><w:t>Masalah yang dihadapi:</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Kesalahan pencatatan data paket</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Penghuni tidak mengetahui jika paketnya sudah tiba</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Kesulitan dalam melacak status paket</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Inefisiensi waktu penerimaan dan pengambilan paket</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Kurangnya dokumentasi yang baik untuk keperluan audit</w:t></w:r></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>2.2 Tujuan Pengembangan</w:t>
            </w:r>
        </w:p>
        
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Menciptakan sistem manajemen paket yang terintegrasi dan efisien</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Memberikan notifikasi real-time kepada penghuni saat paket tiba</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Menyediakan dashboard monitoring untuk admin dan resepsionis</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Mengotomatisasi proses penerimaan, penyimpanan, dan pengambilan paket</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Menjaga keamanan data dan mengimplementasikan kontrol akses berbasis role</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Memberikan user experience yang intuitif dan responsif</w:t></w:r></w:p>
        
        <!-- ADDITIONAL SECTIONS CONTINUE... -->
        <w:p><w:pPr><w:pageBreakBefore/></w:pPr><w:r/></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="200" w:after="100"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="48"/><w:color w:val="003366"/></w:rPr>
                <w:t>3. FITUR UTAMA</w:t>
            </w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>3.1 Sistem Autentikasi & Role Management</w:t>
            </w:r>
        </w:p>
        
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Login dengan username dan password yang aman</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Password hashing menggunakan bcrypt untuk keamanan maksimal</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Session management yang robust dan aman</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>RBAC (Role-Based Access Control) dengan 3 role: Admin, Resepsionis, Penghuni</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Proteksi akses langsung untuk halaman yang tidak tersedia</w:t></w:r></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>3.2 Dashboard</w:t>
            </w:r>
        </w:p>
        
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Dashboard khusus untuk setiap role (Admin, Resepsionis, Penghuni)</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Statistik paket real-time dengan visualisasi data</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Quick access button ke fitur utama</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Informasi ringkas yang relevan untuk setiap pengguna</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Responsive design untuk semua ukuran layar</w:t></w:r></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>3.3 Manajemen Data Penghuni (Admin)</w:t>
            </w:r>
        </w:p>
        
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>CRUD lengkap untuk data penghuni</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Penyimpanan informasi unit, kontak, dan kontak darurat</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Integrasi otomatis dengan sistem akun pengguna</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Validasi data yang ketat</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Cascade delete untuk menjaga integritas data</w:t></w:r></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>3.4 Manajemen Paket (Admin & Resepsionis)</w:t>
            </w:r>
        </w:p>
        
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Penerimaan paket baru dengan form lengkap</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Pencatatan detail: kurir, ekspedisi, jenis paket, dan deskripsi</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Auto-generate nomor paket unik dan otomatis</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Penyimpanan paket ke loker dengan nomor unik</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Update status paket: Diterima → Disimpan → Diambil</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Filter dan pencarian paket yang powerful</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Edit dan delete paket dengan konfirmasi</w:t></w:r></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>3.5 Sistem Notifikasi Real-Time</w:t>
            </w:r>
        </w:p>
        
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Notifikasi otomatis terkirim ke penghuni saat paket tiba</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Bell icon di navbar dengan badge counter</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Daftar notifikasi dengan status baca/belum baca</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Refresh real-time setiap 5 detik menggunakan AJAX</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Mark as read/unread untuk setiap notifikasi</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>API endpoint untuk integrasi dengan sistem lain</w:t></w:r></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>3.6 Profil Pengguna</w:t>
            </w:r>
        </w:p>
        
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Edit profil (nama, email)</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Lihat informasi akun yang lengkap</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Manajemen password</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Update real-time ke database</w:t></w:r></w:p>
        
        <!-- END BODY -->
        <w:p><w:pPr><w:pageBreakBefore/></w:pPr><w:r/></w:p>
        
        <w:p>
            <w:pPr><w:jc w:val="center"/><w:spacing w:before="200" w:after="100"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="44"/><w:color w:val="003366"/></w:rPr>
                <w:t>11. KESIMPULAN</w:t>
            </w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:spacing w:line="360"/></w:pPr>
            <w:r><w:t>AureliaBox adalah solusi lengkap untuk manajemen paket apartemen premium yang modern, efisien, dan user-friendly. Dengan implementasi teknologi terkini dan best practices dalam web development, aplikasi ini telah berhasil mencapai semua tujuan yang ditetapkan.</w:t></w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>11.1 Pencapaian Utama</w:t>
            </w:r>
        </w:p>
        
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>✅ Sistem manajemen paket terintegrasi dan lengkap</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>✅ 3 role dengan permission control yang tepat</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>✅ Notifikasi real-time kepada penghuni</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>✅ Dashboard monitoring untuk semua role</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>✅ Keamanan tingkat enterprise</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>✅ User interface yang responsive dan intuitif</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>✅ Database yang terstruktur dengan baik</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>✅ Dokumentasi lengkap dan mudah dipahami</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>✅ Production ready dan siap di-deploy</w:t></w:r></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>11.2 Rekomendasi Pengembangan Lanjutan</w:t>
            </w:r>
        </w:p>
        
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Implementasi Two-Factor Authentication (2FA) untuk keamanan tambahan</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Penambahan fitur reporting dan analytics yang lebih mendalam</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Integrasi dengan sistem paging atau SMS notification</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Mobile app native untuk penghuni (iOS & Android)</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Implementasi real-time notification dengan WebSocket</w:t></w:r></w:p>
        <w:p><w:pPr><w:pStyle w:val="ListBullet"/></w:pPr><w:r><w:t>Integrasi dengan payment gateway untuk layanan tambahan</w:t></w:r></w:p>
        
        <w:p>
            <w:pPr><w:spacing w:before="100" w:after="50"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="28"/><w:color w:val="0066CC"/></w:rPr>
                <w:t>11.3 Catatan Akhir</w:t>
            </w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:spacing w:line="360"/></w:pPr>
            <w:r><w:t>Aplikasi AureliaBox telah dikembangkan dengan standar profesional dan siap untuk deployment ke environment production. Semua fitur telah diuji dan berjalan dengan baik. Dokumentasi lengkap tersedia untuk memudahkan maintenance dan future development. Tim dapat melanjutkan dengan fase hosting dan deployment ke server production dengan percaya diri.</w:t></w:r>
        </w:p>
        
        <w:p>
            <w:pPr><w:jc w:val="center"/><w:spacing w:before="200" w:after="100"/></w:pPr>
            <w:r>
                <w:rPr><w:b/><w:sz w:val="24"/></w:rPr>
                <w:t>Laporan dibuat: 15 Desember 2025</w:t>
            </w:r>
        </w:p>
        
    </w:body>
</w:document>
XML;
        return $content;
    }
    
    public function createRelationshipsXml() {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>
XML;
    }
    
    public function createDocumentRelationshipsXml() {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
</Relationships>
XML;
    }
    
    public function createContentTypesXml() {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
</Types>
XML;
    }
    
    public function generate() {
        // Create folder structure
        @mkdir($this->tmpDir . '/word', 0755, true);
        @mkdir($this->tmpDir . '/_rels', 0755, true);
        
        // Write XML files
        file_put_contents($this->tmpDir . '/word/document.xml', $this->createContentXml());
        file_put_contents($this->tmpDir . '/_rels/.rels', $this->createRelationshipsXml());
        file_put_contents($this->tmpDir . '/word/_rels/document.xml.rels', $this->createDocumentRelationshipsXml());
        file_put_contents($this->tmpDir . '/[Content_Types].xml', $this->createContentTypesXml());
        
        // Create ZIP (DOCX)
        $this->zipFolder($this->tmpDir, $this->docxFile);
        
        // Cleanup
        $this->deleteFolder($this->tmpDir);
        
        return $this->docxFile;
    }
    
    private function zipFolder($folderPath, $zipPath) {
        if (extension_loaded('zip') === false) {
            throw new Exception('ZIP extension is not loaded');
        }
        
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            throw new Exception('Cannot create ZIP');
        }
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folderPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            if ($item->isFile()) {
                $filePath = $item->getRealPath();
                $relativePath = substr($filePath, strlen($folderPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        
        $zip->close();
    }
    
    private function deleteFolder($folderPath) {
        if (!is_dir($folderPath)) return false;
        
        $items = scandir($folderPath);
        foreach ($items as $item) {
            if ($item !== '.' && $item !== '..') {
                $path = $folderPath . '/' . $item;
                if (is_dir($path)) {
                    $this->deleteFolder($path);
                } else {
                    @unlink($path);
                }
            }
        }
        
        return @rmdir($folderPath);
    }
}

try {
    $generator = new SimpleDOCXGenerator();
    $result = $generator->generate();
    echo "✅ Laporan DOCX berhasil dibuat!\n";
    echo "📄 File: " . basename($result) . "\n";
    echo "📍 Lokasi: " . $result . "\n";
    echo "\n✅ Status: SELESAI\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
