<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

$page_title = 'Tambah Penghuni';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $nomor_unit = isset($_POST['nomor_unit']) ? trim($_POST['nomor_unit']) : '';
    $nomor_hp = isset($_POST['nomor_hp']) ? trim($_POST['nomor_hp']) : '';
    $blok = isset($_POST['blok']) ? trim($_POST['blok']) : '';
    $lantai = isset($_POST['lantai']) ? trim($_POST['lantai']) : '';
    $nama_kontak = isset($_POST['nama_kontak_darurat']) ? trim($_POST['nama_kontak_darurat']) : '';
    $nomor_kontak = isset($_POST['nomor_kontak_darurat']) ? trim($_POST['nomor_kontak_darurat']) : '';
    
    if (empty($nama_lengkap) || empty($username) || empty($email) || empty($password) || empty($nomor_unit) || empty($nomor_hp)) {
        $error = 'Semua field wajib diisi!';
    } else {
        // Check username sudah ada
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param('s', $username);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            $error = 'Username sudah terdaftar!';
        } else {
            // Tambah user dengan role 'resident' dan is_active = 1
            $hashed_password = hashPassword($password);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, nama_lengkap, is_active) VALUES (?, ?, ?, 'resident', ?, 1)");
            $stmt->bind_param('ssss', $username, $email, $hashed_password, $nama_lengkap);
            
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
                
                // Tambah data penghuni
                $stmt2 = $conn->prepare("INSERT INTO penghuni (user_id, nomor_unit, nomor_hp, blok, lantai, nama_kontak_darurat, nomor_kontak_darurat) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt2->bind_param('isssiss', $user_id, $nomor_unit, $nomor_hp, $blok, $lantai, $nama_kontak, $nomor_kontak);
                
                if ($stmt2->execute()) {
                    header('Location: ' . BASE_URL . '/modules/penghuni/list.php?msg=added');
                    exit();
                } else {
                    $error = 'Gagal menambahkan data penghuni!';
                }
            } else {
                $error = 'Gagal membuat akun pengguna!';
            }
        }
    }
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 style="font-weight: bold; color: #333; margin-bottom: 0.5rem;">
                    <i class="bi bi-person-plus-fill" style="font-size: 2rem; color: #007bff;"></i> Tambah Penghuni Baru
                </h1>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Daftarkan penghuni baru ke dalam sistem AureliaBox</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/modules/penghuni/list.php" class="btn btn-secondary" style="padding: 0.6rem 1.5rem;">
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
                        <h5 class="mb-0"><i class="bi bi-form-check"></i> Form Data Penghuni</h5>
                    </div>
                    <div class="card-body" style="padding: 2rem;">
                        <form method="POST" action="">
                            <!-- Informasi Akun -->
                            <div class="mb-4">
                                <h6 class="fw-bold" style="font-size: 1rem; color: #333; margin-bottom: 1.5rem;">
                                    <i class="bi bi-shield-check" style="color: #007bff;"></i> Informasi Akun
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

                            <!-- Informasi Penghuni -->
                            <div class="mb-4">
                                <h6 class="fw-bold" style="font-size: 1rem; color: #333; margin-bottom: 1.5rem;">
                                    <i class="bi bi-house-fill" style="color: #28a745;"></i> Informasi Penghuni
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nomor_unit" class="form-label fw-bold">Nomor Unit <span style="color: #dc3545;">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="nomor_unit" name="nomor_unit" placeholder="Contoh: 101, 202" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nomor_hp" class="form-label fw-bold">Nomor HP <span style="color: #dc3545;">*</span></label>
                                        <input type="tel" class="form-control form-control-lg" id="nomor_hp" name="nomor_hp" placeholder="08xxxxxxxxxx" required>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label for="blok" class="form-label fw-bold">Blok</label>
                                        <input type="text" class="form-control form-control-lg" id="blok" name="blok" placeholder="Contoh: A, B, C">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lantai" class="form-label fw-bold">Lantai</label>
                                        <input type="number" class="form-control form-control-lg" id="lantai" name="lantai" placeholder="Contoh: 1, 2, 3">
                                    </div>
                                </div>
                            </div>

                            <hr style="margin: 2rem 0;">

                            <!-- Kontak Darurat -->
                            <div class="mb-4">
                                <h6 class="fw-bold" style="font-size: 1rem; color: #333; margin-bottom: 1.5rem;">
                                    <i class="bi bi-telephone-fill" style="color: #ffc107;"></i> Kontak Darurat
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nama_kontak_darurat" class="form-label fw-bold">Nama Kontak</label>
                                        <input type="text" class="form-control form-control-lg" id="nama_kontak_darurat" name="nama_kontak_darurat" placeholder="Nama orang untuk kontak darurat">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nomor_kontak_darurat" class="form-label fw-bold">Nomor Kontak</label>
                                        <input type="tel" class="form-control form-control-lg" id="nomor_kontak_darurat" name="nomor_kontak_darurat" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2 d-md-flex gap-md-3 mt-5">
                                <button type="submit" class="btn btn-success btn-lg" style="font-weight: bold;">
                                    <i class="bi bi-check-circle"></i> Simpan Penghuni
                                </button>
                                <a href="<?php echo BASE_URL; ?>/modules/penghuni/list.php" class="btn btn-outline-secondary btn-lg" style="font-weight: bold;">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow" style="border-left: 4px solid #007bff; border-radius: 0.5rem; position: sticky; top: 100px;">
                    <div class="card-body" style="padding: 2rem;">
                        <h6 class="fw-bold mb-3" style="color: #333;">
                            <i class="bi bi-info-circle" style="color: #007bff;"></i> Informasi Penting
                        </h6>
                        <div style="font-size: 0.95rem; line-height: 1.8; color: #666;">
                            <p><strong>Username:</strong> Harus unik dan tidak dapat diubah</p>
                            <p><strong>Password:</strong> Simpan dengan aman untuk login pertama kali</p>
                            <p><strong>Email:</strong> Gunakan email yang masih aktif</p>
                            <p><strong>Nomor Unit:</strong> Wajib diisi untuk identifikasi penghuni</p>
                            <p><strong>Kontak Darurat:</strong> Akan digunakan jika ada kejadian penting</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
