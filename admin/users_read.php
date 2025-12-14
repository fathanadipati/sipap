<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireRole('admin');

// Get user ID from URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$user_id) {
    header('Location: users.php');
    exit;
}

// Get user data
$query = "SELECT id, username, email, password, role, nama_lengkap, is_active, created_at, updated_at 
         FROM users 
         WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: users.php');
    exit;
}

// Define role mapping
$role_text = [
    'admin' => 'Administrator',
    'receptionist' => 'Receptionist',
    'resident' => 'Resident'
];

$page_title = 'Detail Pengguna - ' . htmlspecialchars($user['nama_lengkap']);
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="bi bi-person-vcard"></i> Detail Pengguna</h1>
                <p class="text-muted">Informasi lengkap data pengguna</p>
            </div>
            <div>
                <a href="users_edit.php?id=<?php echo $user['id']; ?>" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="users.php" class="btn btn-secondary">
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
                        <h4><?php echo htmlspecialchars($user['nama_lengkap']); ?></h4>
                        <p class="text-muted mb-3">@<?php echo htmlspecialchars($user['username']); ?></p>
                        
                        <!-- Role Badge -->
                        <div class="mb-3">
                            <?php
                            $role_color = $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'receptionist' ? 'warning' : 'primary');
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

                <!-- Quick Actions -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Aksi Cepat</h6>
                        <div class="d-grid gap-2">
                            <a href="users_edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit Data
                            </a>
                            <a href="users.php" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-list"></i> Lihat Semua User
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
                                <code><?php echo htmlspecialchars($user['username']); ?></code>
                            </div>
                        </div>
                        <hr>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Email</strong>
                            </div>
                            <div class="col-sm-8">
                                <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </a>
                            </div>
                        </div>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Nama Lengkap</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo htmlspecialchars($user['nama_lengkap']); ?>
                            </div>
                        </div>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Role</strong>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge bg-<?php echo $role_color; ?>">
                                    <?php echo $role_text[$user['role']]; ?>
                                </span>
                            </div>
                        </div>
                        <hr>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Status</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php if ($user['is_active']): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Nonaktif</span>
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
                                    $created = new DateTime($user['created_at']);
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
                                    $updated = new DateTime($user['updated_at']);
                                    echo $updated->format('d M Y H:i:s');
                                    ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Info -->
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Keamanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Password</strong> disimpan dengan enkripsi bcrypt dan tidak dapat dilihat. 
                            Untuk reset password, gunakan fitur Edit.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
