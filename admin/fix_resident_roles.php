<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

// Hanya admin yang bisa akses
requireRole('admin');

$page_title = 'Fix Resident Roles';

// Counter untuk tracking
$fixed_count = 0;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fix_roles'])) {
    // Update semua users yang connected to penghuni table tapi tidak punya role 'resident'
    $fix_query = "UPDATE users u 
                  INNER JOIN penghuni p ON u.id = p.user_id 
                  SET u.role = 'resident', u.is_active = 1 
                  WHERE u.role != 'resident' OR u.role IS NULL OR u.is_active = 0";
    
    if ($conn->query($fix_query)) {
        $fixed_count = $conn->affected_rows;
        $message = "✅ Berhasil memperbaiki $fixed_count resident(s) dengan role yang benar!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}

// Get daftar residents yang tidak punya role 'resident'
$check_query = "SELECT u.id, u.username, u.nama_lengkap, u.role, u.is_active, p.nomor_unit
                FROM users u
                INNER JOIN penghuni p ON u.id = p.user_id
                WHERE u.role != 'resident' OR u.role IS NULL OR u.is_active = 0
                ORDER BY u.created_at DESC";

$result = $conn->query($check_query);
$problematic_residents = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $problematic_residents[] = $row;
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
                    <i class="bi bi-wrench-adjustable-circle" style="font-size: 2rem; color: #ffc107;"></i> Fix Resident Roles
                </h1>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Perbaiki role dan status aktif untuk resident yang tidak sesuai</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/dashboard.php" class="btn btn-secondary" style="padding: 0.6rem 1.5rem;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo strpos($message, 'Berhasil') !== false ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert" style="border-left: 4px solid #28a745;">
                <i class="bi bi-<?php echo strpos($message, 'Berhasil') !== false ? 'check-circle' : 'exclamation-circle'; ?>"></i> 
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (count($problematic_residents) > 0): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Ditemukan <?php echo count($problematic_residents); ?> Resident dengan Role Tidak Sesuai</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Unit</th>
                                    <th>Role Saat Ini</th>
                                    <th>Status Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($problematic_residents as $resident): ?>
                                <tr>
                                    <td><code><?php echo $resident['id']; ?></code></td>
                                    <td><strong><?php echo htmlspecialchars($resident['username']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($resident['nama_lengkap']); ?></td>
                                    <td><?php echo htmlspecialchars($resident['nomor_unit']); ?></td>
                                    <td>
                                        <?php 
                                        if ($resident['role'] == 'resident') {
                                            echo '<span class="badge bg-success">Resident</span>';
                                        } elseif ($resident['role'] == 'penghuni') {
                                            echo '<span class="badge bg-warning">Penghuni (Lama)</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">' . htmlspecialchars($resident['role'] ?? 'NULL') . '</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($resident['is_active']) {
                                            echo '<span class="badge bg-success">Aktif</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">Nonaktif</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i> Klik tombol di bawah untuk memperbaiki semua resident yang terdaftar di tabel ini dengan role 'resident' dan status 'Aktif'.
                    </div>

                    <form method="POST" action="">
                        <button type="submit" name="fix_roles" class="btn btn-warning btn-lg" style="font-weight: bold;">
                            <i class="bi bi-wrench"></i> Perbaiki Semua Roles (<?php echo count($problematic_residents); ?> resident)
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-check-circle"></i> Semua Resident Sudah OK</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success m-0">
                        <i class="bi bi-check-circle-fill"></i> Semua resident sudah memiliki role 'resident' dan status 'Aktif'. Tidak ada yang perlu diperbaiki!
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi</h5>
            </div>
            <div class="card-body">
                <h6>Apa yang dilakukan halaman ini?</h6>
                <ul>
                    <li>Mencari semua resident yang tidak memiliki role 'resident' atau status 'Aktif'</li>
                    <li>Menampilkan daftar resident yang bermasalah</li>
                    <li>Memungkinkan admin untuk memperbaiki semua sekaligus dengan satu klik</li>
                </ul>

                <h6 class="mt-3">Kapan perlu digunakan?</h6>
                <ul>
                    <li>Setelah membuat resident dengan cara manual/impor</li>
                    <li>Jika ada resident yang tidak bisa login atau dashboard kosong</li>
                    <li>Sebagai bagian dari maintenance sistem</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
