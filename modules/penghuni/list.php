<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/pagination.php';

requireRole('admin');

$page_title = 'Kelola Penghuni';

// Get search parameter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query with search
$where_clause = '';
if (!empty($search)) {
    $search_escaped = $conn->real_escape_string($search);
    $where_clause = " WHERE u.nama_lengkap LIKE '%$search_escaped%' 
                      OR ph.nomor_unit LIKE '%$search_escaped%'
                      OR ph.nomor_hp LIKE '%$search_escaped%'
                      OR ph.blok LIKE '%$search_escaped%'";
}

// Get total count
$count_query = "SELECT COUNT(*) as total FROM penghuni ph JOIN users u ON ph.user_id = u.id" . $where_clause;
$count_result = $conn->query($count_query);
$count_row = $count_result->fetch_assoc();
$total_items = $count_row['total'];

// Pagination
$pagination = new Pagination($conn, 10);
$pagination->setTotalItems($total_items);
$offset = $pagination->getOffset();
$limit = $pagination->getLimit();

// Main query with pagination
$query = "SELECT ph.id, ph.nomor_unit, u.nama_lengkap, ph.nomor_hp, ph.blok, ph.lantai
         FROM penghuni ph
         JOIN users u ON ph.user_id = u.id" . 
         $where_clause . "
         ORDER BY ph.nomor_unit ASC
         LIMIT $offset, $limit";
$result = $conn->query($query);
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-people"></i> Kelola Penghuni</h1>
            <a href="<?php echo BASE_URL; ?>/modules/penghuni/add.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Penghuni
            </a>
        </div>

        <?php
        if (isset($_GET['msg']) && $_GET['msg'] === 'added') {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> Penghuni berhasil ditambahkan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>';
        } elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated') {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> Penghuni berhasil diperbarui!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>';
        } elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> Penghuni berhasil dihapus!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>';
        }
        ?>

        <!-- Maintenance Tools -->
        <div class="row mb-4">
            <div class="col-md-6">
                <a href="<?php echo BASE_URL; ?>/admin/residents_without_profile.php" class="card border-0 shadow-sm text-decoration-none text-dark h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 2rem; color: #ffc107; margin-bottom: 10px;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <h6 class="card-title">Resident Tanpa Profil</h6>
                        <p class="card-text text-muted small">Lihat & buat profil untuk resident yang belum punya profil penghuni</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="<?php echo BASE_URL; ?>/admin/fix_resident_roles.php" class="card border-0 shadow-sm text-decoration-none text-dark h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 2rem; color: #ffc107; margin-bottom: 10px;">
                            <i class="bi bi-wrench-adjustable-circle"></i>
                        </div>
                        <h6 class="card-title">Fix Resident Roles</h6>
                        <p class="card-text text-muted small">Perbaiki role & status untuk resident yang tidak sesuai</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Search Box -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control" name="search" placeholder="Cari nama, unit, no. HP, atau blok..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <?php if (!empty($search)): ?>
                    <a href="<?php echo BASE_URL; ?>/modules/penghuni/list.php" class="btn btn-secondary">
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
                <h5 class="mb-0"><i class="bi bi-list"></i> Daftar Penghuni</h5>
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
                                <th>No. Unit</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Blok</th>
                                <th>Lantai</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['nomor_unit']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($row['nomor_hp']); ?></td>
                                <td><?php echo htmlspecialchars($row['blok'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($row['lantai'] ?? '-'); ?></td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center;">
                                    <a href="<?php echo BASE_URL; ?>/modules/penghuni/read.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/modules/penghuni/edit.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>/modules/penghuni/delete.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-danger" title="Hapus"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus penghuni ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
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
                    $base_url = BASE_URL . '/modules/penghuni/list.php';
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
                        echo 'Tidak ada penghuni yang cocok dengan pencarian "' . htmlspecialchars($search) . '".';
                    } else {
                        echo 'Belum ada penghuni terdaftar.';
                    }
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
