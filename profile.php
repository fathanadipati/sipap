<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';

requireLogin();

$page_title = 'Profil Saya';
$error = '';
$success = '';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Normalize old role names to new ones
if ($role === 'resepsionis') $role = 'receptionist';
if ($role === 'penghuni') $role = 'resident';

// Get data user
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get resident data jika role adalah resident
$resident = null;
if ($role === 'resident') {
    $resident_query = "SELECT * FROM penghuni WHERE user_id = ?";
    $resident_stmt = $conn->prepare($resident_query);
    $resident_stmt->bind_param('i', $user_id);
    $resident_stmt->execute();
    $resident_result = $resident_stmt->get_result();
    $resident = $resident_result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    if (empty($nama_lengkap) || empty($email) || empty($username)) {
        $error = 'Nama lengkap, email, dan username wajib diisi!';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter!';
    } else {
        // Check if username already exists (for other users)
        $check_query = "SELECT id FROM users WHERE username = ? AND id != ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param('si', $username, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = 'Username sudah digunakan oleh pengguna lain!';
        } else {
            // Update with password if provided
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $error = 'Password minimal 6 karakter!';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, email = ?, username = ?, password = ? WHERE id = ?");
                    $stmt->bind_param('ssssi', $nama_lengkap, $email, $username, $hashed_password, $user_id);
                    
                    if ($stmt->execute()) {
                        $_SESSION['nama_lengkap'] = $nama_lengkap;
                        $_SESSION['username'] = $username;
                        $success = 'Profil berhasil diperbarui!';
                        $user['nama_lengkap'] = $nama_lengkap;
                        $user['email'] = $email;
                        $user['username'] = $username;
                    } else {
                        $error = 'Gagal memperbarui profil!';
                    }
                }
            } else {
                // Update without password
                $stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, email = ?, username = ? WHERE id = ?");
                $stmt->bind_param('sssi', $nama_lengkap, $email, $username, $user_id);
                
                if ($stmt->execute()) {
                    $_SESSION['nama_lengkap'] = $nama_lengkap;
                    $_SESSION['username'] = $username;
                    $success = 'Profil berhasil diperbarui!';
                    $user['nama_lengkap'] = $nama_lengkap;
                    $user['email'] = $email;
                    $user['username'] = $username;
                } else {
                    $error = 'Gagal memperbarui profil!';
                }
            }
        }
    }
    
    // Update resident data jika role adalah resident
    if ($role === 'resident' && empty($error) && empty($password)) {
        $nomor_hp = isset($_POST['nomor_hp']) ? trim($_POST['nomor_hp']) : '';
        $nama_kontak_darurat = isset($_POST['nama_kontak_darurat']) ? trim($_POST['nama_kontak_darurat']) : '';
        $nomor_kontak_darurat = isset($_POST['nomor_kontak_darurat']) ? trim($_POST['nomor_kontak_darurat']) : '';
        
        if (!empty($nomor_hp) || !empty($nama_kontak_darurat) || !empty($nomor_kontak_darurat)) {
            $update_resident = "UPDATE penghuni SET nomor_hp = ?, nama_kontak_darurat = ?, nomor_kontak_darurat = ? WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_resident);
            $update_stmt->bind_param('sssi', $nomor_hp, $nama_kontak_darurat, $nomor_kontak_darurat, $user_id);
            $update_stmt->execute();
            
            if ($update_stmt->execute()) {
                $resident['nomor_hp'] = $nomor_hp;
                $resident['nama_kontak_darurat'] = $nama_kontak_darurat;
                $resident['nomor_kontak_darurat'] = $nomor_kontak_darurat;
            }
        }
    }
}
?>
<?php require_once __DIR__ . '/includes/header.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="bi bi-person-circle"></i> Profil Saya</h1>
                <p class="text-muted">Kelola informasi akun pribadi Anda</p>
            </div>
            <a href="<?php echo $role === 'admin' ? BASE_URL . '/admin/' : ($role === 'resident' ? BASE_URL . '/dashboard.php' : BASE_URL . '/dashboard.php'); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo $success; ?>
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
                        <h4><?php echo htmlspecialchars($user['nama_lengkap'] ?? 'N/A'); ?></h4>
                        <p class="text-muted mb-3">@<?php echo htmlspecialchars($user['username'] ?? 'unknown'); ?></p>
                        
                        <!-- Role Badge -->
                        <div class="mb-3">
                            <?php
                            $role_color = $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'receptionist' ? 'warning' : 'primary');
                            $role_text = [
                                'admin' => 'Administrator',
                                'receptionist' => 'Receptionist',
                                'resident' => 'Resident',
                                'penghuni' => 'Resident',
                                'resepsionis' => 'Receptionist'
                            ];
                            $display_role = $role_text[$user['role']] ?? ucfirst($user['role']);
                            ?>
                            <span class="badge bg-<?php echo $role_color; ?> fs-6">
                                <i class="bi bi-shield-check"></i> <?php echo $display_role; ?>
                            </span>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <?php if ($user['is_active'] ?? false): ?>
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

                <!-- Info Card -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Informasi Akun</h6>
                        <div class="mb-2">
                            <small class="text-muted">Bergabung</small>
                            <div><small><?php echo $user['created_at'] ? date('d M Y', strtotime($user['created_at'])) : 'N/A'; ?></small></div>
                        </div>
                        <hr class="my-2">
                        <div class="mb-0">
                            <small class="text-muted">Diperbarui</small>
                            <div><small><?php echo $user['updated_at'] ? date('d M Y H:i', strtotime($user['updated_at'])) : 'N/A'; ?></small></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Profil</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <!-- Nama Lengkap -->
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">
                                    <strong>Nama Lengkap</strong> <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                       value="<?php echo htmlspecialchars($user['nama_lengkap'] ?? ''); ?>" required>
                            </div>

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <strong>Username</strong> <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="username" name="username"
                                       value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required 
                                       minlength="3" placeholder="Minimal 3 karakter">
                                <small class="text-muted">Username untuk login (minimal 3 karakter, harus unik)</small>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <strong>Email</strong> <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                                <small class="text-muted">Email harus unik dan valid</small>
                            </div>

                            <!-- Password Baru -->
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <strong>Password Baru</strong>
                                </label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       minlength="6" placeholder="Kosongkan jika tidak ingin mengubah password">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> 
                                    Kosongkan jika tidak ingin mengubah password. Password minimal 6 karakter.
                                </small>
                            </div>

                            <?php if ($role === 'resident' && $resident): ?>
                            <hr>
                            <h6 class="mb-3">Resident Data</h6>

                            <!-- Nomor Unit (Read-Only) -->
                            <div class="mb-3">
                                <label for="nomor_unit" class="form-label"><strong>Unit Number</strong></label>
                                <input type="text" class="form-control" id="nomor_unit" 
                                       value="<?php echo htmlspecialchars($resident['nomor_unit']); ?>" disabled>
                                <small class="text-muted"><i class="bi bi-info-circle"></i> Unit number cannot be changed</small>
                            </div>

                            <!-- Blok (Read-Only) -->
                            <?php if ($resident['blok']): ?>
                            <div class="mb-3">
                                <label for="blok" class="form-label"><strong>Block</strong></label>
                                <input type="text" class="form-control" id="blok" 
                                       value="<?php echo htmlspecialchars($resident['blok']); ?>" disabled>
                            </div>
                            <?php endif; ?>

                            <!-- Lantai (Read-Only) -->
                            <?php if ($resident['lantai']): ?>
                            <div class="mb-3">
                                <label for="lantai" class="form-label"><strong>Floor</strong></label>
                                <input type="text" class="form-control" id="lantai" 
                                       value="<?php echo htmlspecialchars($resident['lantai']); ?>" disabled>
                            </div>
                            <?php endif; ?>

                            <!-- Nomor HP -->
                            <div class="mb-3">
                                <label for="nomor_hp" class="form-label"><strong>Phone Number</strong></label>
                                <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp" 
                                       value="<?php echo htmlspecialchars($resident['nomor_hp'] ?? ''); ?>" 
                                       placeholder="Example: 08123456789">
                                <small class="text-muted">Active phone number for package delivery</small>
                            </div>

                            <!-- Nama Kontak Darurat -->
                            <div class="mb-3">
                                <label for="nama_kontak_darurat" class="form-label"><strong>Emergency Contact Name</strong></label>
                                <input type="text" class="form-control" id="nama_kontak_darurat" name="nama_kontak_darurat" 
                                       value="<?php echo htmlspecialchars($resident['nama_kontak_darurat'] ?? ''); ?>" 
                                       placeholder="Name of person to contact">
                                <small class="text-muted">Emergency contact if needed</small>
                            </div>

                            <!-- Nomor Kontak Darurat -->
                            <div class="mb-3">
                                <label for="nomor_kontak_darurat" class="form-label"><strong>Emergency Contact Number</strong></label>
                                <input type="tel" class="form-control" id="nomor_kontak_darurat" name="nomor_kontak_darurat" 
                                       value="<?php echo htmlspecialchars($resident['nomor_kontak_darurat'] ?? ''); ?>" 
                                       placeholder="Emergency contact phone number">
                                <small class="text-muted">Phone number for emergency contact</small>
                            </div>
                            <?php endif; ?>

                            <hr>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                                </button>
                                <a href="<?php echo $role === 'admin' ? BASE_URL . '/admin/' : BASE_URL . '/dashboard.php'; ?>" class="btn btn-outline-secondary">
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
