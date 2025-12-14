<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireRole('admin');

$page_title = 'Edit Pengguna';
$error = '';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    header('Location: ' . BASE_URL . '/admin/users.php');
    exit();
}

// Get data user
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ' . BASE_URL . '/admin/users.php');
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($nama_lengkap) || empty($email) || empty($username)) {
        $error = 'Nama lengkap, email, dan username wajib diisi!';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter!';
    } else {
        // Check if username already exists (for other users)
        $check_query = "SELECT id FROM users WHERE username = ? AND id != ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param('si', $username, $id);
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
                    $stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, email = ?, username = ?, password = ?, is_active = ? WHERE id = ?");
                    $stmt->bind_param('ssssii', $nama_lengkap, $email, $username, $hashed_password, $is_active, $id);
                    
                    if ($stmt->execute()) {
                        header('Location: ' . BASE_URL . '/admin/users.php?msg=updated');
                        exit();
                    } else {
                        $error = 'Gagal memperbarui pengguna!';
                    }
                }
            } else {
                // Update without password
                $stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, email = ?, username = ?, is_active = ? WHERE id = ?");
                $stmt->bind_param('sssii', $nama_lengkap, $email, $username, $is_active, $id);
                
                if ($stmt->execute()) {
                    header('Location: ' . BASE_URL . '/admin/users.php?msg=updated');
                    exit();
                } else {
                    $error = 'Gagal memperbarui pengguna!';
                }
            }
        }
    }
}
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="bi bi-pencil-square"></i> Edit Pengguna</h1>
                <p class="text-muted">Perbarui informasi data pengguna</p>
            </div>
            <div>
                <a href="users_read.php?id=<?php echo $user['id']; ?>" class="btn btn-info">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>
                <a href="users.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

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
                        <h4><?php echo htmlspecialchars($user['nama_lengkap']); ?></h4>
                        <p class="text-muted mb-3">@<?php echo htmlspecialchars($user['username']); ?></p>
                        
                        <!-- Role Badge -->
                        <div class="mb-3">
                            <?php
                            $role_color = $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'receptionist' ? 'warning' : 'primary');
                            $role_text = [
                                'admin' => 'Administrator',
                                'receptionist' => 'Receptionist',
                                'resident' => 'Resident'
                            ];
                            ?>
                            <span class="badge bg-<?php echo $role_color; ?> fs-6">
                                <i class="bi bi-shield-check"></i> <?php echo $role_text[$user['role']]; ?>
                            </span>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <?php if ($user['is_active']): ?>
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
                        <h6 class="card-title mb-3">Informasi Umum</h6>
                        <div class="mb-2">
                            <small class="text-muted">Username</small>
                            <div><code><?php echo htmlspecialchars($user['username']); ?></code></div>
                        </div>
                        <hr class="my-2">
                        <div class="mb-2">
                            <small class="text-muted">Email</small>
                            <div><small><?php echo htmlspecialchars($user['email']); ?></small></div>
                        </div>
                        <hr class="my-2">
                        <div class="mb-0">
                            <small class="text-muted">Role</small>
                            <div>
                                <span class="badge bg-<?php echo $role_color; ?>">
                                    <?php echo $role_text[$user['role']]; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-form-check"></i> Form Edit Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <!-- Nama Lengkap -->
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">
                                    <strong>Nama Lengkap</strong> <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                       value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
                                <small class="text-muted">Nama lengkap pengguna sistem</small>
                            </div>

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <strong>Username</strong> <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="username" name="username"
                                       value="<?php echo htmlspecialchars($user['username']); ?>" required 
                                       minlength="3" placeholder="Username minimal 3 karakter">
                                <small class="text-muted">Username untuk login (minimal 3 karakter, harus unik)</small>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <strong>Email</strong> <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                <small class="text-muted">Email harus unik dan valid</small>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
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

                            <!-- Role (Read-Only) -->
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <strong>Role</strong>
                                </label>
                                <input type="text" class="form-control" id="role" 
                                       value="<?php echo $role_text[$user['role']]; ?>" disabled>
                                <small class="text-muted"><i class="bi bi-info-circle"></i> Role tidak dapat diubah</small>
                            </div>

                            <!-- Status Aktif -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           <?php echo $user['is_active'] ? 'checked' : ''; ?> style="width: 50px; height: 25px; cursor: pointer;">
                                    <label class="form-check-label" for="is_active">
                                        <strong>Akun Aktif</strong>
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle"></i> Nonaktifkan akun untuk melarang akses
                                </small>
                            </div>

                            <hr>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                                </button>
                                <a href="users_read.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                                <a href="users.php" class="btn btn-outline-secondary">
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
