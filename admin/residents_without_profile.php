<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

// Hanya admin yang bisa akses
requireRole('admin');

$page_title = 'Resident Tanpa Profil';
?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container-lg">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 style="font-weight: bold; color: #333; margin-bottom: 0.5rem;">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem; color: #ffc107;"></i> Resident Tanpa Profil Penghuni
                </h1>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Daftar user dengan role resident yang belum memiliki profil penghuni</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/dashboard.php" class="btn btn-secondary" style="padding: 0.6rem 1.5rem;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Data Resident Tanpa Profil</h5>
            </div>
            <div class="card-body">
                <?php
                // Query untuk mendapatkan user dengan role 'penghuni' yang tidak memiliki profil di tabel penghuni
                $query = "SELECT u.id, u.username, u.email, u.nama_lengkap, u.created_at
                         FROM users u
                         LEFT JOIN penghuni p ON u.id = p.user_id
                         WHERE u.role = 'penghuni' AND p.id IS NULL
                         ORDER BY u.created_at DESC";
                
                $result = $conn->query($query);
                
                if ($result && $result->num_rows > 0):
                ?>
                <div class="alert alert-warning">
                    <strong>⚠️ Perhatian:</strong> Ditemukan <?php echo $result->num_rows; ?> resident yang belum memiliki profil penghuni. 
                    Mereka akan melihat pesan di dashboard mereka.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th style="width: 5%;">ID</th>
                                <th style="width: 20%;">Username</th>
                                <th style="width: 30%;">Nama Lengkap</th>
                                <th style="width: 25%;">Email</th>
                                <th style="width: 15%;">Tgl Daftar</th>
                                <th style="width: 5%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Reset result pointer
                            $result->data_seek(0);
                            while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><code><?php echo htmlspecialchars($row['id']); ?></code></td>
                                <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td><small><?php echo htmlspecialchars($row['email']); ?></small></td>
                                <td><small><?php echo date('d M Y H:i', strtotime($row['created_at'])); ?></small></td>
                                <td style="text-align: center;">
                                    <a href="<?php echo BASE_URL; ?>/modules/penghuni/add.php" class="btn btn-sm btn-primary" title="Buat Profil Penghuni">
                                        <i class="bi bi-plus-circle"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3">
                    <strong>💡 Solusi:</strong> Klik tombol <i class="bi bi-plus-circle"></i> di kolom Aksi untuk membuat profil penghuni, atau gunakan menu 
                    <strong>Data Master → Penghuni → Tambah Penghuni Baru</strong>
                </div>

                <?php
                else:
                ?>
                <div class="alert alert-success m-0">
                    <i class="bi bi-check-circle"></i> <strong>Sempurna!</strong> Semua resident sudah memiliki profil penghuni.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
