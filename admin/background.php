<?php
require_once __DIR__ . '/../config/session.php';
requireRole(['admin']);

$page_title = 'Upload Background';
$upload_message = '';
$upload_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['background_image'])) {
    $file = $_FILES['background_image'];
    
    // Validasi
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $upload_error = 'Error upload file: ' . $file['error'];
    } elseif (!in_array($file_ext, $allowed_extensions)) {
        $upload_error = 'Tipe file tidak diizinkan. Gunakan JPG, PNG, atau WebP.';
    } elseif ($file['size'] > $max_size) {
        $upload_error = 'Ukuran file terlalu besar (max 5MB).';
    } else {
        // Upload file
        $target_dir = __DIR__ . '/../assets/images/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $filename = 'background-login.' . $file_ext;
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $upload_message = 'Background berhasil di-upload! File tersimpan sebagai: ' . $filename;
            
            // Log aktivitas
            $log_message = "Admin " . $_SESSION['username'] . " upload background image: " . $filename;
            error_log($log_message);
        } else {
            $upload_error = 'Gagal menyimpan file.';
        }
    }
}

// Get existing background
$background_files = [];
$image_dir = __DIR__ . '/../assets/images/';
if (is_dir($image_dir)) {
    $files = scandir($image_dir);
    foreach ($files as $file) {
        if (preg_match('/^background-login\.(jpg|jpeg|png|webp)$/i', $file)) {
            $background_files[] = $file;
        }
    }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container">
        <h1 class="mb-4">
            <i class="bi bi-image"></i> Kelola Background Halaman Login
        </h1>

        <?php if (!empty($upload_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $upload_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (!empty($upload_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> <?php echo $upload_error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row">
            <!-- Upload Form -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-cloud-upload"></i> Upload Background Baru</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label"><strong>Pilih File Gambar</strong></label>
                                <input type="file" name="background_image" class="form-control" accept="image/jpeg,image/png,image/webp" required>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle"></i> Format: JPG, PNG, WebP | Max: 5MB
                                </small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><strong>Rekomendasi:</strong></label>
                                <ul class="small text-muted">
                                    <li>Ukuran minimal: 1920×1080px</li>
                                    <li>Ukuran file: < 1MB untuk performa optimal</li>
                                    <li>Format WebP paling efisien (ukuran lebih kecil)</li>
                                    <li>Gunakan gambar dengan warna cerah agar teks terbaca</li>
                                </ul>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-upload"></i> Upload Background
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Current Background -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-image"></i> Background Saat Ini</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($background_files)): ?>
                            <p class="text-muted mb-3">Background yang sedang digunakan:</p>
                            <div class="list-group">
                                <?php foreach ($background_files as $file): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><i class="bi bi-file-image"></i> <?php echo htmlspecialchars($file); ?></h6>
                                        <small class="text-muted">
                                            Ukuran: <?php echo round(filesize($image_dir . $file) / 1024, 2); ?> KB
                                        </small>
                                    </div>
                                    <a href="<?php echo BASE_URL; ?>/assets/images/<?php echo htmlspecialchars($file); ?>" 
                                       target="_blank" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="bi bi-info-circle"></i> <strong>Info:</strong> 
                                Untuk mengganti background, upload file baru dengan nama yang sama. 
                                Halaman login akan otomatis menggunakan background terbaru.
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-exclamation-triangle"></i> <strong>Belum ada background.</strong>
                                <p class="mb-0">Upload file background untuk mengubah tampilan halaman login dari gradient default.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="<?php echo BASE_URL; ?>/dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
