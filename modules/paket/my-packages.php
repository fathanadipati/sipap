<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/pagination.php';

requireRole(['resident']);

$page_title = 'Paket Saya';

// Get resident_id
$query = "SELECT id FROM penghuni WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$resident = $result->fetch_assoc();
$resident_id = $resident['id'] ?? null;

// Get filters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query conditions
$where_clauses = ["p.penghuni_id = $resident_id"];

if ($status_filter) {
    $status_escaped = $conn->real_escape_string($status_filter);
    $where_clauses[] = "p.status = '$status_escaped'";
}

if (!empty($search)) {
    $search_escaped = $conn->real_escape_string($search);
    $where_clauses[] = "(p.nomor_paket LIKE '%$search_escaped%' 
                        OR p.nama_pengirim LIKE '%$search_escaped%'
                        OR p.nama_ekspedisi LIKE '%$search_escaped%')";
}

$where_clause = implode(' AND ', $where_clauses);

// Get total count
$count_query = "SELECT COUNT(*) as total FROM paket p WHERE $where_clause";
$count_result = $conn->query($count_query);
$count_row = $count_result->fetch_assoc();
$total_items = $count_row['total'];

// Pagination
$pagination = new Pagination($conn, 10);
$pagination->setTotalItems($total_items);
$offset = $pagination->getOffset();
$limit = $pagination->getLimit();

// Get paket data
$query = "SELECT p.id, p.nomor_paket, p.nama_pengirim, p.nama_ekspedisi, p.status, 
                 p.nomor_loker, p.tanggal_terima, p.tanggal_diambil, p.deskripsi
         FROM paket p
         WHERE $where_clause
         ORDER BY p.tanggal_terima DESC
         LIMIT $offset, $limit";
$result = $conn->query($query);
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="mb-0">
                    <i class="bi bi-inbox"></i> Paket Saya
                </h1>
                <p class="text-muted">Total paket: <strong><?php echo $total_items; ?></strong></p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="<?php echo BASE_URL; ?>/dashboard.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Cari Paket</label>
                        <input type="text" name="search" class="form-control" placeholder="No. paket, pengirim, ekspedisi..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="diterima" <?php echo $status_filter === 'diterima' ? 'selected' : ''; ?>>Diterima</option>
                            <option value="disimpan" <?php echo $status_filter === 'disimpan' ? 'selected' : ''; ?>>Tersimpan</option>
                            <option value="diambil" <?php echo $status_filter === 'diambil' ? 'selected' : ''; ?>>Diambil</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Cari
                            </button>
                            <a href="<?php echo BASE_URL; ?>/modules/paket/my-packages.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Paket Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-list-task"></i> Daftar Paket Saya</h5>
            </div>
            <div class="card-body">
                <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>No. Paket</th>
                                <th>Pengirim</th>
                                <th>Ekspedisi</th>
                                <th style="text-align: center;">Penyimpanan</th>
                                <th style="text-align: center;">Status</th>
                                <th>Diterima</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><small><?php echo substr($row['nomor_paket'], 0, 12); ?>...</small></strong></td>
                                <td><small><?php echo htmlspecialchars($row['nama_pengirim']); ?></small></td>
                                <td><small><?php echo htmlspecialchars($row['nama_ekspedisi']); ?></small></td>
                                <td style="text-align: center;">
                                    <?php 
                                    if ($row['nomor_loker']) {
                                        if ($row['nomor_loker'] === 'WAREHOUSE') {
                                            echo '<span class="badge" style="background-color: #0066cc;"><i class="bi bi-building"></i> ' . htmlspecialchars($row['nomor_loker']) . '</span>';
                                        } else {
                                            echo '<span class="badge bg-info">' . htmlspecialchars($row['nomor_loker']) . '</span>';
                                        }
                                    } else {
                                        echo '<span class="badge bg-secondary">-</span>';
                                    }
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php
                                    $status_badges = [
                                        'diterima' => '<span class="badge bg-warning">Diterima</span>',
                                        'disimpan' => '<span class="badge bg-info">Tersimpan</span>',
                                        'diambil' => '<span class="badge bg-success">Diambil</span>'
                                    ];
                                    echo $status_badges[$row['status']] ?? '<span class="badge bg-secondary">Unknown</span>';
                                    ?>
                                </td>
                                <td><small><?php echo date('d M Y H:i', strtotime($row['tanggal_terima'])); ?></small></td>
                                <td style="text-align: center;">
                                    <div style="display: flex; justify-content: center;">
                                    <a href="<?php echo BASE_URL; ?>/modules/paket/view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        <?php echo $pagination->getInfo(); ?>
                    </small>
                    <nav>
                        <ul class="pagination mb-0">
                            <?php echo $pagination->render('my-packages.php', [
                                'status' => $status_filter,
                                'search' => $search
                            ]); ?>
                        </ul>
                    </nav>
                </div>
                <?php else: ?>
                <div class="alert alert-info m-0">
                    <i class="bi bi-info-circle"></i> Anda belum memiliki paket apapun.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
