<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

$page_title = 'Edit Penghuni';
$error = '';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    header('Location: ' . BASE_URL . '/modules/penghuni/list.php');
    exit();
}

// Get data penghuni
$query = "SELECT ph.id, ph.user_id, ph.nomor_unit, ph.nomor_hp, ph.blok, ph.lantai, ph.nama_kontak_darurat, ph.nomor_kontak_darurat,
                 u.nama_lengkap, u.email, u.username
          FROM penghuni ph
          JOIN users u ON ph.user_id = u.id
          WHERE ph.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ' . BASE_URL . '/modules/penghuni/list.php');
    exit();
}

$penghuni = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $nomor_hp = isset($_POST['nomor_hp']) ? trim($_POST['nomor_hp']) : '';
    $blok = isset($_POST['blok']) ? trim($_POST['blok']) : '';
    $lantai = isset($_POST['lantai']) ? trim($_POST['lantai']) : '';
    $nama_kontak = isset($_POST['nama_kontak_darurat']) ? trim($_POST['nama_kontak_darurat']) : '';
    $nomor_kontak = isset($_POST['nomor_kontak_darurat']) ? trim($_POST['nomor_kontak_darurat']) : '';
    
    if (empty($nama_lengkap) || empty($email) || empty($nomor_hp)) {
        $error = 'Semua field wajib diisi!';
    } else {
        // Update user
        $stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, email = ? WHERE id = ?");
        $stmt->bind_param('ssi', $nama_lengkap, $email, $penghuni['user_id']);
        
        if ($stmt->execute()) {
            // Update penghuni
            $stmt2 = $conn->prepare("UPDATE penghuni SET nomor_hp = ?, blok = ?, lantai = ?, nama_kontak_darurat = ?, nomor_kontak_darurat = ? WHERE id = ?");
            $stmt2->bind_param('ssissi', $nomor_hp, $blok, $lantai, $nama_kontak, $nomor_kontak, $id);
            
            if ($stmt2->execute()) {
                header('Location: ' . BASE_URL . '/modules/penghuni/list.php?msg=updated');
                exit();
            } else {
                $error = 'Gagal memperbarui data penghuni!';
            }
        } else {
            $error = 'Gagal memperbarui akun pengguna!';
        }
    }
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>


<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="bi bi-person-circle"></i> Edit Penghuni</h1>
                <p class="text-muted">Perbarui informasi data penghuni</p>
            </div>
            <div>
                <a href="list.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

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
                        <p class="text-muted mb-3"><strong><?php echo htmlspecialchars($penghuni['nomor_unit']); ?></strong></p>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Informasi Unit</h6>
                        <div class="mb-2">
                            <small class="text-muted">Nomor Unit</small>
                            <p class="mb-0"><strong><?php echo htmlspecialchars($penghuni['nomor_unit']); ?></strong></p>
                        </div>
                        <hr>
                        <div>
                            <small class="text-muted">Blok</small>
                            <p class="mb-0"><strong><?php echo htmlspecialchars($penghuni['blok'] ?? '-'); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-form-check"></i> Informasi Akun</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                           value="<?php echo htmlspecialchars($penghuni['nama_lengkap']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" 
                                           value="<?php echo htmlspecialchars($penghuni['username']); ?>" disabled>
                                    <small class="text-muted">Username tidak dapat diubah</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($penghuni['email']); ?>" required>
                                </div>
                            </div>

                            <hr>

                            <h6 class="text-muted mb-3">Informasi Penghuni</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nomor_hp" class="form-label">Nomor HP *</label>
                                    <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp" 
                                           value="<?php echo htmlspecialchars($penghuni['nomor_hp']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="blok" class="form-label">Blok</label>
                                    <input type="text" class="form-control" id="blok" name="blok" 
                                           value="<?php echo htmlspecialchars($penghuni['blok'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="lantai" class="form-label">Lantai</label>
                                    <input type="number" class="form-control" id="lantai" name="lantai" 
                                           value="<?php echo htmlspecialchars($penghuni['lantai'] ?? ''); ?>">
                                </div>
                            </div>

                            <hr>

                            <h6 class="text-muted mb-3">Kontak Darurat</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_kontak_darurat" class="form-label">Nama Kontak</label>
                                    <input type="text" class="form-control" id="nama_kontak_darurat" name="nama_kontak_darurat" 
                                           value="<?php echo htmlspecialchars($penghuni['nama_kontak_darurat'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nomor_kontak_darurat" class="form-label">Nomor Kontak</label>
                                    <input type="tel" class="form-control" id="nomor_kontak_darurat" name="nomor_kontak_darurat" 
                                           value="<?php echo htmlspecialchars($penghuni['nomor_kontak_darurat'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                                </button>
                                <a href="list.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>