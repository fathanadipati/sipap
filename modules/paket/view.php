<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireLogin();

$page_title = 'Detail Paket';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Normalize old role names to new ones
$_SESSION['role'] = $_SESSION['role'] === 'penghuni' ? 'resident' : $_SESSION['role'];
$_SESSION['role'] = $_SESSION['role'] === 'resepsionis' ? 'receptionist' : $_SESSION['role'];

if ($id === 0) {
    header('Location: ' . BASE_URL . '/modules/paket/list.php');
    exit();
}

// Get data paket lengkap
$query = "SELECT p.*, ph.nomor_unit, ph.nama_kontak_darurat, 
                 u.nama_lengkap as nama_resepsionis, u_penghuni.nama_lengkap as nama_penghuni
         FROM paket p
         JOIN penghuni ph ON p.penghuni_id = ph.id
         JOIN users u ON p.resepsionis_id = u.id
         JOIN users u_penghuni ON ph.user_id = u_penghuni.id
         WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ' . BASE_URL . '/modules/paket/list.php');
    exit();
}

$paket = $result->fetch_assoc();

// Cek akses: resident hanya bisa lihat paket mereka sendiri
if ($_SESSION['role'] === 'resident') {
    $penghuni_query = "SELECT id FROM penghuni WHERE user_id = ?";
    $penghuni_stmt = $conn->prepare($penghuni_query);
    $penghuni_stmt->bind_param('i', $_SESSION['user_id']);
    $penghuni_stmt->execute();
    $penghuni_result = $penghuni_stmt->get_result();
    $penghuni = $penghuni_result->fetch_assoc();
    
    if ($penghuni['id'] !== $paket['penghuni_id']) {
        header('Location: ' . BASE_URL . '/forbidden.php');
        exit();
    }
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-box2"></i> Detail Paket</h1>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-box2-heart"></i> Informasi Paket</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Nomor Paket</label>
                                <p class="h6"><?php echo htmlspecialchars($paket['nomor_paket']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Status</label>
                                <p class="h6">
                                    <?php
                                    $status_classes = [
                                        'disimpan' => 'info',
                                        'diambil' => 'success'
                                    ];
                                    $status_text = [
                                        'disimpan' => 'Tersimpan',
                                        'diambil' => 'Sudah Diambil'
                                    ];
                                    ?>
                                    <span class="badge bg-<?php echo $status_classes[$paket['status']]; ?>">
                                        <?php echo $status_text[$paket['status']]; ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Nama Pengirim</label>
                                <p><?php echo htmlspecialchars($paket['nama_pengirim']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Nama Kurir</label>
                                <p><?php echo htmlspecialchars($paket['nama_kurir']); ?></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Ekspedisi</label>
                                <p><?php echo htmlspecialchars($paket['nama_ekspedisi']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Jenis Paket</label>
                                <p>
                                    <?php
                                    $jenis_text = [
                                        'makanan_minuman' => 'Makanan / Minuman',
                                        'barang_umum' => 'Barang Umum',
                                        'barang_khusus' => 'Barang Khusus'
                                    ];
                                    echo $jenis_text[$paket['jenis_paket']];
                                    ?>
                                </p>
                            </div>
                        </div>

                        <?php if (!empty($paket['deskripsi'])): ?>
                        <div class="mb-3">
                            <label class="form-label text-muted">Deskripsi</label>
                            <p><?php echo htmlspecialchars($paket['deskripsi']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-door-closed"></i> Informasi Penyimpanan & Penerima</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Nomor Penyimpanan</label>
                                <p class="h6">
                                    <?php
                                    if ($paket['nomor_loker'] === 'WAREHOUSE') {
                                        echo '<span class="badge" style="background-color: #0066cc;"><i class="bi bi-building"></i> ' . htmlspecialchars($paket['nomor_loker']) . '</span>';
                                    } else {
                                        echo '<span class="badge bg-info">' . htmlspecialchars($paket['nomor_loker']) . '</span>';
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Unit Penghuni</label>
                                <p><?php echo htmlspecialchars($paket['nomor_unit']); ?></p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Nama Penghuni</label>
                                <p><?php echo htmlspecialchars($paket['nama_penghuni']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Resepsionis</label>
                                <p><?php echo htmlspecialchars($paket['nama_resepsionis']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Riwayat Waktu</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Tanggal Diterima</label>
                            <p><?php echo date('d M Y H:i:s', strtotime($paket['tanggal_terima'])); ?></p>
                        </div>

                        <?php if ($paket['status'] === 'diambil' && !empty($paket['tanggal_diambil'])): ?>
                        <div class="mb-3">
                            <label class="form-label text-muted">Tanggal Diambil</label>
                            <p><?php echo date('d M Y H:i:s', strtotime($paket['tanggal_diambil'])); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($paket['catatan'])): ?>
                        <div class="mb-3">
                            <label class="form-label text-muted">Catatan</label>
                            <p><?php echo htmlspecialchars($paket['catatan']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-list-check"></i> Status Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item <?php echo in_array($paket['status'], ['disimpan', 'diambil']) ? 'active' : ''; ?>">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6>Diterima dari Kurir</h6>
                                    <small class="text-muted">
                                        <?php echo date('d M Y H:i', strtotime($paket['tanggal_terima'])); ?>
                                    </small>
                                </div>
                            </div>

                            <div class="timeline-item <?php echo in_array($paket['status'], ['disimpan', 'diambil']) ? 'active' : ''; ?>">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6>Disimpan</h6>
                                    <small class="text-muted">
                                        <?php
                                        if ($paket['nomor_loker'] === 'WAREHOUSE') {
                                            echo '<i class="bi bi-building"></i> ' . htmlspecialchars($paket['nomor_loker']);
                                        } else {
                                            echo 'Loker No. ' . htmlspecialchars($paket['nomor_loker']);
                                        }
                                        ?>
                                    </small>
                                </div>
                            </div>

                            <div class="timeline-item <?php echo $paket['status'] === 'diambil' ? 'active' : ''; ?>">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6>Diambil oleh Penghuni</h6>
                                    <?php if ($paket['status'] === 'diambil' && !empty($paket['tanggal_diambil'])): ?>
                                    <small class="text-muted">
                                        <?php echo date('d M Y H:i', strtotime($paket['tanggal_diambil'])); ?>
                                    </small>
                                    <?php else: ?>
                                    <small class="text-muted">Menunggu...</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
    opacity: 0.5;
}

.timeline-item.active {
    opacity: 1;
}

.timeline-marker {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 3px solid white;
    flex-shrink: 0;
    margin-top: 5px;
}

.timeline-content h6 {
    margin: 0 0 5px 0;
    font-weight: 600;
}

.timeline-content small {
    display: block;
}
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
