<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

// Get penghuni ID from URL
$penghuni_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$penghuni_id) {
    header('Location: list.php');
    exit;
}

// Get penghuni data
$query = "SELECT ph.id, ph.user_id, ph.nomor_unit, ph.nomor_hp, ph.blok, ph.lantai, 
                 ph.nama_kontak_darurat, ph.nomor_kontak_darurat, ph.created_at, ph.updated_at,
                 u.username, u.email, u.role, u.nama_lengkap, u.is_active
         FROM penghuni ph
         JOIN users u ON ph.user_id = u.id
         WHERE ph.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $penghuni_id);
$stmt->execute();
$result = $stmt->get_result();
$penghuni = $result->fetch_assoc();

if (!$penghuni) {
    header('Location: list.php');
    exit;
}

$page_title = 'Detail Penghuni - ' . htmlspecialchars($penghuni['nama_lengkap']);
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="bi bi-person-vcard"></i> Detail Penghuni</h1>
                <p class="text-muted">Informasi lengkap data penghuni</p>
            </div>
            <div>
                <a href="edit.php?id=<?php echo $penghuni['id']; ?>" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="list.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="row">
            <!-- Profile Card -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="avatar-circle mx-auto mb-3" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                        <h4><?php echo htmlspecialchars($penghuni['nama_lengkap']); ?></h4>
                        <p class="text-muted mb-3">@<?php echo htmlspecialchars($penghuni['username']); ?></p>
                        
                        <!-- Status -->
                        <div class="mb-3">
                            <?php if ($penghuni['is_active']): ?>
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle"></i> Aktif
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary fs-6">
                                    <i class="bi bi-x-circle"></i> Nonaktif
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Aksi Cepat</h6>
                        <div class="d-grid gap-2">
                            <a href="edit.php?id=<?php echo $penghuni['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit Data
                            </a>
                            <a href="list.php" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-list"></i> Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Information -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Akun</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Username</strong>
                            </div>
                            <div class="col-sm-8">
                                <code><?php echo htmlspecialchars($penghuni['username']); ?></code>
                            </div>
                        </div>
                        <hr>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Email</strong>
                            </div>
                            <div class="col-sm-8">
                                <a href="mailto:<?php echo htmlspecialchars($penghuni['email']); ?>">
                                    <?php echo htmlspecialchars($penghuni['email']); ?>
                                </a>
                            </div>
                        </div>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nama Lengkap</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($penghuni['nama_lengkap']); ?>
                            </div>
                        </div>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Status</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php if ($penghuni['is_active']): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Nonaktif</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-building"></i> Informasi Penghuni</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nomor Unit</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($penghuni['nomor_unit']); ?>
                            </div>
                        </div>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nomor HP</strong>
                            </div>
                            <div class="col-sm-8">
                                <a href="tel:<?php echo htmlspecialchars($penghuni['nomor_hp']); ?>">
                                    <?php echo htmlspecialchars($penghuni['nomor_hp']); ?>
                                </a>
                            </div>
                        </div>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Blok</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($penghuni['blok'] ?? '-'); ?>
                            </div>
                        </div>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Lantai</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($penghuni['lantai'] ?? '-'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-telephone"></i> Kontak Darurat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nama Kontak</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($penghuni['nama_kontak_darurat'] ?? '-'); ?>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Nomor Kontak</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php if ($penghuni['nomor_kontak_darurat']): ?>
                                    <a href="tel:<?php echo htmlspecialchars($penghuni['nomor_kontak_darurat']); ?>">
                                        <?php echo htmlspecialchars($penghuni['nomor_kontak_darurat']); ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timestamp Information -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Dibuat Pada</strong>
                            </div>
                            <div class="col-sm-8">
                                <small class="text-muted">
                                    <?php 
                                    $created = new DateTime($penghuni['created_at']);
                                    echo $created->format('d M Y H:i:s');
                                    ?>
                                </small>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Diperbarui Pada</strong>
                            </div>
                            <div class="col-sm-8">
                                <small class="text-muted">
                                    <?php 
                                    $updated = new DateTime($penghuni['updated_at']);
                                    echo $updated->format('d M Y H:i:s');
                                    ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
