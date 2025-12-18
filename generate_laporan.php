<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Generate Laporan Akhir - AureliaBox</title>
</head>
<body>
    <h1>Generate Laporan Akhir</h1>
    <p>Laporan sedang dibuat...</p>
    
<?php
// Include library untuk membuat Word document
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Pt;
use PhpOffice\PhpWord\Shared\RGBColor;

// Create new Word document
$phpWord = new PhpWord();

// Set document properties
$phpWord->getProperties()->setCreator('AureliaBox Admin');
$phpWord->getProperties()->setTitle('Laporan Akhir AureliaBox');
$phpWord->getProperties()->setSubject('Smart Package Management System');

// Define styles
$phpWord->addParagraphStyle('Heading1', array(
    'name' => 'Calibri',
    'size' => 24,
    'bold' => true,
    'color' => '003366'
));

$phpWord->addParagraphStyle('Heading2', array(
    'name' => 'Calibri',
    'size' => 14,
    'bold' => true,
    'color' => '0066CC'
));

// Add cover page
$section = $phpWord->addSection();

// Title
$title = $section->addParagraph('LAPORAN AKHIR APLIKASI', 'Heading1');
$title->setAlignment('center');

$section->addParagraph();

// App name
$appName = $section->addParagraph('AureliaBox');
$appNameRun = $appName->createRun();
$appNameRun->setFontSize(36);
$appNameRun->setBold(true);
$appNameRun->setColor('003366');
$appName->setAlignment('center');

// Subtitle
$subtitle = $section->addParagraph('Smart Package Management System');
$subtitleRun = $subtitle->createRun();
$subtitleRun->setFontSize(14);
$subtitleRun->setItalic(true);
$subtitle->setAlignment('center');

$section->addParagraph();
$section->addParagraph();

// Info table
$infoTable = $section->addTable();
$infoTable->setWidth(5000);

$infoData = array(
    array('Nama Proyek', 'AureliaBox - Smart Package Management System'),
    array('Tanggal Laporan', date('d B Y', strtotime('today'))),
    array('Status', 'Production Ready'),
    array('Versi', '1.0')
);

foreach ($infoData as $data) {
    $row = $infoTable->addRow();
    $row->addCell(1500)->addParagraph($data[0])->createRun()->setBold(true);
    $row->addCell(3500)->addParagraph($data[1]);
}

// Page break
$section->addPageBreak();

// ===== DAFTAR ISI =====
$section->addParagraph('DAFTAR ISI', 'Heading1')->setAlignment('center');
$section->addParagraph();

$tocItems = array(
    '1. Pendahuluan',
    '2. Latar Belakang & Tujuan',
    '3. Fitur Utama',
    '4. Teknologi yang Digunakan',
    '5. Rancangan Sistem',
    '6. Struktur Database',
    '7. Struktur Folder & File',
    '8. Panduan Instalasi',
    '9. Panduan Penggunaan',
    '10. Keamanan Sistem',
    '11. Kesimpulan'
);

foreach ($tocItems as $item) {
    $section->addParagraph($item);
}

$section->addPageBreak();

// ===== 1. PENDAHULUAN =====
$section->addParagraph('1. PENDAHULUAN', 'Heading2');
$section->addParagraph(
    'AureliaBox adalah sebuah sistem manajemen paket yang dirancang khusus untuk mengelola '
    . 'alur pengiriman paket di apartemen premium. Sistem ini dikembangkan dengan tujuan memberikan '
    . 'solusi terpadu yang efisien dan user-friendly dalam menangani penerimaan, penyimpanan, dan '
    . 'pengambilan paket oleh penghuni.'
);

$section->addParagraph(
    'Dikembangkan khusus untuk THE GRAND AURELIA RESIDENCE, aplikasi ini memadukan teknologi '
    . 'modern dengan desain antarmuka yang intuitif untuk meningkatkan pengalaman pengguna dan '
    . 'efisiensi operasional.'
);

$section->addPageBreak();

// ===== 2. LATAR BELAKANG & TUJUAN =====
$section->addParagraph('2. LATAR BELAKANG & TUJUAN', 'Heading2');

$section->addParagraph('2.1 Latar Belakang', 'Heading2');
$section->addParagraph(
    'Setiap hari, apartemen premium menerima ratusan paket dari berbagai ekspedisi. '
    . 'Sistem penerimaan paket yang manual atau tidak terstruktur sering kali menyebabkan:'
);

$problems = array(
    'Kesalahan pencatatan data paket',
    'Penghuni tidak mengetahui jika paketnya sudah tiba',
    'Kesulitan dalam melacak status paket',
    'Inefisiensi waktu penerimaan dan pengambilan paket',
    'Kurangnya dokumentasi yang baik untuk keperluan audit'
);

foreach ($problems as $problem) {
    $section->addParagraph($problem, 'List Bullet');
}

$section->addParagraph('2.2 Tujuan Pengembangan', 'Heading2');

$goals = array(
    'Menciptakan sistem manajemen paket yang terintegrasi dan efisien',
    'Memberikan notifikasi real-time kepada penghuni saat paket tiba',
    'Menyediakan dashboard monitoring untuk admin dan resepsionis',
    'Mengotomatisasi proses penerimaan, penyimpanan, dan pengambilan paket',
    'Menjaga keamanan data dan mengimplementasikan kontrol akses berbasis role',
    'Memberikan user experience yang intuitif dan responsif'
);

foreach ($goals as $goal) {
    $section->addParagraph($goal, 'List Bullet');
}

$section->addPageBreak();

// ===== 3. FITUR UTAMA =====
$section->addParagraph('3. FITUR UTAMA', 'Heading2');

$features = array(
    '3.1 Sistem Autentikasi & Role Management' => array(
        'Login dengan username dan password yang aman',
        'Password hashing menggunakan bcrypt untuk keamanan maksimal',
        'Session management yang robust dan aman',
        'RBAC (Role-Based Access Control) dengan 3 role: Admin, Resepsionis, Penghuni',
        'Proteksi akses langsung untuk halaman yang tidak tersedia'
    ),
    '3.2 Dashboard' => array(
        'Dashboard khusus untuk setiap role (Admin, Resepsionis, Penghuni)',
        'Statistik paket real-time dengan visualisasi data',
        'Quick access button ke fitur utama',
        'Informasi ringkas yang relevan untuk setiap pengguna',
        'Responsive design untuk semua ukuran layar'
    ),
    '3.3 Manajemen Data Penghuni (Admin)' => array(
        'CRUD lengkap untuk data penghuni',
        'Penyimpanan informasi unit, kontak, dan kontak darurat',
        'Integrasi otomatis dengan sistem akun pengguna',
        'Validasi data yang ketat',
        'Cascade delete untuk menjaga integritas data'
    ),
    '3.4 Manajemen Paket (Admin & Resepsionis)' => array(
        'Penerimaan paket baru dengan form lengkap',
        'Pencatatan detail: kurir, ekspedisi, jenis paket, dan deskripsi',
        'Auto-generate nomor paket unik dan otomatis',
        'Penyimpanan paket ke loker dengan nomor unik',
        'Update status paket: Diterima → Disimpan → Diambil',
        'Filter dan pencarian paket yang powerful',
        'Edit dan delete paket dengan konfirmasi'
    ),
    '3.5 Sistem Notifikasi Real-Time' => array(
        'Notifikasi otomatis terkirim ke penghuni saat paket tiba',
        'Bell icon di navbar dengan badge counter',
        'Daftar notifikasi dengan status baca/belum baca',
        'Refresh real-time setiap 5 detik menggunakan AJAX',
        'Mark as read/unread untuk setiap notifikasi',
        'API endpoint untuk integrasi dengan sistem lain'
    ),
    '3.6 Profil Pengguna' => array(
        'Edit profil (nama, email)',
        'Lihat informasi akun yang lengkap',
        'Manajemen password',
        'Update real-time ke database'
    )
);

foreach ($features as $section_title => $feature_list) {
    $section->addParagraph($section_title, 'Heading2');
    foreach ($feature_list as $feature) {
        $section->addParagraph($feature, 'List Bullet');
    }
}

$section->addPageBreak();

// ===== 4. TEKNOLOGI YANG DIGUNAKAN =====
$section->addParagraph('4. TEKNOLOGI YANG DIGUNAKAN', 'Heading2');

$techTable = $section->addTable();
$techTable->setWidth(5000);
$techTable->addRow()->addCell(1500)->addParagraph('Komponen')->createRun()->setBold(true);
$techTable->getLastRow()->addCell(3500)->addParagraph('Teknologi')->createRun()->setBold(true);

$tech_data = array(
    array('Backend', 'PHP 7.4+ (Native - Tanpa Framework)'),
    array('Database', 'MySQL 5.7+'),
    array('Frontend', 'HTML5, CSS3, Vanilla JavaScript'),
    array('CSS Framework', 'Bootstrap 5'),
    array('Icon', 'Bootstrap Icons'),
    array('Server', 'Apache (XAMPP)'),
    array('AJAX', 'Vanilla JavaScript + XMLHttpRequest'),
    array('Password Hashing', 'bcrypt'),
    array('Version Control', 'Git')
);

foreach ($tech_data as $data) {
    $row = $techTable->addRow();
    $row->addCell(1500)->addParagraph($data[0]);
    $row->addCell(3500)->addParagraph($data[1]);
}

$section->addParagraph('4.1 Alasan Pemilihan Teknologi', 'Heading2');

$reasons = array(
    'PHP Native' => 'Lightweight, mudah di-deploy, tidak perlu dependency kompleks, cocok untuk XAMPP',
    'MySQL' => 'Reliable, widely used, support untuk relational database dengan foreign key',
    'Bootstrap 5' => 'Modern UI components, responsive grid system, extensive documentation',
    'Vanilla JavaScript' => 'No dependencies, fast performance, native browser support untuk AJAX',
    'Apache' => 'Standard web server, integrated dengan XAMPP, easy configuration'
);

foreach ($reasons as $tech => $reason) {
    $p = $section->addParagraph();
    $run = $p->createRun();
    $run->setText($tech . ': ');
    $run->setBold(true);
    $p->createRun()->setText($reason);
}

$section->addPageBreak();

// ===== 5. RANCANGAN SISTEM =====
$section->addParagraph('5. RANCANGAN SISTEM', 'Heading2');

$section->addParagraph('5.1 Arsitektur Aplikasi', 'Heading2');
$section->addParagraph(
    'AureliaBox menggunakan arsitektur 3-Tier (Presentation-Business Logic-Data) yang '
    . 'klasik dan proven:'
);

$section->addParagraph('Presentation Layer (Frontend)', 'List Bullet');
$section->addParagraph('HTML, CSS, JavaScript untuk user interface', 'List Bullet 2');

$section->addParagraph('Business Logic Layer (Backend)', 'List Bullet');
$section->addParagraph('PHP files yang menghandle business logic', 'List Bullet 2');

$section->addParagraph('Data Access Layer (Database)', 'List Bullet');
$section->addParagraph('MySQL untuk persistent data storage', 'List Bullet 2');

$section->addParagraph('5.2 Workflow Paket Masuk', 'Heading2');
$section->addParagraph('Berikut adalah workflow lengkap saat paket masuk ke sistem:');

$workflow_steps = array(
    'Kurir datang dengan paket',
    'Resepsionis klik menu \'Terima Paket Baru\'',
    'Resepsionis mengisi form: nama pengirim, ekspedisi, jenis paket, deskripsi',
    'Resepsionis memilih unit/penghuni penerima paket',
    'Resepsionis memasukkan nomor loker tempat paket disimpan',
    'Resepsionis klik tombol \'Simpan\'',
    'Sistem auto-generate nomor paket unik',
    'Database update dengan status \'Disimpan\'',
    'Sistem otomatis mengirim notifikasi ke penghuni',
    'Penghuni menerima notifikasi real-time di dashboard',
    'Penghuni dapat melihat detail paket dan lokernya'
);

foreach ($workflow_steps as $step) {
    $section->addParagraph($step, 'List Number');
}

$section->addParagraph('5.3 User Roles & Permissions', 'Heading2');

$rolesTable = $section->addTable();
$rolesTable->setWidth(5000);
$rolesTable->addRow()->addCell(1500)->addParagraph('Role')->createRun()->setBold(true);
$rolesTable->getLastRow()->addCell(3500)->addParagraph('Permissions')->createRun()->setBold(true);

$roles_data = array(
    array('Admin', 'Manage users, manage residents, manage packages, view all data, access admin panel'),
    array('Resepsionis', 'Receive packages, manage packages (edit/delete), view resident data, view notifications'),
    array('Penghuni', 'View own packages, view notifications, edit profile, view own data')
);

foreach ($roles_data as $data) {
    $row = $rolesTable->addRow();
    $row->addCell(1500)->addParagraph($data[0]);
    $row->addCell(3500)->addParagraph($data[1]);
}

$section->addPageBreak();

// ===== 6. STRUKTUR DATABASE =====
$section->addParagraph('6. STRUKTUR DATABASE', 'Heading2');

$section->addParagraph('6.1 Entity Relationship Diagram (Konsep)', 'Heading2');
$section->addParagraph(
    'Database terdiri dari 4 tabel utama yang saling berelasi untuk menyimpan data pengguna, '
    . 'penghuni, paket, dan notifikasi:'
);

$section->addParagraph('6.2 Tabel: users', 'Heading2');

$usersTable = $section->addTable();
$usersTable->setWidth(5000);
$usersTable->addRow()->addCell(1200)->addParagraph('Field')->createRun()->setBold(true);
$usersTable->getLastRow()->addCell(1000)->addParagraph('Tipe')->createRun()->setBold(true);
$usersTable->getLastRow()->addCell(2800)->addParagraph('Keterangan')->createRun()->setBold(true);

$users_data = array(
    array('id', 'INT', 'Primary Key, Auto Increment'),
    array('username', 'VARCHAR(50)', 'UNIQUE, untuk login'),
    array('email', 'VARCHAR(100)', 'UNIQUE, email pengguna'),
    array('password', 'VARCHAR(255)', 'Hashed dengan bcrypt'),
    array('role', 'ENUM', 'admin, resepsionis, atau penghuni'),
    array('nama_lengkap', 'VARCHAR(100)', 'Nama lengkap pengguna'),
    array('is_active', 'BOOLEAN', 'Status aktif/nonaktif'),
    array('created_at', 'TIMESTAMP', 'Waktu pembuatan record'),
    array('updated_at', 'TIMESTAMP', 'Waktu update terakhir')
);

foreach ($users_data as $data) {
    $row = $usersTable->addRow();
    $row->addCell(1200)->addParagraph($data[0]);
    $row->addCell(1000)->addParagraph($data[1]);
    $row->addCell(2800)->addParagraph($data[2]);
}

$section->addParagraph('6.3 Tabel: penghuni', 'Heading2');

$penghuniTable = $section->addTable();
$penghuniTable->setWidth(5000);
$penghuniTable->addRow()->addCell(1200)->addParagraph('Field')->createRun()->setBold(true);
$penghuniTable->getLastRow()->addCell(1000)->addParagraph('Tipe')->createRun()->setBold(true);
$penghuniTable->getLastRow()->addCell(2800)->addParagraph('Keterangan')->createRun()->setBold(true);

$penghuni_data = array(
    array('id', 'INT', 'Primary Key, Auto Increment'),
    array('user_id', 'INT', 'Foreign Key ke tabel users'),
    array('nomor_unit', 'VARCHAR(20)', 'UNIQUE, nomor unit apartemen'),
    array('blok', 'VARCHAR(10)', 'Blok unit'),
    array('lantai', 'INT', 'Lantai unit'),
    array('nomor_hp', 'VARCHAR(15)', 'Nomor telepon penghuni'),
    array('nama_kontak_darurat', 'VARCHAR(100)', 'Nama kontak darurat'),
    array('nomor_kontak_darurat', 'VARCHAR(15)', 'Nomor kontak darurat'),
    array('created_at', 'TIMESTAMP', 'Waktu pembuatan record'),
    array('updated_at', 'TIMESTAMP', 'Waktu update terakhir')
);

foreach ($penghuni_data as $data) {
    $row = $penghuniTable->addRow();
    $row->addCell(1200)->addParagraph($data[0]);
    $row->addCell(1000)->addParagraph($data[1]);
    $row->addCell(2800)->addParagraph($data[2]);
}

$section->addParagraph('6.4 Tabel: paket', 'Heading2');

$paketTable = $section->addTable();
$paketTable->setWidth(5000);
$paketTable->addRow()->addCell(1200)->addParagraph('Field')->createRun()->setBold(true);
$paketTable->getLastRow()->addCell(1000)->addParagraph('Tipe')->createRun()->setBold(true);
$paketTable->getLastRow()->addCell(2800)->addParagraph('Keterangan')->createRun()->setBold(true);

$paket_data = array(
    array('id', 'INT', 'Primary Key, Auto Increment'),
    array('nomor_paket', 'VARCHAR(50)', 'UNIQUE, auto-generated identifier'),
    array('penghuni_id', 'INT', 'Foreign Key ke tabel penghuni'),
    array('nama_pengirim', 'VARCHAR(100)', 'Nama pengirim paket'),
    array('nama_kurir', 'VARCHAR(100)', 'Nama kurir yang mengantarkan'),
    array('nama_ekspedisi', 'VARCHAR(100)', 'Nama perusahaan ekspedisi'),
    array('jenis_paket', 'VARCHAR(50)', 'Kategori paket'),
    array('deskripsi', 'TEXT', 'Deskripsi isi paket'),
    array('nomor_loker', 'VARCHAR(20)', 'Nomor loker penyimpanan'),
    array('status', 'ENUM', 'diterima, disimpan, atau diambil'),
    array('resepsionis_id', 'INT', 'Foreign Key ke user (resepsionis)'),
    array('tanggal_terima', 'DATETIME', 'Waktu paket diterima'),
    array('tanggal_diambil', 'DATETIME', 'Waktu paket diambil (nullable)'),
    array('catatan', 'TEXT', 'Catatan tambahan'),
    array('created_at', 'TIMESTAMP', 'Waktu pembuatan record'),
    array('updated_at', 'TIMESTAMP', 'Waktu update terakhir')
);

foreach ($paket_data as $data) {
    $row = $paketTable->addRow();
    $row->addCell(1200)->addParagraph($data[0]);
    $row->addCell(1000)->addParagraph($data[1]);
    $row->addCell(2800)->addParagraph($data[2]);
}

$section->addParagraph('6.5 Tabel: notifikasi', 'Heading2');

$notifTable = $section->addTable();
$notifTable->setWidth(5000);
$notifTable->addRow()->addCell(1200)->addParagraph('Field')->createRun()->setBold(true);
$notifTable->getLastRow()->addCell(1000)->addParagraph('Tipe')->createRun()->setBold(true);
$notifTable->getLastRow()->addCell(2800)->addParagraph('Keterangan')->createRun()->setBold(true);

$notif_data = array(
    array('id', 'INT', 'Primary Key, Auto Increment'),
    array('penghuni_id', 'INT', 'Foreign Key ke tabel penghuni'),
    array('paket_id', 'INT', 'Foreign Key ke tabel paket'),
    array('pesan', 'TEXT', 'Isi pesan notifikasi'),
    array('is_read', 'BOOLEAN', 'Status baca/belum baca'),
    array('created_at', 'TIMESTAMP', 'Waktu notifikasi dibuat')
);

foreach ($notif_data as $data) {
    $row = $notifTable->addRow();
    $row->addCell(1200)->addParagraph($data[0]);
    $row->addCell(1000)->addParagraph($data[1]);
    $row->addCell(2800)->addParagraph($data[2]);
}

$section->addPageBreak();

// ===== 7. STRUKTUR FOLDER & FILE =====
$section->addParagraph('7. STRUKTUR FOLDER & FILE', 'Heading2');

$section->addParagraph(
    'Aplikasi terorganisir dalam struktur folder yang jelas dan mudah dipahami untuk '
    . 'memudahkan maintenance dan development:'
);

$section->addParagraph('7.1 Root Level Files', 'Heading2');

$root_files = array(
    'index.php - Halaman utama/landing page',
    'login.php - Halaman login pengguna',
    'logout.php - Script proses logout',
    'dashboard.php - Dashboard utama setelah login',
    'profile.php - Halaman profil pengguna',
    'forbidden.php - Halaman error 403 (akses terlarang)',
    'database.sql - Database schema dan data awal'
);

foreach ($root_files as $f) {
    $section->addParagraph($f, 'List Bullet');
}

$section->addParagraph('7.2 Folder Utama', 'Heading2');

$folders = array(
    'config/' => array(
        'database.php - Konfigurasi koneksi MySQL',
        'session.php - Session management & autentikasi',
        'pagination.php - Konfigurasi pagination'
    ),
    'includes/' => array(
        'header.php - Header template',
        'navbar.php - Navigation bar',
        'footer.php - Footer template'
    ),
    'modules/' => array(
        'penghuni/ - Modul manajemen data penghuni',
        'paket/ - Modul manajemen paket',
        'notifikasi/ - Modul sistem notifikasi'
    ),
    'admin/' => array(
        'index.php - Admin panel utama',
        'users.php - Daftar pengguna',
        'users_add.php - Tambah pengguna',
        'users_edit.php - Edit pengguna',
        'users_delete.php - Hapus pengguna'
    ),
    'assets/' => array(
        'css/style.css - Stylesheet custom',
        'js/script.js - JavaScript custom',
        'images/ - Folder untuk gambar'
    ),
    'api/' => array(
        'get-occupied-lokers.php - API endpoint untuk loker terisi'
    )
);

foreach ($folders as $folder => $items) {
    $section->addParagraph('📁 ' . $folder, 'List Bullet');
    foreach ($items as $item) {
        $section->addParagraph($item, 'List Bullet 2');
    }
}

$section->addParagraph('7.3 Total File Organization', 'Heading2');

$orgTable = $section->addTable();
$orgTable->setWidth(5000);
$orgTable->addRow()->addCell(2500)->addParagraph('Tipe')->createRun()->setBold(true);
$orgTable->getLastRow()->addCell(2500)->addParagraph('Jumlah')->createRun()->setBold(true);

$org_data = array(
    array('PHP Files', '25+'),
    array('Config Files', '3'),
    array('Template Files', '3'),
    array('CSS Files', '1'),
    array('JavaScript Files', '1'),
    array('SQL Files', '1'),
    array('Documentation Files', '10+'),
    array('Total Files', '50+')
);

foreach ($org_data as $data) {
    $row = $orgTable->addRow();
    $row->addCell(2500)->addParagraph($data[0]);
    $row->addCell(2500)->addParagraph($data[1]);
}

$section->addPageBreak();

// ===== 8. PANDUAN INSTALASI =====
$section->addParagraph('8. PANDUAN INSTALASI', 'Heading2');

$section->addParagraph('8.1 Prasyarat (Requirements)', 'Heading2');

$requirements = array(
    'XAMPP (Apache + MySQL + PHP)',
    'PHP 7.4 atau lebih tinggi',
    'MySQL 5.7 atau lebih tinggi',
    'Text Editor atau IDE (VS Code, PhpStorm, dll)',
    'Browser modern (Chrome, Firefox, Edge, Safari)'
);

foreach ($requirements as $req) {
    $section->addParagraph($req, 'List Bullet');
}

$section->addParagraph('8.2 Langkah Instalasi', 'Heading2');

$steps = array(
    array('Ekstrak file', 'Ekstrak folder \'sipap\' ke dalam folder \'C:\\xampp\\htdocs\\\''),
    array('Start XAMPP', 'Buka XAMPP Control Panel dan start Apache dan MySQL'),
    array('Import Database', 'Buka phpMyAdmin (http://localhost/phpmyadmin), buat database baru \'sipap_db\', lalu import file \'database.sql\''),
    array('Akses Aplikasi', 'Buka browser dan ketik: http://localhost/sipap'),
    array('Login', 'Gunakan kredensial default untuk login sebagai admin')
);

$stepNum = 1;
foreach ($steps as $step) {
    $p = $section->addParagraph();
    $run = $p->createRun();
    $run->setText($stepNum . '. ' . $step[0] . ': ');
    $run->setBold(true);
    $p->createRun()->setText($step[1]);
    $stepNum++;
}

$section->addParagraph('8.3 Kredensial Default', 'Heading2');

$credTable = $section->addTable();
$credTable->setWidth(5000);
$credTable->addRow()->addCell(1500)->addParagraph('Role')->createRun()->setBold(true);
$credTable->getLastRow()->addCell(1500)->addParagraph('Username')->createRun()->setBold(true);
$credTable->getLastRow()->addCell(2000)->addParagraph('Password')->createRun()->setBold(true);

$cred_data = array(
    array('Admin', 'admin', 'admin123'),
    array('Resepsionis', 'resepsionis', 'resepsionis123'),
    array('Penghuni', 'penghuni1', 'penghuni123')
);

foreach ($cred_data as $data) {
    $row = $credTable->addRow();
    $row->addCell(1500)->addParagraph($data[0]);
    $row->addCell(1500)->addParagraph($data[1]);
    $row->addCell(2000)->addParagraph($data[2]);
}

$section->addParagraph('⚠️ Penting: Ganti semua password default setelah instalasi berhasil!');

$section->addPageBreak();

// ===== 9. PANDUAN PENGGUNAAN =====
$section->addParagraph('9. PANDUAN PENGGUNAAN', 'Heading2');

$section->addParagraph('9.1 Untuk Admin', 'Heading2');

$admin_guide = array(
    'Login ke sistem menggunakan akun admin',
    'Akses Admin Panel dari menu dropdown profil',
    'Kelola pengguna: tambah, edit, hapus pengguna',
    'Kelola data penghuni: tambah, edit, hapus penghuni',
    'Monitor semua paket yang masuk dan keluar',
    'Lihat statistik dan ringkasan di dashboard'
);

foreach ($admin_guide as $guide) {
    $section->addParagraph($guide, 'List Bullet');
}

$section->addParagraph('9.2 Untuk Resepsionis', 'Heading2');

$resep_guide = array(
    'Login ke sistem menggunakan akun resepsionis',
    'Klik \'Terima Paket Baru\' saat kurir datang',
    'Isi form dengan detail paket (pengirim, ekspedisi, deskripsi)',
    'Pilih unit/penghuni penerima dari dropdown',
    'Masukkan nomor loker tempat menyimpan paket',
    'Klik \'Simpan\' untuk mencatat paket',
    'Sistem otomatis mengirim notifikasi ke penghuni',
    'Monitor paket yang sedang disimpan di loker',
    'Update status ketika penghuni mengambil paket'
);

foreach ($resep_guide as $guide) {
    $section->addParagraph($guide, 'List Bullet');
}

$section->addParagraph('9.3 Untuk Penghuni', 'Heading2');

$penghuni_guide = array(
    'Login ke sistem menggunakan akun penghuni',
    'Lihat notifikasi bell di navbar untuk paket baru',
    'Klik notifikasi untuk melihat detail paket dan nomor loker',
    'Ambil paket dari loker sesuai nomor yang diberikan',
    'Edit profil jika ingin update data pribadi',
    'Lihat riwayat paket yang sudah diambil'
);

foreach ($penghuni_guide as $guide) {
    $section->addParagraph($guide, 'List Bullet');
}

$section->addPageBreak();

// ===== 10. KEAMANAN SISTEM =====
$section->addParagraph('10. KEAMANAN SISTEM', 'Heading2');

$section->addParagraph('10.1 Implementasi Keamanan', 'Heading2');

$security = array(
    'Password Hashing' => array(
        'Menggunakan bcrypt untuk hashing password',
        'Password tidak pernah disimpan dalam plain text',
        'Salt otomatis pada setiap hash untuk security tambahan'
    ),
    'SQL Injection Prevention' => array(
        'Menggunakan prepared statements dengan parameterized queries',
        'Input validation di semua form',
        'Sanitasi data sebelum dimasukkan ke database'
    ),
    'XSS (Cross-Site Scripting) Prevention' => array(
        'Output encoding untuk semua user input',
        'Htmlspecialchars() untuk mencegah script injection',
        'Content Security Policy ready'
    ),
    'Session Management' => array(
        'Session timeout configuration',
        'Unique session ID untuk setiap user',
        'Session data disimpan di server, bukan di client'
    ),
    'Access Control' => array(
        'RBAC (Role-Based Access Control)',
        'Direct access protection untuk halaman admin',
        'Permission check di setiap halaman yang restricted',
        '403 Forbidden page untuk akses terlarang'
    ),
    'Data Validation' => array(
        'Server-side validation di semua form',
        'Input length checking',
        'Data type validation',
        'Required field validation'
    )
);

foreach ($security as $sec_feature => $details) {
    $p = $section->addParagraph('• ' . $sec_feature);
    $p->getStyle()->setName('Heading2');
    foreach ($details as $detail) {
        $section->addParagraph($detail, 'List Bullet');
    }
}

$section->addParagraph('10.2 Best Practices yang Diikuti', 'Heading2');

$practices = array(
    'Never trust user input - selalu validasi dan sanitasi',
    'Principle of Least Privilege - users hanya dapat akses yang perlu',
    'Error handling yang tidak mengungkap detail sistem',
    'Logging untuk audit trail',
    'Regular backup database',
    'HTTPS ready (saat hosting, gunakan SSL certificate)'
);

foreach ($practices as $practice) {
    $section->addParagraph($practice, 'List Bullet');
}

$section->addPageBreak();

// ===== 11. KESIMPULAN =====
$section->addParagraph('11. KESIMPULAN', 'Heading2');

$section->addParagraph(
    'AureliaBox adalah solusi lengkap untuk manajemen paket apartemen premium yang modern, '
    . 'efisien, dan user-friendly. Dengan implementasi teknologi terkini dan best practices '
    . 'dalam web development, aplikasi ini telah berhasil mencapai semua tujuan yang ditetapkan.'
);

$section->addParagraph('11.1 Pencapaian', 'Heading2');

$achievements = array(
    '✅ Sistem manajemen paket terintegrasi dan lengkap',
    '✅ 3 role dengan permission control yang tepat',
    '✅ Notifikasi real-time kepada penghuni',
    '✅ Dashboard monitoring untuk semua role',
    '✅ Keamanan tingkat enterprise',
    '✅ User interface yang responsive dan intuitif',
    '✅ Database yang terstruktur dengan baik',
    '✅ Dokumentasi lengkap dan mudah dipahami',
    '✅ Production ready dan siap di-deploy'
);

foreach ($achievements as $achievement) {
    $section->addParagraph($achievement, 'List Bullet');
}

$section->addParagraph('11.2 Rekomendasi ke Depan', 'Heading2');

$recommendations = array(
    'Implementasi Two-Factor Authentication (2FA) untuk keamanan tambahan',
    'Penambahan fitur reporting dan analytics yang lebih mendalam',
    'Integrasi dengan sistem paging atau SMS notification',
    'Mobile app native untuk penghuni (iOS & Android)',
    'Implementasi real-time notification dengan WebSocket',
    'Integrasi dengan payment gateway untuk layanan tambahan',
    'Automation dengan background jobs untuk tugas periodik',
    'Monitoring dan logging dengan tools seperti ELK Stack'
);

foreach ($recommendations as $rec) {
    $section->addParagraph($rec, 'List Bullet');
}

$section->addParagraph('11.3 Catatan Akhir', 'Heading2');

$section->addParagraph(
    'Aplikasi AureliaBox telah dikembangkan dengan standar profesional dan siap untuk '
    . 'deployment ke environment production. Semua fitur telah diuji dan berjalan dengan baik. '
    . 'Tim dapat melanjutkan dengan fase hosting dan deployment ke server.'
);

$section->addParagraph(
    'Laporan ini dibuat pada: ' . date('d B Y H:i:s', strtotime('today'))
);

// Save dokumen
$filename = 'LAPORAN_AKHIR_AureliaBox.docx';
$filepath = __DIR__ . '/' . $filename;

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save($filepath);

?>
    <p style="color: green; font-weight: bold;">
        ✅ Laporan berhasil dibuat!<br/>
        <a href="<?php echo $filename; ?>" target="_blank" style="color: blue; text-decoration: underline;">
            Klik di sini untuk download: LAPORAN_AKHIR_AureliaBox.docx
        </a>
    </p>
</body>
</html>
