<?php
// Perbaiki path: __DIR__ adalah C:\xampp\htdocs\sipap\admin
// Kita perlu naik satu level ke C:\xampp\htdocs\sipap
$basePath = dirname(__DIR__);
require_once $basePath . '/config/database.php';
require_once $basePath . '/config/session.php';

requireRole('admin');

$page_title = 'Tambah Pengguna';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';
    
    if (empty($nama_lengkap) || empty($username) || empty($email) || empty($password) || empty($role)) {
        $error = 'Semua field wajib diisi!';
    } elseif (!in_array($role, ['admin', 'receptionist'])) {
        $error = 'Role tidak valid!';
    } else {
        // Check username sudah ada
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param('s', $username);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            $error = 'Username sudah terdaftar!';
        } else {
            // Tambah user
            $hashed_password = hashPassword($password);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, nama_lengkap, is_active) VALUES (?, ?, ?, ?, ?, 1)");
            $stmt->bind_param('sssss', $username, $email, $hashed_password, $role, $nama_lengkap);
            
            if ($stmt->execute()) {
                header('Location: ' . BASE_URL . '/admin/users.php?msg=added');
                exit();
            } else {
                $error = 'Gagal membuat akun pengguna!';
            }
        }
    }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 style="font-weight: bold; color: #333; margin-bottom: 0.5rem;">
                    <i class="bi bi-person-badge-fill" style="font-size: 2rem; color: #ffc107;"></i> Tambah Pengguna Baru
                </h1>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Daftarkan Admin atau Receptionist baru ke dalam sistem</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/admin/users.php" class="btn btn-secondary" style="padding: 0.6rem 1.5rem;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 4px solid #dc3545;">
                <div class="d-flex gap-3">
                    <div style="font-size: 1.3rem;">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div>
                        <h5 class="mt-0">Terjadi Kesalahan</h5>
                        <p class="mb-0"><?php echo $error; ?></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Form Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow" style="border-radius: 0.5rem;">
                    <div class="card-header bg-dark text-white" style="border-bottom: 2px solid #555; padding: 1.5rem;">
                        <h5 class="mb-0"><i class="bi bi-form-check"></i> Form Data Pengguna</h5>
                    </div>
                    <div class="card-body" style="padding: 2rem;">
                        <form method="POST" action="">
                            <!-- Informasi Akun -->
                            <div class="mb-4">
                                <h6 class="fw-bold" style="font-size: 1rem; color: #333; margin-bottom: 1.5rem;">
                                    <i class="bi bi-shield-check" style="color: #ffc107;"></i> Informasi Akun
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap <span style="color: #dc3545;">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="username" class="form-label fw-bold">Username <span style="color: #dc3545;">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Masukkan username unik" required>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-bold">Email <span style="color: #dc3545;">*</span></label>
                                        <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="email@example.com" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-bold">Password <span style="color: #dc3545;">*</span></label>
                                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Masukkan password" required>
                                    </div>
                                </div>
                            </div>

                            <hr style="margin: 2rem 0;">

                            <!-- Informasi Role -->
                            <div class="mb-4">
                                <h6 class="fw-bold" style="font-size: 1rem; color: #333; margin-bottom: 1.5rem;">
                                    <i class="bi bi-lock-fill" style="color: #dc3545;"></i> Pengaturan Role
                                </h6>
                                <div>
                                    <label for="role" class="form-label fw-bold">Pilih Role <span style="color: #dc3545;">*</span></label>
                                    <select class="form-select form-select-lg" id="role" name="role" required>
                                        <option value="">-- Pilih Role --</option>
                                        <option value="admin">Admin - Akses Penuh</option>
                                        <option value="receptionist">Receptionist - Kelola Paket</option>
                                    </select>
                                    <small class="text-muted d-block mt-2">
                                        <strong>Admin:</strong> Akses ke seluruh fitur sistem
                                        <br>
                                        <strong>Receptionist:</strong> Hanya untuk manajemen paket
                                    </small>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2 d-md-flex gap-md-3 mt-5">
                                <button type="submit" class="btn btn-success btn-lg" style="font-weight: bold;">
                                    <i class="bi bi-check-circle"></i> Simpan Pengguna
                                </button>
                                <a href="<?php echo BASE_URL; ?>/admin/users.php" class="btn btn-outline-secondary btn-lg" style="font-weight: bold;">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow" style="border-left: 4px solid #ffc107; border-radius: 0.5rem; position: sticky; top: 100px;">
                    <div class="card-body" style="padding: 2rem;">
                        <h6 class="fw-bold mb-3" style="color: #333;">
                            <i class="bi bi-info-circle" style="color: #ffc107;"></i> Informasi Penting
                        </h6>
                        <div style="font-size: 0.95rem; line-height: 1.8; color: #666;">
                            <p><strong>Username:</strong> Harus unik dan tidak dapat diubah</p>
                            <p><strong>Password:</strong> Berikan kepada pengguna untuk login pertama kali</p>
                            <p><strong>Email:</strong> Gunakan email yang masih aktif untuk notifikasi</p>
                            <p><strong>Admin:</strong> Dapat mengelola seluruh sistem</p>
                            <p><strong>Receptionist:</strong> Hanya mengelola paket masuk dan loker</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
