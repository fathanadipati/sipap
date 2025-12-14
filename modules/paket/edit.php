<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole(['admin', 'receptionist']);

$page_title = 'Edit Paket';
$error = '';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    header('Location: ' . BASE_URL . '/modules/paket/list.php');
    exit();
}

// Get data paket
$query = "SELECT * FROM paket WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ' . BASE_URL . '/modules/paket/list.php');
    exit();
}

$paket = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pengirim = isset($_POST['nama_pengirim']) ? trim($_POST['nama_pengirim']) : '';
    $nama_kurir = isset($_POST['nama_kurir']) ? trim($_POST['nama_kurir']) : '';
    $nama_ekspedisi = isset($_POST['nama_ekspedisi']) ? trim($_POST['nama_ekspedisi']) : '';
    $jenis_paket = isset($_POST['jenis_paket']) ? trim($_POST['jenis_paket']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    $nomor_loker = isset($_POST['nomor_loker']) ? trim($_POST['nomor_loker']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';
    $catatan = isset($_POST['catatan']) ? trim($_POST['catatan']) : '';
    
    if (empty($nama_pengirim) || empty($nama_kurir) || empty($nama_ekspedisi) || empty($jenis_paket) || empty($nomor_loker)) {
        $error = 'Semua field wajib diisi!';
    } else {
        $stmt = $conn->prepare("UPDATE paket SET nama_pengirim = ?, nama_kurir = ?, nama_ekspedisi = ?, 
                               jenis_paket = ?, deskripsi = ?, nomor_loker = ?, status = ?, catatan = ? WHERE id = ?");
        
        $stmt->bind_param('ssssssssi', $nama_pengirim, $nama_kurir, $nama_ekspedisi, $jenis_paket, 
                         $deskripsi, $nomor_loker, $status, $catatan, $id);
        
        if ($stmt->execute()) {
            // Jika status berubah menjadi 'diambil', set waktu diambil
            if ($status === 'diambil' && $paket['status'] !== 'diambil') {
                $update_time = $conn->prepare("UPDATE paket SET tanggal_diambil = NOW() WHERE id = ?");
                $update_time->bind_param('i', $id);
                $update_time->execute();
            }
            
            header('Location: ' . BASE_URL . '/modules/paket/list.php?msg=updated');
            exit();
        } else {
            $error = 'Gagal memperbarui paket!';
        }
    }
}

// Get daftar penghuni
$penghuni_query = "SELECT id, nomor_unit, nama_kontak_darurat FROM penghuni ORDER BY nomor_unit ASC";
$penghuni_result = $conn->query($penghuni_query);
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1><i class="bi bi-pencil"></i> Edit Paket</h1>
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
                        <h5 class="mb-0"><i class="bi bi-form-check"></i> Form Edit Paket - <?php echo htmlspecialchars($paket['nomor_paket']); ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <h6 class="text-muted mb-3">Informasi Paket</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_pengirim" class="form-label">Nama Pengirim *</label>
                                    <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim" 
                                           value="<?php echo htmlspecialchars($paket['nama_pengirim']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nama_kurir" class="form-label">Nama Kurir *</label>
                                    <input type="text" class="form-control" id="nama_kurir" name="nama_kurir" 
                                           value="<?php echo htmlspecialchars($paket['nama_kurir']); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_ekspedisi" class="form-label">Nama Ekspedisi *</label>
                                    <select class="form-select" id="nama_ekspedisi" name="nama_ekspedisi" required>
                                        <option value="">-- Pilih Ekspedisi --</option>
                                        <option value="JNE" <?php if($paket['nama_ekspedisi'] === 'JNE') echo 'selected'; ?>>JNE (Jasa Pengiriman Ekspres)</option>
                                        <option value="Tiki" <?php if($paket['nama_ekspedisi'] === 'Tiki') echo 'selected'; ?>>Tiki (Tasi Kirim)</option>
                                        <option value="Pos Indonesia" <?php if($paket['nama_ekspedisi'] === 'Pos Indonesia') echo 'selected'; ?>>Pos Indonesia</option>
                                        <option value="Grab Express" <?php if($paket['nama_ekspedisi'] === 'Grab Express') echo 'selected'; ?>>Grab Express</option>
                                        <option value="Gojek" <?php if($paket['nama_ekspedisi'] === 'Gojek') echo 'selected'; ?>>Gojek</option>
                                        <option value="Lazada" <?php if($paket['nama_ekspedisi'] === 'Lazada') echo 'selected'; ?>>Lazada Express</option>
                                        <option value="Shopee" <?php if($paket['nama_ekspedisi'] === 'Shopee') echo 'selected'; ?>>Shopee Express</option>
                                        <option value="Anteraja" <?php if($paket['nama_ekspedisi'] === 'Anteraja') echo 'selected'; ?>>Anteraja</option>
                                        <option value="Ninja Xpress" <?php if($paket['nama_ekspedisi'] === 'Ninja Xpress') echo 'selected'; ?>>Ninja Xpress</option>
                                        <option value="SiCepat" <?php if($paket['nama_ekspedisi'] === 'SiCepat') echo 'selected'; ?>>SiCepat</option>
                                        <option value="Lainnya" <?php if($paket['nama_ekspedisi'] === 'Lainnya') echo 'selected'; ?>>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_paket" class="form-label">Jenis Paket *</label>
                                    <select class="form-select" id="jenis_paket" name="jenis_paket" required>
                                        <option value="makanan_minuman" <?php if($paket['jenis_paket'] === 'makanan_minuman') echo 'selected'; ?>>Makanan / Minuman</option>
                                        <option value="barang_umum" <?php if($paket['jenis_paket'] === 'barang_umum') echo 'selected'; ?>>Barang Umum</option>
                                        <option value="barang_khusus" <?php if($paket['jenis_paket'] === 'barang_khusus') echo 'selected'; ?>>Barang Khusus</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Paket</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="2"><?php echo htmlspecialchars($paket['deskripsi'] ?? ''); ?></textarea>
                            </div>

                            <hr>

                            <h6 class="form-section-title">
                                <i class="bi bi-door-closed"></i> Status & Tempat Penyimpanan
                            </h6>
                            
                            <div class="row mb-4">
                                <div class="col-md-12 mb-3">
                                    <label for="status" class="form-label">Status Paket *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="disimpan" <?php if($paket['status'] === 'disimpan') echo 'selected'; ?>>Disimpan</option>
                                        <option value="diambil" <?php if($paket['status'] === 'diambil') echo 'selected'; ?>>Diambil</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tampilkan informasi berdasarkan jenis penyimpanan -->
                            <?php if ($paket['nomor_loker'] === 'WAREHOUSE'): ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-info-circle"></i> <strong>Penyimpanan Warehouse:</strong> Paket ini disimpan di warehouse dengan penanganan khusus.
                            </div>
                            <?php else: ?>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">Nomor Loker *</label>
                                    <div class="alert alert-info small">
                                        <i class="bi bi-info-circle"></i> Klik loker untuk memilih/mengubah nomor loker
                                    </div>
                                    
                                    <!-- Loker Grid Selector -->
                                    <div id="loker-grid" class="loker-grid">
                                        <!-- Grid akan di-generate dengan JavaScript -->
                                    </div>
                                    
                                    <!-- Hidden input untuk store selected loker -->
                                    <input type="hidden" id="nomor_loker" name="nomor_loker" 
                                           value="<?php echo htmlspecialchars($paket['nomor_loker']); ?>" required>
                                    
                                    <!-- Display selected loker -->
                                    <div class="mt-3">
                                        <span class="badge bg-success" id="selected-loker-display">
                                            Loker Terpilih: <span id="selected-loker-text"><?php echo htmlspecialchars($paket['nomor_loker']); ?></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="2" 
                                          placeholder="Catatan tambahan tentang paket"><?php echo htmlspecialchars($paket['catatan'] ?? ''); ?></textarea>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                                </button>
                                <a href="<?php echo BASE_URL; ?>/modules/paket/list.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi</h5>
                    </div>
                    <div class="card-body">
                        <p class="small">
                            <strong>Disimpan:</strong> Paket sudah disimpan di loker atau warehouse<br>
                            <strong>Diambil:</strong> Penghuni sudah mengambil paket
                        </p>
                        <hr>
                        <p class="small text-muted">
                            <i class="bi bi-clock"></i> Dibuat: <?php echo date('d M Y H:i', strtotime($paket['created_at'])); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
