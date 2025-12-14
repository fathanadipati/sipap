<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('resident');

$page_title = 'Notifikasi Paket';

// Get penghuni_id
$query = "SELECT id FROM penghuni WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$penghuni = $result->fetch_assoc();
$penghuni_id = $penghuni['id'];
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-bell"></i> Notifikasi Paket</h1>
            <button type="button" class="btn btn-sm btn-warning" id="clearAllBtn"
                   onclick="clearAllNotifications()">
                <i class="bi bi-check-all"></i> Tandai Semua Dibaca
            </button>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-list"></i> Daftar Notifikasi</h5>
            </div>
            <div class="card-body">
                <?php
                $query = "SELECT n.id, n.paket_id, n.pesan, n.is_read, n.created_at, p.nomor_paket
                         FROM notifikasi n
                         JOIN paket p ON n.paket_id = p.id
                         WHERE n.penghuni_id = ?
                         ORDER BY n.created_at DESC";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $penghuni_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0):
                ?>
                <div class="list-group">
                    <?php while ($notif = $result->fetch_assoc()): ?>
                    <a href="<?php echo BASE_URL; ?>/modules/paket/view.php?id=<?php echo $notif['paket_id']; ?>" 
                       class="list-group-item list-group-item-action <?php echo $notif['is_read'] ? '' : 'active'; ?>" 
                       onclick="markAsRead(<?php echo $notif['id']; ?>)">
                        <div class="d-flex w-100 justify-content-between">
                            <div>
                                <h6 class="mb-1">
                                    <?php echo htmlspecialchars($notif['pesan']); ?>
                                </h6>
                                <p class="mb-1 small">
                                    <strong>Paket:</strong> <?php echo $notif['nomor_paket']; ?>
                                </p>
                                <small><?php echo date('d M Y H:i', strtotime($notif['created_at'])); ?></small>
                            </div>
                            <?php if (!$notif['is_read']): ?>
                            <span class="badge bg-primary rounded-pill align-self-center">Baru</span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <!-- No notifications - show package history instead -->
                <div class="alert alert-info mb-3">
                    <i class="bi bi-info-circle"></i> Anda tidak memiliki notifikasi baru saat ini. Berikut adalah riwayat paket Anda:
                </div>
                
                <?php
                // Get all packages for this penghuni
                $paket_query = "SELECT id, nomor_paket, nama_pengirim, nama_ekspedisi, status, nomor_loker, tanggal_terima, tanggal_diambil
                               FROM paket
                               WHERE penghuni_id = ?
                               ORDER BY tanggal_terima DESC";
                $paket_stmt = $conn->prepare($paket_query);
                $paket_stmt->bind_param('i', $penghuni_id);
                $paket_stmt->execute();
                $paket_result = $paket_stmt->get_result();
                
                if ($paket_result->num_rows > 0):
                ?>
                <div class="list-group">
                    <?php while ($paket = $paket_result->fetch_assoc()): ?>
                    <a href="<?php echo BASE_URL; ?>/modules/paket/view.php?id=<?php echo $paket['id']; ?>" 
                       class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div style="flex: 1;">
                                <h6 class="mb-1">
                                    <strong><?php echo htmlspecialchars($paket['nomor_paket']); ?></strong>
                                </h6>
                                <p class="mb-2 small">
                                    <strong>Pengirim:</strong> <?php echo htmlspecialchars($paket['nama_pengirim']); ?> 
                                    <i class="bi bi-dot"></i>
                                    <strong>Ekspedisi:</strong> <?php echo htmlspecialchars($paket['nama_ekspedisi']); ?>
                                </p>
                                <p class="mb-2 small">
                                    <strong>Penyimpanan:</strong> 
                                    <?php
                                    if ($paket['nomor_loker'] === 'WAREHOUSE') {
                                        echo '<span class="badge" style="background-color: #0066cc;"><i class="bi bi-building"></i> ' . htmlspecialchars($paket['nomor_loker']) . '</span>';
                                    } else {
                                        echo '<span class="badge bg-info">' . htmlspecialchars($paket['nomor_loker']) . '</span>';
                                    }
                                    ?>
                                </p>
                                <small class="text-muted">
                                    Diterima: <?php echo date('d M Y H:i', strtotime($paket['tanggal_terima'])); ?>
                                    <?php if ($paket['tanggal_diambil']): ?>
                                        <i class="bi bi-dot"></i> Diambil: <?php echo date('d M Y H:i', strtotime($paket['tanggal_diambil'])); ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                            <div class="ms-3">
                                <?php
                                $status_classes = [
                                    'disimpan' => 'info',
                                    'diambil' => 'success'
                                ];
                                $status_text = [
                                    'disimpan' => 'Tersimpan',
                                    'diambil' => 'Diambil'
                                ];
                                ?>
                                <span class="badge bg-<?php echo $status_classes[$paket['status']] ?? 'secondary'; ?>">
                                    <?php echo $status_text[$paket['status']] ?? 'Unknown'; ?>
                                </span>
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <div style="font-size: 3rem; color: #6c757d; margin-bottom: 20px;">
                        <i class="bi bi-box2"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Paket</h5>
                    <p class="text-muted mb-4">
                        Anda belum memiliki paket apapun. Paket akan muncul di halaman ini ketika resepsionis mencatat paket untuk Anda.
                    </p>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(id) {
    fetch('<?php echo BASE_URL; ?>/modules/notifikasi/mark_read.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: id})
    });
}

function clearAllNotifications() {
    if (!confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
        return;
    }
    
    const btn = document.getElementById('clearAllBtn');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
    
    // Use FormData instead of JSON for better compatibility
    const formData = new FormData();
    formData.append('action', 'clear_all');
    
    fetch('<?php echo BASE_URL; ?>/modules/notifikasi/clear_all.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error, status = ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="bi bi-check-circle"></i> Semua notifikasi telah ditandai sebagai dibaca
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('.container');
            container.insertBefore(alertDiv, container.firstChild);
            
            // Reload page after 1.5 seconds
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            alert('Gagal memproses: ' + (data.message || 'Unknown error'));
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses: ' + error.message);
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    });
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
