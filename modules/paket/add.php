<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('receptionist');

$page_title = 'Terima Paket Baru';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pengirim = isset($_POST['nama_pengirim']) ? trim($_POST['nama_pengirim']) : '';
    $nama_kurir = isset($_POST['nama_kurir']) ? trim($_POST['nama_kurir']) : '';
    $nama_ekspedisi = isset($_POST['nama_ekspedisi']) ? trim($_POST['nama_ekspedisi']) : '';
    $jenis_paket = isset($_POST['jenis_paket']) ? trim($_POST['jenis_paket']) : '';
    $penghuni_id = isset($_POST['penghuni_id']) ? intval($_POST['penghuni_id']) : 0;
    $jenis_penyimpanan = isset($_POST['jenis_penyimpanan']) ? trim($_POST['jenis_penyimpanan']) : 'loker';
    $nomor_loker = isset($_POST['nomor_loker']) ? trim($_POST['nomor_loker']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    $resepsionis_id = $_SESSION['user_id'];
    
    // Validasi
    if (empty($nama_pengirim) || empty($nama_kurir) || empty($nama_ekspedisi) || empty($jenis_paket) || 
        $penghuni_id === 0) {
        $error = 'Semua field wajib diisi!';
    } elseif ($jenis_penyimpanan === 'loker' && empty($nomor_loker)) {
        $error = 'Nomor loker harus dipilih untuk penyimpanan di loker!';
    } else {
        // Generate nomor paket unik
        $nomor_paket = 'PKT-' . date('YmdHis') . '-' . rand(1000, 9999);
        
        // Untuk warehouse, set nomor_loker menjadi "WAREHOUSE"
        if ($jenis_penyimpanan === 'warehouse') {
            $nomor_loker = 'WAREHOUSE';
        }
        
        // Tambah paket
        $stmt = $conn->prepare("INSERT INTO paket (nomor_paket, penghuni_id, nama_pengirim, nama_kurir, 
                               nama_ekspedisi, jenis_paket, deskripsi, nomor_loker, status, resepsionis_id, tanggal_terima)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'disimpan', ?, NOW())");
        
        $stmt->bind_param('sissssssi', $nomor_paket, $penghuni_id, $nama_pengirim, $nama_kurir, 
                         $nama_ekspedisi, $jenis_paket, $deskripsi, $nomor_loker, $resepsionis_id);
        
        if ($stmt->execute()) {
            $paket_id = $conn->insert_id;
            
            // Buat notifikasi untuk penghuni
            if ($nomor_loker === 'WAREHOUSE') {
                $message = "Paket baru telah tiba dari " . $nama_pengirim . " (Ekspedisi: " . $nama_ekspedisi . "). Paket ini disimpan di warehouse.";
            } else {
                $message = "Paket baru telah tiba dari " . $nama_pengirim . " (Ekspedisi: " . $nama_ekspedisi . "). Silakan ambil di loker nomor " . $nomor_loker;
            }
            $notif_stmt = $conn->prepare("INSERT INTO notifikasi (penghuni_id, paket_id, pesan, is_read) VALUES (?, ?, ?, 0)");
            $notif_stmt->bind_param('iis', $penghuni_id, $paket_id, $message);
            $notif_stmt->execute();
            
            header('Location: ' . BASE_URL . '/modules/paket/list.php?msg=added');
            exit();
        } else {
            $error = 'Gagal menambahkan paket!';
        }
    }
}

// Get daftar penghuni
$penghuni_query = "SELECT p.id, p.nomor_unit, u.nama_lengkap FROM penghuni p 
                   JOIN users u ON p.user_id = u.id 
                   ORDER BY p.nomor_unit ASC";
$penghuni_result = $conn->query($penghuni_query);

// Get occupied lokers
$occupied_query = "SELECT DISTINCT nomor_loker FROM paket WHERE status = 'disimpan' AND nomor_loker != 'WAREHOUSE' AND nomor_loker IS NOT NULL";
$occupied_result = $conn->query($occupied_query);
$occupied_lokers = [];
if ($occupied_result && $occupied_result->num_rows > 0) {
    while ($row = $occupied_result->fetch_assoc()) {
        $occupied_lokers[] = (int)$row['nomor_loker'];
    }
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1><i class="bi bi-box2-heart"></i> Terima Paket Baru</h1>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-form-check"></i> Form Penerimaan Paket</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <!-- Bagian 1: Informasi Pengirim & Kurir -->
                            <div class="mb-4">
                                <h6 class="form-section-title">
                                    <i class="bi bi-person-check"></i> Informasi Pengirim & Kurir
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_pengirim" class="form-label">Nama Pengirim *</label>
                                        <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim" 
                                               placeholder="Nama orang/perusahaan yang mengirim" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_kurir" class="form-label">Nama Kurir *</label>
                                        <input type="text" class="form-control" id="nama_kurir" name="nama_kurir" 
                                               placeholder="Nama kurir/pengantar" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian 2: Informasi Paket -->
                            <div class="mb-4">
                                <h6 class="form-section-title">
                                    <i class="bi bi-box2"></i> Informasi Paket
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_ekspedisi" class="form-label">Nama Ekspedisi *</label>
                                        <select class="form-select" id="nama_ekspedisi" name="nama_ekspedisi" required>
                                            <option value="">-- Pilih Ekspedisi --</option>
                                            <option value="Anteraja">Anteraja</option>
                                            <option value="Gojek">Gojek</option>
                                            <option value="Grab Express">Grab Express</option>
                                            <option value="JNE">JNE</option>
                                            <option value="JNT">JNT</option>
                                            <option value="Lalamove">Lalamove</option>
                                            <option value="Pos Indonesia">Pos Indonesia</option>
                                            <option value="SiCepat">SiCepat</option>
                                            <option value="Shopee">Shopee Express</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jenis_paket" class="form-label">Jenis Paket *</label>
                                        <select class="form-select" id="jenis_paket" name="jenis_paket" required>
                                            <option value="">-- Pilih Jenis --</option>
                                            <option value="makanan_minuman">Makanan / Minuman</option>
                                            <option value="barang_umum">Barang Umum</option>
                                            <option value="barang_khusus">Barang Khusus</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Paket (Opsional)</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" 
                                              rows="2" placeholder="Contoh: Paket makanan, dokumen penting, barang elektronik, dll"></textarea>
                                </div>
                            </div>

                            <!-- Bagian 3: Informasi Penerima & Loker -->
                            <div class="form-section-divider"></div>
                            <div class="mb-4">
                                <h6 class="form-section-title">
                                    <i class="bi bi-door-closed"></i> Informasi Penerima & Tempat Penyimpanan
                                </h6>
                                <div class="row mb-4">
                                    <div class="col-md-12 mb-3">
                                        <label for="penghuni_id" class="form-label">Unit Penghuni *</label>
                                        <select class="form-select" id="penghuni_id" name="penghuni_id" required>
                                            <option value="">-- Pilih Unit --</option>
                                            <?php 
                                            // Reset query result
                                            $penghuni_result = $conn->query("SELECT p.id, p.nomor_unit, u.nama_lengkap FROM penghuni p 
                                                                               JOIN users u ON p.user_id = u.id 
                                                                               ORDER BY p.nomor_unit ASC");
                                            while ($penghuni = $penghuni_result->fetch_assoc()): 
                                            ?>
                                            <option value="<?php echo $penghuni['id']; ?>">
                                                <?php echo $penghuni['nomor_unit'] . ' - ' . $penghuni['nama_lengkap']; ?>
                                            </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Pilihan Tempat Penyimpanan -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label">Tempat Penyimpanan *</label>
                                        <div class="btn-group w-100" role="group" style="height: 50px;">
                                            <input type="radio" class="btn-check" name="jenis_penyimpanan" id="penyimpanan_loker" value="loker" required checked>
                                            <label class="btn btn-outline-primary" for="penyimpanan_loker" style="border-radius: 8px 0 0 8px; flex: 1;">
                                                <i class="bi bi-door-closed"></i> Di Loker
                                            </label>
                                            
                                            <input type="radio" class="btn-check" name="jenis_penyimpanan" id="penyimpanan_warehouse" value="warehouse" required>
                                            <label class="btn btn-outline-primary" for="penyimpanan_warehouse" style="border-radius: 0 8px 8px 0; flex: 1;">
                                                <i class="bi bi-building"></i> Di Warehouse
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <strong>Di Loker:</strong> Untuk paket berukuran kecil-sedang
                                            <br><strong>Di Warehouse:</strong> Untuk paket berukuran besar atau memerlukan penanganan khusus
                                        </small>
                                    </div>
                                </div>

                                <!-- Loker Grid (Visible hanya saat Di Loker) -->
                                <div id="loker-section" style="display: block;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Nomor Loker *</label>
                                            <div class="alert alert-info small">
                                                <i class="bi bi-info-circle"></i> Klik loker yang tersedia untuk memilih nomor loker
                                            </div>
                                            
                                            <!-- Loker Grid Selector -->
                                            <div id="loker-grid" class="loker-grid">
                                                <!-- Grid akan di-generate dengan JavaScript -->
                                            </div>
                                            
                                            <!-- Hidden input untuk store selected loker -->
                                            <input type="hidden" id="nomor_loker" name="nomor_loker">
                                            
                                            <!-- Display selected loker -->
                                            <div class="mt-3">
                                                <span class="badge bg-success" id="selected-loker-display" style="display: none;">
                                                    Loker Terpilih: <span id="selected-loker-text"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Warehouse Note (Visible hanya saat Di Warehouse) -->
                                <div id="warehouse-section" style="display: none;">
                                    <div class="alert alert-info" style="background-color: #e7f3ff; border-color: #b3d9ff;">
                                        <i class="bi bi-building" style="color: #0066cc; font-size: 1.2rem;"></i> 
                                        <strong style="color: #0066cc;">Penyimpanan Warehouse</strong><br>
                                        <small style="color: #004499;">Paket akan disimpan di warehouse dengan penanganan khusus. Pastikan deskripsi paket jelas untuk handling yang tepat.</small>
                                    </div>
                                    <div style="text-align: center; padding: 20px; background-color: #f0f8ff; border-radius: 8px; border: 2px dashed #0066cc;">
                                        <span class="badge" style="background-color: #0066cc; font-size: 0.95rem; padding: 8px 16px;">
                                            <i class="bi bi-building"></i> WAREHOUSE
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success btn-lg" style="min-width: 150px;">
                                    <i class="bi bi-check-circle"></i> Simpan Paket
                                </button>
                                <a href="<?php echo BASE_URL; ?>/modules/paket/list.php" class="btn btn-secondary btn-lg">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Panduan</h5>
                    </div>
                    <div class="card-body">
                        <h6>Langkah-langkah:</h6>
                        <ol class="small">
                            <li>Isi informasi pengirim dan kurir</li>
                            <li>Pilih jenis paket dengan benar</li>
                            <li>Pilih unit penghuni penerima</li>
                            <li>Pilih tempat penyimpanan: Di Loker atau Di Warehouse</li>
                            <li>Untuk loker: Pilih nomor loker dari grid (1-50)</li>
                            <li>Klik "Simpan Paket"</li>
                        </ol>
                        <p class="small text-muted mt-3">
                            <i class="bi bi-bell"></i> Notifikasi otomatis akan dikirim ke penghuni setelah paket dicatat.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle loker section based on jenis_penyimpanan
function togglePenyimpananSection() {
    const jenisPenyimpanan = document.querySelector('input[name="jenis_penyimpanan"]:checked').value;
    const lokerSection = document.getElementById('loker-section');
    const warehouseSection = document.getElementById('warehouse-section');
    const nomor_loker = document.getElementById('nomor_loker');
    
    if (jenisPenyimpanan === 'loker') {
        lokerSection.style.display = 'block';
        warehouseSection.style.display = 'none';
        nomor_loker.required = true;
    } else {
        lokerSection.style.display = 'none';
        warehouseSection.style.display = 'block';
        nomor_loker.required = false;
        nomor_loker.value = ''; // Clear loker selection
    }
}

// Add event listeners to radio buttons
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="jenis_penyimpanan"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', togglePenyimpananSection);
    });
    
    // Initialize on page load
    togglePenyimpananSection();
    
    // Initialize loker grid with occupied lokers data
    const occupiedLokers = <?php echo json_encode($occupied_lokers); ?>;
    if (window.initLokerGrid) {
        initLokerGrid(occupiedLokers);
    }
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
