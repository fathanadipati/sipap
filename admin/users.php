<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/pagination.php';

requireRole('admin');

$page_title = 'Kelola Staff';

// Get search parameter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query with search
$where_clause = " WHERE role IN ('admin', 'receptionist')";
if (!empty($search)) {
    $search_escaped = $conn->real_escape_string($search);
    $where_clause .= " AND (username LIKE '%$search_escaped%' 
                      OR email LIKE '%$search_escaped%'
                      OR nama_lengkap LIKE '%$search_escaped%'
                      OR role LIKE '%$search_escaped%')";
}

// Get total count
$count_query = "SELECT COUNT(*) as total FROM users" . $where_clause;
$count_result = $conn->query($count_query);
$count_row = $count_result->fetch_assoc();
$total_items = $count_row['total'];

// Pagination
$pagination = new Pagination($conn, 10);
$pagination->setTotalItems($total_items);
$offset = $pagination->getOffset();
$limit = $pagination->getLimit();

// Main query with pagination
$query = "SELECT id, username, email, role, nama_lengkap, is_active, created_at 
         FROM users" . 
         $where_clause . "
         ORDER BY created_at DESC
         LIMIT $offset, $limit";
$result = $conn->query($query);
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-person-circle"></i> Kelola Staff</h1>
            <a href="<?php echo BASE_URL; ?>/admin/users_add.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Staff
            </a>
        </div>

        <?php
        if (isset($_GET['msg'])) {
            $messages = [
                'added' => 'Staff berhasil ditambahkan!',
                'updated' => 'Staff berhasil diperbarui!',
                'deleted' => 'Staff berhasil dihapus!'
            ];
            if (isset($messages[$_GET['msg']])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> ' . $messages[$_GET['msg']] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>';
            }
        }
        ?>

        <!-- Search Box -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control" name="search" placeholder="Cari username, email, nama, atau role..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <?php if (!empty($search)): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/users.php" class="btn btn-secondary">
                        <i class="bi bi-x"></i> Reset
                    </a>
                    <?php endif; ?>
                </form>
                <?php if (!empty($search)): ?>
                <small class="text-muted mt-2 d-block">
                    Hasil pencarian untuk: <strong><?php echo htmlspecialchars($search); ?></strong>
                </small>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list"></i> Daftar Staff</h5>
                <small class="text-muted"><?php echo $pagination->getInfo(); ?></small>
            </div>
            <div class="card-body">
                <?php
                if ($result && $result->num_rows > 0):
                ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th style="text-align: center;">Role</th>
                                <th style="text-align: center;">Status</th>
                                <th>Dibuat</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td style="text-align: center;">
                                    <?php
                                    $role_badges = [
                                        'admin' => 'danger',
                                        'receptionist' => 'warning',
                                        'resident' => 'primary'
                                    ];
                                    $role_text = [
                                        'admin' => 'Admin',
                                        'receptionist' => 'Receptionist',
                                        'resident' => 'Resident'
                                    ];
                                    $role = $row['role'] ?? 'unknown';
                                    $badge_color = $role_badges[$role] ?? 'secondary';
                                    $role_display = $role_text[$role] ?? ucfirst($role);
                                    ?>
                                    <span class="badge bg-<?php echo $badge_color; ?>">
                                        <?php echo htmlspecialchars($role_display); ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge <?php echo $row['is_active'] ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo $row['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center;">
                                    <a href="<?php echo BASE_URL; ?>/admin/users_read.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/admin/users_edit.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($row['id'] !== $_SESSION['user_id']): ?>
                                    <a href="<?php echo BASE_URL; ?>/admin/users_delete.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-danger" title="Hapus"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus staff ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    <?php 
                    $base_url = BASE_URL . '/admin/users.php';
                    if (!empty($search)) {
                        $base_url .= '?search=' . urlencode($search);
                    }
                    echo $pagination->render($base_url);
                    ?>
                </div>
                <?php else: ?>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> 
                    <?php 
                    if (!empty($search)) {
                        echo 'Tidak ada staff yang cocok dengan pencarian "' . htmlspecialchars($search) . '".';
                    } else {
                        echo 'Belum ada staff terdaftar.';
                    }
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
