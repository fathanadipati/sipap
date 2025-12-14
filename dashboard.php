<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';

requireLogin();

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Normalize old role names to new ones
if ($role === 'resepsionis') $role = 'receptionist';
if ($role === 'penghuni') $role = 'resident';

$page_title = 'Dashboard';
?>
<?php require_once __DIR__ . '/includes/header.php'; ?>
<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<div class="container-fluid container-main py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-2">
                    <i class="bi bi-speedometer2"></i> Dashboard 
                    <span class="badge bg-secondary ms-2"><?php echo ucfirst($role); ?></span>
                </h1>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">THE GRAND AURELIA RESIDENCE</p>
            </div>
        </div>

        <?php if ($role === 'admin'): ?>
            <!-- Dashboard Admin -->
            <?php
            // Cek jika ada resident tanpa profil penghuni
            $check_residents = $conn->query("SELECT COUNT(*) as total FROM users u LEFT JOIN penghuni p ON u.id = p.user_id WHERE u.role = 'penghuni' AND p.id IS NULL");
            $residents_without_profile = $check_residents->fetch_assoc()['total'];
            ?>
            
            <?php if ($residents_without_profile > 0): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-left: 4px solid #ffc107; margin-bottom: 2rem;">
                <div class="d-flex gap-3 align-items-start">
                    <div style="font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <h5 class="mt-0">Ada <?php echo $residents_without_profile; ?> Resident Tanpa Profil Penghuni</h5>
                        <p class="mb-2">Ada akun resident yang belum memiliki profil penghuni di sistem. Resident ini akan melihat pesan di dashboard mereka.</p>
                        <a href="<?php echo BASE_URL; ?>/admin/residents_without_profile.php" class="btn btn-sm btn-warning">
                            <i class="bi bi-arrow-right"></i> Lihat Daftar Resident
                        </a>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <div class="row mb-4">
                <?php
                // Query data untuk cards
                $penghuni_result = $conn->query("SELECT COUNT(*) as total FROM penghuni");
                $penghuni_row = $penghuni_result->fetch_assoc();
                $total_penghuni = $penghuni_row['total'];
                
                $paket_result = $conn->query("SELECT COUNT(*) as total FROM paket");
                $paket_row = $paket_result->fetch_assoc();
                $total_paket = $paket_row['total'];
                
                $paket_loker_result = $conn->query("SELECT COUNT(*) as total FROM paket WHERE status = 'disimpan'");
                $paket_loker_row = $paket_loker_result->fetch_assoc();
                $total_loker = $paket_loker_row['total'];
                
                $users_result = $conn->query("SELECT COUNT(*) as total FROM users");
                $users_row = $users_result->fetch_assoc();
                $total_users = $users_row['total'];
                
                $staff_result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role IN ('admin', 'receptionist')");
                $staff_row = $staff_result->fetch_assoc();
                $total_staff = $staff_row['total'];
                ?>
                
                <!-- Card 1: Total Penghuni & Staff (Merged) -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center d-flex flex-column">
                            <div style="display: flex; align-items: center; justify-content: space-around; gap: 0.5rem; margin-bottom: 1rem;">
                                <!-- Penghuni Section -->
                                <div class="text-center" style="cursor: pointer; flex: 1; padding: 0.75rem;">
                                    <div style="font-size: 2rem; color: #ffc107; margin-bottom: 8px;">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <h3 style="font-size: 2rem; font-weight: bold; color: #ffc107; margin: 0 0 4px 0;">
                                        <?php echo $total_penghuni; ?>
                                    </h3>
                                    <p style="font-size: 0.85rem; color: #777; margin: 0;">Penghuni</p>
                                </div>
                                
                                <!-- Divider -->
                                <div style="width: 1px; height: 70px; background-color: #e0e0e0;"></div>
                                
                                <!-- Staff Section -->
                                <div class="text-center" style="cursor: pointer; flex: 1; padding: 0.75rem;">
                                    <div style="font-size: 2rem; color: #007bff; margin-bottom: 8px;">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </div>
                                    <h3 style="font-size: 2rem; font-weight: bold; color: #007bff; margin: 0 0 4px 0;">
                                        <?php echo $total_staff; ?>
                                    </h3>
                                    <p style="font-size: 0.85rem; color: #777; margin: 0;">Staff</p>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem; flex-grow: 1; align-items: flex-end;">
                                <a href="<?php echo BASE_URL; ?>/modules/penghuni/list.php" class="btn btn-sm btn-outline-warning w-100" style="font-weight: 500;">
                                    Penghuni
                                </a>
                                <a href="<?php echo BASE_URL; ?>/admin/users.php" class="btn btn-sm btn-outline-primary w-100" style="font-weight: 500;">
                                    Staff
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2: Total Paket -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center d-flex flex-column">
                            <div style="font-size: 2.5rem; color: #28a745; margin-bottom: 10px;">
                                <i class="bi bi-box2-fill"></i>
                            </div>
                            <h5 class="card-title" style="font-weight: bold; flex-grow: 1;">Total Paket</h5>
                            <h3 class="card-title" style="font-size: 2.5rem; font-weight: bold; color: #28a745; margin-bottom: 1rem;">
                                <?php echo $total_paket; ?>
                            </h3>
                            <a href="<?php echo BASE_URL; ?>/modules/paket/list.php" class="btn btn-sm btn-outline-success w-100" style="font-weight: 500;">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3: Paket Tersimpan -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center d-flex flex-column">
                            <div style="font-size: 2.5rem; color: #17a2b8; margin-bottom: 10px;">
                                <i class="bi bi-door-closed"></i>
                            </div>
                            <h5 class="card-title" style="font-weight: bold; flex-grow: 1;">Paket Tersimpan</h5>
                            <h3 class="card-title" style="font-size: 2.5rem; font-weight: bold; color: #17a2b8; margin-bottom: 1rem;">
                                <?php echo $total_loker; ?>
                            </h3>
                            <a href="<?php echo BASE_URL; ?>/modules/paket/list.php?status=disimpan" class="btn btn-sm btn-outline-info w-100" style="font-weight: 500;">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Card 4: Cetak Laporan -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center d-flex flex-column">
                            <div style="font-size: 2.5rem; color: #dc3545; margin-bottom: 10px;">
                                <i class="bi bi-printer-fill"></i>
                            </div>
                            <h5 class="card-title" style="font-weight: bold;">Cetak Laporan</h5>
                            <p class="text-muted mb-0 flex-grow-1 d-flex align-items-center justify-content-center">Laporan Penerimaan Paket</p>
                            <a href="<?php echo BASE_URL; ?>/modules/paket/print_report.php" class="btn btn-sm btn-outline-danger w-100 mt-2" style="font-weight: 500;">
                                Buka Laporan →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistik Paket</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $disimpan = $conn->query("SELECT COUNT(*) as total FROM paket WHERE status = 'disimpan'")->fetch_assoc()['total'];
                            $diambil = $conn->query("SELECT COUNT(*) as total FROM paket WHERE status = 'diambil'")->fetch_assoc()['total'];
                            ?>
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="badge bg-info">Disimpan</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-info" style="font-size: 1rem; padding: 0.5rem 0.75rem;">
                                                <?php echo $disimpan; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="badge bg-success">Diambil</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 0.75rem;">
                                                <?php echo $diambil; ?>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Paket Terbaru Diterima</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $query = "SELECT p.id, p.nomor_paket, ph.nomor_unit, p.nama_pengirim, p.tanggal_terima, p.status
                                     FROM paket p
                                     JOIN penghuni ph ON p.penghuni_id = ph.id
                                     ORDER BY p.tanggal_terima DESC
                                     LIMIT 5";
                            $result = $conn->query($query);
                            
                            if ($result && $result->num_rows > 0) {
                            ?>
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Paket</th>
                                        <th>Unit</th>
                                        <th style="text-align: center;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <small><?php echo substr($row['nomor_paket'], 0, 12); ?>...</small>
                                        </td>
                                        <td><?php echo $row['nomor_unit']; ?></td>
                                        <td style="text-align: center;">
                                            <?php
                                            $status_badges = [
                                                'diterima' => '<span class="badge bg-warning">Diterima</span>',
                                                'disimpan' => '<span class="badge bg-info">Disimpan</span>',
                                                'diambil' => '<span class="badge bg-success">Diambil</span>'
                                            ];
                                            echo $status_badges[$row['status']] ?? '<span class="badge bg-secondary">Unknown</span>';
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <?php
                            } else {
                                echo '<p class="text-muted text-center py-4">Belum ada paket yang diterima</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($role === 'receptionist'): ?>
            <!-- Dashboard Receptionist -->
            <div class="row mb-4">
                <?php
                // Query data untuk cards
                $paket_loker_result = $conn->query("SELECT COUNT(*) as total FROM paket WHERE status = 'disimpan'");
                $paket_loker_row = $paket_loker_result->fetch_assoc();
                $paket_loker = $paket_loker_row['total'];
                
                $paket_diambil_result = $conn->query("SELECT COUNT(*) as total FROM paket WHERE status = 'diambil' AND DATE(tanggal_diambil) = CURDATE()");
                $paket_diambil_row = $paket_diambil_result->fetch_assoc();
                $paket_diambil_hari = $paket_diambil_row['total'];
                
                $total_paket_result = $conn->query("SELECT COUNT(*) as total FROM paket");
                $total_paket_row = $total_paket_result->fetch_assoc();
                $total_paket = $total_paket_row['total'];
                ?>
                
                <!-- Card 1: Terima Paket Baru -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="cursor: pointer;" onclick="window.location.href='<?php echo BASE_URL; ?>/modules/paket/add.php';">
                        <div class="card-body text-center">
                            <div style="font-size: 2.5rem; color: #28a745; margin-bottom: 10px;">
                                <i class="bi bi-plus-square-fill"></i>
                            </div>
                            <h5 class="card-title">Terima Paket</h5>
                            <p class="card-text text-muted mb-0">Input Paket Baru</p>
                            <a href="<?php echo BASE_URL; ?>/modules/paket/add.php" class="btn btn-sm btn-success mt-3" onclick="event.stopPropagation();">
                                + Paket Baru →
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2: Paket Tersimpan -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="cursor: pointer;" onclick="window.location.href='<?php echo BASE_URL; ?>/modules/paket/list.php?status=disimpan';">
                        <div class="card-body text-center">
                            <div style="font-size: 2.5rem; color: #17a2b8; margin-bottom: 10px;">
                                <i class="bi bi-door-closed"></i>
                            </div>
                            <h3 class="card-title" style="font-size: 2.5rem; font-weight: bold; color: #17a2b8;" data-stat="paket_loker">
                                <?php echo $paket_loker; ?>
                            </h3>
                            <p class="card-text text-muted mb-0">Paket Tersimpan</p>
                            <a href="<?php echo BASE_URL; ?>/modules/paket/list.php?status=disimpan" class="btn btn-sm btn-outline-info mt-3 w-100" onclick="event.stopPropagation();">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3: Diambil Hari Ini -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="cursor: pointer;" onclick="window.location.href='<?php echo BASE_URL; ?>/modules/paket/list.php?status=diambil&date_filter=today';">
                        <div class="card-body text-center">
                            <div style="font-size: 2.5rem; color: #ffc107; margin-bottom: 10px;">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h3 class="card-title" style="font-size: 2.5rem; font-weight: bold; color: #ffc107;" data-stat="paket_diambil_hari">
                                <?php echo $paket_diambil_hari; ?>
                            </h3>
                            <p class="card-text text-muted mb-0">Diambil Hari Ini</p>
                            <a href="<?php echo BASE_URL; ?>/modules/paket/list.php?status=diambil&date_filter=today" class="btn btn-sm btn-outline-warning mt-3 w-100" onclick="event.stopPropagation();">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Card 4: Total Paket -->
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card border-0 shadow-sm h-100" style="cursor: pointer;" onclick="window.location.href='<?php echo BASE_URL; ?>/modules/paket/list.php';">
                        <div class="card-body text-center">
                            <div style="font-size: 2.5rem; color: #007bff; margin-bottom: 10px;">
                                <i class="bi bi-box2-fill"></i>
                            </div>
                            <h3 class="card-title" style="font-size: 2.5rem; font-weight: bold; color: #007bff;" data-stat="total_paket">
                                <?php echo $total_paket; ?>
                            </h3>
                            <p class="card-text text-muted mb-0">Total Paket</p>
                            <a href="<?php echo BASE_URL; ?>/modules/paket/list.php" class="btn btn-sm btn-outline-primary mt-3 w-100" onclick="event.stopPropagation();">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Paket di Penyimpanan -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-task"></i> Paket Tersimpan (5 Terbaru)</h5>
                    <a href="<?php echo BASE_URL; ?>/modules/paket/list.php?status=disimpan" class="btn btn-sm btn-light">
                        Lihat Semua →
                    </a>
                </div>
                <div class="card-body">
                    <?php
                    $query = "SELECT p.id, p.nomor_paket, ph.nomor_unit, ph.nama_kontak_darurat, p.nomor_loker, p.tanggal_terima
                             FROM paket p
                             JOIN penghuni ph ON p.penghuni_id = ph.id
                             WHERE p.status = 'disimpan'
                             ORDER BY p.tanggal_terima DESC
                             LIMIT 5";
                    $result = $conn->query($query);
                    
                    if ($result && $result->num_rows > 0):
                    ?>
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>No. Paket</th>
                                <th>Unit</th>
                                <th style="text-align: center;">Penyimpanan</th>
                                <th>Diterima</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><small><?php echo substr($row['nomor_paket'], 0, 12); ?>...</small></strong></td>
                                <td><?php echo $row['nomor_unit']; ?></td>
                                <td style="text-align: center;">
                                    <?php
                                    if ($row['nomor_loker'] === 'WAREHOUSE') {
                                        echo '<span class="badge" style="background-color: #0066cc;"><i class="bi bi-building"></i> ' . $row['nomor_loker'] . '</span>';
                                    } else {
                                        echo '<span class="badge bg-info">' . $row['nomor_loker'] . '</span>';
                                    }
                                    ?>
                                </td>
                                <td><small><?php echo date('d M Y H:i', strtotime($row['tanggal_terima'])); ?></small></td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center;">
                                        <a href="<?php echo BASE_URL; ?>/modules/paket/view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success quick-mark-btn dashboard-quick-mark" 
                                                data-paket-id="<?php echo $row['id']; ?>"
                                                title="Tandai sebagai Diambil" style="cursor: pointer;">
                                            <i class="bi bi-check2-circle"></i> Diambil
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>/modules/paket/edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="alert alert-info m-0">
                        <i class="bi bi-info-circle"></i> Tidak ada paket di loker saat ini.
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($role === 'resident'): ?>
            <!-- Dashboard Resident -->
            <?php
            $query = "SELECT id FROM penghuni WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $resident = $result->fetch_assoc();
            $resident_id = $resident['id'] ?? null;
            
            // Jika resident belum memiliki profil penghuni, tampilkan pesan
            $profile_not_created = is_null($resident_id);
            
            // Query statistik (hanya jika resident_id ada)
            $paket_menunggu = 0;
            $paket_diambil = 0;
            
            if (!$profile_not_created) {
                $menunggu_query = "SELECT COUNT(*) as total FROM paket WHERE penghuni_id = ? AND status IN ('diterima', 'disimpan')";
                $menunggu_stmt = $conn->prepare($menunggu_query);
                $menunggu_stmt->bind_param('i', $resident_id);
                $menunggu_stmt->execute();
                $menunggu_result = $menunggu_stmt->get_result();
                $menunggu_row = $menunggu_result->fetch_assoc();
                $paket_menunggu = $menunggu_row['total'];
                
                $diambil_query = "SELECT COUNT(*) as total FROM paket WHERE penghuni_id = ? AND status = 'diambil'";
                $diambil_stmt = $conn->prepare($diambil_query);
                $diambil_stmt->bind_param('i', $resident_id);
                $diambil_stmt->execute();
                $diambil_result = $diambil_stmt->get_result();
                $diambil_row = $diambil_result->fetch_assoc();
                $paket_diambil = $diambil_row['total'];
            }
            ?>
            
            <!-- Alert jika profil belum dibuat -->
            <?php if ($profile_not_created): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-left: 4px solid #ffc107; margin-bottom: 2rem;">
                <div class="d-flex gap-3 align-items-start">
                    <div style="font-size: 1.5rem; flex-shrink: 0;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <h5 class="mt-0">Profil Penghuni Belum Dibuat</h5>
                        <p class="mb-2">Untuk menggunakan sistem ini dengan fitur lengkap, admin perlu membuat profil penghuni untuk akun Anda. 
                        Profil ini berisi informasi unit, nomor HP, dan kontak darurat Anda.</p>
                        <p class="mb-0"><strong>Apa yang perlu dilakukan?</strong></p>
                        <ol class="mb-2" style="font-size: 0.95rem;">
                            <li>Hubungi admin/receptionist untuk membuat profil penghuni Anda</li>
                            <li>Siapkan informasi: nomor unit, nomor HP, nama kontak darurat</li>
                            <li>Setelah profil dibuat, refresh halaman ini</li>
                        </ol>
                        <small class="text-muted d-block">Jika Anda adalah admin, gunakan menu <strong>Data Master → Penghuni → Tambah Penghuni Baru</strong></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            
            <!-- Debug Info untuk Development -->
            <div class="alert alert-secondary mb-4">
                <small><strong>Debug Info:</strong> User ID <?php echo htmlspecialchars($user_id); ?> tidak memiliki profil penghuni. <a href="<?php echo BASE_URL; ?>/debug_resident_profile.php" target="_blank">Lihat Detail</a></small>
            </div>
            <?php endif; ?>

            <div class="row mb-4">
                <!-- Card 1: Paket Menunggu -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100" <?php echo $profile_not_created ? 'style="opacity: 0.6;"' : ''; ?>>
                        <div class="card-body text-center">
                            <div style="font-size: 2.5rem; color: #007bff; margin-bottom: 10px;">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <h3 class="card-title" style="font-size: 2.5rem; font-weight: bold; color: #007bff;">
                                <?php echo $paket_menunggu; ?>
                            </h3>
                            <p class="card-text text-muted mb-0">Paket Menunggu</p>
                            <small class="text-muted">Diterima / Tersimpan</small>
                        </div>
                    </div>
                </div>
                
                <!-- Card 2: Paket Diambil -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm h-100" <?php echo $profile_not_created ? 'style="opacity: 0.6;"' : ''; ?>>
                        <div class="card-body text-center">
                            <div style="font-size: 2.5rem; color: #28a745; margin-bottom: 10px;">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h3 class="card-title" style="font-size: 2.5rem; font-weight: bold; color: #28a745;">
                                <?php echo $paket_diambil; ?>
                            </h3>
                            <p class="card-text text-muted mb-0">Paket Diambil</p>
                            <small class="text-muted">Sudah diambil</small>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3: Info Paket -->
                <div class="col-md-6 col-lg-4 mb-3">
                    <a href="<?php echo BASE_URL; ?>/modules/paket/help.php" class="card border-0 shadow-sm h-100 text-decoration-none text-dark" <?php echo $profile_not_created ? 'style="opacity: 0.6;"' : ''; ?>>
                        <div class="card-body text-center">
                            <div style="font-size: 2.5rem; color: #17a2b8; margin-bottom: 10px;">
                                <i class="bi bi-question-circle-fill"></i>
                            </div>
                            <h5 class="card-title">Panduan & Bantuan</h5>
                            <p class="card-text text-muted mb-0">Pelajari cara menggunakan sistem</p>
                            <div class="btn btn-sm btn-outline-info mt-3">
                                Baca Panduan →
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Tabel Paket Saya (Hanya jika profil sudah dibuat) -->
            <?php if (!$profile_not_created): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-inbox"></i> Paket Saya (5 Terbaru)</h5>
                    <a href="<?php echo BASE_URL; ?>/modules/paket/my-packages.php" class="btn btn-sm btn-light">
                        Lihat Semua →
                    </a>
                </div>
                <div class="card-body">
                    <?php
                    $query = "SELECT id, nomor_paket, nama_pengirim, nama_ekspedisi, status, nomor_loker, tanggal_terima, tanggal_diambil
                             FROM paket
                             WHERE penghuni_id = ?
                             ORDER BY tanggal_terima DESC
                             LIMIT 5";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('i', $resident_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result && $result->num_rows > 0):
                    ?>
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>No. Paket</th>
                                <th>Pengirim</th>
                                <th>Ekspedisi</th>
                                <th style="text-align: center;">Penyimpanan</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><small><?php echo substr($row['nomor_paket'], 0, 12); ?>...</small></strong></td>
                                <td><small><?php echo $row['nama_pengirim']; ?></small></td>
                                <td><small><?php echo $row['nama_ekspedisi']; ?></small></td>
                                <td style="text-align: center;">
                                    <?php 
                                    if ($row['nomor_loker']) {
                                        if ($row['nomor_loker'] === 'WAREHOUSE') {
                                            echo '<span class="badge" style="background-color: #0066cc;"><i class="bi bi-building"></i> ' . $row['nomor_loker'] . '</span>';
                                        } else {
                                            echo '<span class="badge bg-info">' . $row['nomor_loker'] . '</span>';
                                        }
                                    } else {
                                        echo '<span class="badge bg-secondary">-</span>';
                                    }
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php
                                    $status_badges = [
                                        'disimpan' => '<span class="badge bg-info">Tersimpan</span>',
                                        'diambil' => '<span class="badge bg-success">Diambil</span>'
                                    ];
                                    echo $status_badges[$row['status']] ?? '<span class="badge bg-secondary">Unknown</span>';
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; justify-content: center;">
                                    <a href="<?php echo BASE_URL; ?>/modules/paket/view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="alert alert-info m-0">
                        <i class="bi bi-info-circle"></i> Anda belum memiliki paket apapun.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            <!-- Pesan ketika profil belum dibuat -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-inbox"></i> Paket Saya</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info m-0">
                        <i class="bi bi-info-circle"></i> Data paket akan tampil setelah admin membuat profil penghuni Anda.
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
// Handle Quick Mark Paket as Diambil (for both list and dashboard)
document.querySelectorAll('.quick-mark-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const paketId = this.getAttribute('data-paket-id');
        const btn = this;
        const isDashboard = this.classList.contains('dashboard-quick-mark');
        
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
                // Update button
                btn.className = 'btn btn-sm btn-success';
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-check2-circle"></i> Diambil';
                btn.title = 'Sudah Diambil';
                btn.style.cursor = 'default';
                
                // Show success message
                if (isDashboard) {
                    // For dashboard, show toast-like message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.style.position = 'fixed';
                    alertDiv.style.top = '20px';
                    alertDiv.style.right = '20px';
                    alertDiv.style.zIndex = '9999';
                    alertDiv.style.minWidth = '300px';
                    alertDiv.innerHTML = `
                        <i class="bi bi-check-circle"></i> ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alertDiv);
                    
                    // Auto-close after 4 seconds
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 4000);
                } else {
                    // For list page, reload with success message
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
