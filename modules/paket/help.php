<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('resident');

$page_title = 'Panduan & Bantuan';

// Get resident data
$query = "SELECT id FROM penghuni WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$resident = $result->fetch_assoc();
$resident_id = $resident['id'] ?? null;

// Get paket stats
$paket_menunggu = $conn->query("SELECT COUNT(*) as total FROM paket WHERE penghuni_id = $resident_id AND status IN ('diterima', 'disimpan')")->fetch_assoc()['total'];
$paket_diambil = $conn->query("SELECT COUNT(*) as total FROM paket WHERE penghuni_id = $resident_id AND status = 'diambil'")->fetch_assoc()['total'];
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-question-circle"></i> Panduan & Bantuan</h1>
            <a href="<?php echo BASE_URL; ?>/dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 style="color: #007bff; font-weight: bold;"><?php echo $paket_menunggu; ?></h3>
                        <p class="text-muted mb-0">Paket Menunggu Diambil</p>
                        <small class="text-muted">Diterima atau Tersimpan</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 style="color: #28a745; font-weight: bold;"><?php echo $paket_diambil; ?></h3>
                        <p class="text-muted mb-0">Paket Sudah Diambil</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Accordion -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-book"></i> Pertanyaan Umum (FAQ)</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <i class="bi bi-envelope"></i> Bagaimana cara mengecek paket saya?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Anda dapat mengecek status paket Anda melalui dashboard atau menu <strong>"Paket Saya"</strong>. Setiap paket akan menampilkan:</p>
                                <ul>
                                    <li><strong>Nomor Paket:</strong> Identitas unik paket Anda</li>
                                    <li><strong>Nama Pengirim:</strong> Siapa yang mengirimkan paket</li>
                                    <li><strong>Nama Ekspedisi:</strong> Kurir atau jasa pengiriman</li>
                                    <li><strong>Nomor Loker/Penyimpanan:</strong> Lokasi penyimpanan paket</li>
                                    <li><strong>Status:</strong> Tersimpan atau Sudah Diambil</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <i class="bi bi-door-closed"></i> Apa itu nomor loker/penyimpanan?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Nomor loker/penyimpanan adalah lokasi di mana paket Anda disimpan untuk keamanan. Format dapat berupa:</p>
                                <ul>
                                    <li><strong>Nomor Loker:</strong> Paket kecil disimpan di loker dengan nomor 1 sampai 50</li>
                                    <li><strong>Warehouse:</strong> Paket besar atau banyak disimpan di gudang</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <i class="bi bi-clock"></i> Berapa lama paket disimpan?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Durasi penyimpanan paket tergantung kebijakan apartemen Anda. Umumnya:</p>
                                <ul>
                                    <li><strong>7-14 hari:</strong> Periode penyimpanan standar</li>
                                    <li><strong>Notifikasi:</strong> Resepsionis akan memberi tahu jika mendekati batas waktu</li>
                                    <li><strong>Perpanjangan Penyimpanan:</strong> Hubungi resepsionis untuk penanganan lebih lanjut</li>
                                </ul>
                                <p class="mt-3">⚠️ Paket yang tidak diambil dalam waktu yang ditentukan dapat disimpan/didisposisi oleh pihak manajemen.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                <i class="bi bi-exclamation-triangle"></i> Paket saya hilang, apa yang harus dilakukan?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Jika paket Anda hilang atau tidak ditemukan, ikuti langkah berikut:</p>
                                <ol>
                                    <li>Hubungi <strong>meja resepsionis</strong> segera</li>
                                    <li>Siapkan <strong>nomor paket</strong> dan <strong>nomor loker</strong> Anda</li>
                                    <li>Jika tidak ada data, hubungi <strong>kurir/ekspedisi</strong> pengirim</li>
                                    <li>Buatlah <strong>laporan resmi</strong> jika diperlukan untuk klaim asuransi</li>
                                </ol>
                                <p class="mt-3">📞 <strong>Kontak Resepsionis:</strong> Tanyakan ke kantor manajemen</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                <i class="bi bi-shield-check"></i> Apakah paket saya aman?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Paket Anda disimpan dengan aman oleh staf resepsionis. Keamanan meliputi:</p>
                                <ul>
                                    <li>✓ Penyimpanan di loker/gudang tertutup</li>
                                    <li>✓ Pencatatan digital setiap paket masuk dan keluar</li>
                                    <li>✓ Pengecekan identitas saat pengambilan</li>
                                    <li>✓ Perlindungan dari cuaca dan kerusakan</li>
                                </ul>
                                <p class="mt-3">Jika paket berisi barang berharga, sebaiknya asuransikan sebelum pengiriman.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips & Trik -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Tips & Trik</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-info border-0" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-star"></i> Notifikasi Otomatis</h6>
                            <p class="mb-0">Sistem akan memberitahu Anda saat ada paket baru masuk. Periksa notifikasi Anda secara berkala.</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-warning border-0" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-telephone"></i> Hubungi Resepsionis</h6>
                            <p class="mb-0">Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi resepsionis melalui telepon atau kunjungi langsung.</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-success border-0" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-check-circle"></i> Ambil Segera</h6>
                            <p class="mb-0">Segera ambil paket Anda untuk menghindari keterlambatan atau kehilangan.</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-primary border-0" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-eye"></i> Pantau Dashboard</h6>
                            <p class="mb-0">Selalu periksa dashboard "Paket Saya" untuk status terbaru paket Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-telephone"></i> Butuh Bantuan Lebih Lanjut?</h5>
            </div>
            <div class="card-body">
                <p>Jika Anda memiliki pertanyaan atau masalah yang tidak terjawab, hubungi:</p>
                <ul class="list-unstyled">
                    <li><strong>📞 Hubungi Meja Resepsionis:</strong> <span class="text-muted">Hubungi melalui panel atau kunjungi meja resepsionis secara langsung.</span></li>
                    <li><strong>⏰ Jam Layanan:</strong> <span class="text-muted">Layanan tersedia 24 jam</span></li>
                    <li><strong>📧 Email:</strong> <span class="text-muted">Hubungi manajemen untuk informasi lebih lanjut : customerservice@aureliabox.local</span></li>
                </ul>
                <p class="mt-3 text-muted">
                    <i class="bi bi-info-circle"></i> 
                    Panduan ini disediakan untuk membantu Anda menggunakan sistem manajemen paket apartemen dengan lebih baik.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
