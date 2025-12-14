<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/pagination.php';

requireRole(['admin', 'receptionist']);

$page_title = 'Kelola Paket';

// Get filters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';

// Build query conditions
$where_clauses = [];

if ($status_filter) {
    $status_escaped = $conn->real_escape_string($status_filter);
    $where_clauses[] = "p.status = '$status_escaped'";
}

if ($date_filter === 'today') {
    $where_clauses[] = "DATE(p.tanggal_diambil) = CURDATE()";
}

if (!empty($search)) {
    $search_escaped = $conn->real_escape_string($search);
    $where_clauses[] = "(p.nomor_paket LIKE '%$search_escaped%' 
                        OR ph.nomor_unit LIKE '%$search_escaped%'
                        OR p.nama_pengirim LIKE '%$search_escaped%'
                        OR p.nama_ekspedisi LIKE '%$search_escaped%')";
}

$where_clause = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

// Get total count
$count_query = "SELECT COUNT(*) as total FROM paket p
                JOIN penghuni ph ON p.penghuni_id = ph.id
                JOIN users u ON p.resepsionis_id = u.id
                $where_clause";
$count_result = $conn->query($count_query);
$count_row = $count_result->fetch_assoc();
$total_items = $count_row['total'];

// Pagination
$pagination = new Pagination($conn, 10);
$pagination->setTotalItems($total_items);
$offset = $pagination->getOffset();
$limit = $pagination->getLimit();

// Main query with pagination
$query = "SELECT p.id, p.nomor_paket, ph.nomor_unit, p.nama_pengirim, p.nama_ekspedisi, 
                 p.jenis_paket, p.nomor_loker, p.status, p.tanggal_terima, p.tanggal_diambil,
                 u.nama_lengkap as nama_resepsionis
         FROM paket p
         JOIN penghuni ph ON p.penghuni_id = ph.id
         JOIN users u ON p.resepsionis_id = u.id
         $where_clause
         ORDER BY p.tanggal_terima DESC
         LIMIT $offset, $limit";
$result = $conn->query($query);
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                <i class="bi bi-box2"></i> 
                <?php
                if (!empty($date_filter) && $date_filter === 'today' && $status_filter === 'diambil') {
                    echo 'Paket Diambil Hari Ini';
                } elseif ($status_filter === 'disimpan') {
                    echo 'Paket Tersimpan';
                } elseif ($status_filter === 'diambil') {
                    echo 'Paket yang Telah Diambil';
                } else {
                    echo 'Semua Paket';
                }
                ?>
            </h1>
        </div>

        <?php
        if (isset($_GET['msg']) && $_GET['msg'] === 'added') {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> Paket berhasil dicatat!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>';
        } elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated') {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> Paket berhasil diperbarui!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>';
        } elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> Paket berhasil dihapus!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>';
        }
        ?>

        <!-- Search Box -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control" name="search" placeholder="Cari No. Paket, Unit, Pengirim, Ekspedisi..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <?php if ($status_filter): ?>
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($status_filter); ?>">
                    <?php endif; ?>
                    <?php if ($date_filter): ?>
                    <input type="hidden" name="date_filter" value="<?php echo htmlspecialchars($date_filter); ?>">
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <?php if (!empty($search) || !empty($status_filter) || !empty($date_filter)): ?>
                    <a href="<?php echo BASE_URL; ?>/modules/paket/list.php" class="btn btn-secondary">
                        <i class="bi bi-x"></i> Reset
                    </a>
                    <?php endif; ?>
                </form>
                <?php if (!empty($search)): ?>
                <small class="text-muted mt-2 d-block">
                    Hasil pencarian untuk: <strong><?php echo htmlspecialchars($search); ?></strong> 
                    (mencari di semua paket, bukan hanya halaman ini)
                </small>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list"></i> Daftar Paket</h5>
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
                                <th>No. Paket</th>
                                <th>Unit</th>
                                <th>Pengirim</th>
                                <th>Ekspedisi</th>
                                <th style="text-align: center;">Penyimpanan</th>
                                <th style="text-align: center;">Status</th>
                                <th>Tgl Terima</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['nomor_paket']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['nomor_unit']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_pengirim']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_ekspedisi']); ?></td>
                                <td style="text-align: center;">
                                    <?php
                                    if ($row['nomor_loker'] === 'WAREHOUSE') {
                                        echo '<span class="badge" style="background-color: #0066cc;"><i class="bi bi-building"></i> ' . htmlspecialchars($row['nomor_loker']) . '</span>';
                                    } else {
                                        echo '<span class="badge bg-info">' . htmlspecialchars($row['nomor_loker']) . '</span>';
                                    }
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php
                                    $status_classes = [
                                        'disimpan' => 'info',
                                        'diambil' => 'success'
                                    ];
                                    $status_text = [
                                        'disimpan' => 'Disimpan',
                                        'diambil' => 'Diambil'
                                    ];
                                    ?>
                                    <span class="badge bg-<?php echo $status_classes[$row['status']]; ?>">
                                        <?php echo $status_text[$row['status']]; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y H:i', strtotime($row['tanggal_terima'])); ?></td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 5px; flex-wrap: wrap; justify-content: center;">
                                        <a href="<?php echo BASE_URL; ?>/modules/paket/view.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-info" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <?php if ($row['status'] === 'disimpan'): ?>
                                        <button type="button" class="btn btn-sm btn-success quick-mark-btn" 
                                                data-paket-id="<?php echo $row['id']; ?>"
                                                title="Tandai sebagai Diambil" style="cursor: pointer;">
                                            <i class="bi bi-check2-circle"></i> Diambil
                                        </button>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-success" disabled title="Sudah Diambil">
                                            <i class="bi bi-check2-circle"></i> Diambil
                                        </button>
                                        <?php endif; ?>
                                        
                                        <?php if (hasRole('admin') || hasRole('receptionist')): ?>
                                        <a href="<?php echo BASE_URL; ?>/modules/paket/edit.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if (hasRole('admin')): ?>
                                        <a href="<?php echo BASE_URL; ?>/modules/paket/delete.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-danger" title="Hapus"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus paket ini?')">
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
                    $base_url = BASE_URL . '/modules/paket/list.php';
                    $params = [];
                    if (!empty($search)) $params[] = 'search=' . urlencode($search);
                    if (!empty($status_filter)) $params[] = 'status=' . urlencode($status_filter);
                    if (!empty($date_filter)) $params[] = 'date_filter=' . urlencode($date_filter);
                    if (!empty($params)) $base_url .= '?' . implode('&', $params);
                    echo $pagination->render($base_url);
                    ?>
                </div>
                <?php else: ?>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> 
                    <?php 
                    if (!empty($search)) {
                        echo 'Tidak ada paket yang cocok dengan pencarian "' . htmlspecialchars($search) . '".';
                    } elseif (!empty($status_filter) || !empty($date_filter)) {
                        echo 'Tidak ada paket dengan filter yang dipilih.';
                    } else {
                        echo 'Belum ada paket terdaftar.';
                    }
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Handle Quick Mark Paket as Diambil
document.querySelectorAll('.quick-mark-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const paketId = this.getAttribute('data-paket-id');
        const btn = this;
        
        // Show confirmation
        if (!confirm('Tandai paket ini sebagai sudah diambil?')) {
            return;
        }
        
        // Disable button and show loading
        btn.disabled = true;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
        
        // Send AJAX request
        fetch('<?php echo BASE_URL; ?>/modules/paket/quick_mark.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + paketId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="bi bi-check-circle"></i> ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                // Insert alert at top of card body
                const cardBody = btn.closest('.card-body');
                cardBody.insertBefore(alertDiv, cardBody.firstChild);
                
                // Update button and row
                setTimeout(() => {
                    const row = btn.closest('tr');
                    const statusBadge = row.querySelector('[class*="bg-info"]');
                    
                    // Update status badge
                    if (statusBadge) {
                        statusBadge.className = 'badge bg-success';
                        statusBadge.textContent = 'Diambil';
                    }
                    
                    // Replace button with disabled version
                    btn.className = 'btn btn-sm btn-success';
                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-check2-circle"></i> Diambil';
                    btn.title = 'Sudah Diambil';
                    btn.style.cursor = 'default';
                }, 500);
            } else {
                alert('Error: ' + data.message);
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan');
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

