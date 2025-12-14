<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';

// Hanya bisa diakses oleh admin atau user yang login
requireLogin();

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Normalize old role names
if ($role === 'resepsionis') $role = 'receptionist';
if ($role === 'penghuni') $role = 'resident';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Resident Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f5f5f5; padding: 20px;">
    <div class="container">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Debug: Resident Profile Check</h4>
            </div>
            <div class="card-body">
                <h5>Session Information:</h5>
                <table class="table table-sm">
                    <tr>
                        <td><strong>User ID:</strong></td>
                        <td><?php echo htmlspecialchars($user_id); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td><?php echo htmlspecialchars($role); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Username:</strong></td>
                        <td><?php echo htmlspecialchars($_SESSION['username'] ?? 'N/A'); ?></td>
                    </tr>
                </table>

                <hr>

                <h5>User Data in Database:</h5>
                <?php
                $query = "SELECT id, username, email, role, nama_lengkap FROM users WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($row = $result->fetch_assoc()) {
                    echo '<table class="table table-sm">';
                    foreach ($row as $key => $value) {
                        echo '<tr><td><strong>' . ucfirst(str_replace('_', ' ', $key)) . ':</strong></td><td>' . htmlspecialchars($value) . '</td></tr>';
                    }
                    echo '</table>';
                } else {
                    echo '<div class="alert alert-danger">❌ User tidak ditemukan di database!</div>';
                }
                ?>

                <hr>

                <h5>Penghuni Profile Data:</h5>
                <?php
                $query = "SELECT * FROM penghuni WHERE user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($row = $result->fetch_assoc()) {
                    echo '<div class="alert alert-success">✅ Profil penghuni DITEMUKAN</div>';
                    echo '<table class="table table-sm">';
                    foreach ($row as $key => $value) {
                        echo '<tr><td><strong>' . ucfirst(str_replace('_', ' ', $key)) . ':</strong></td><td>' . htmlspecialchars($value ?? 'NULL') . '</td></tr>';
                    }
                    echo '</table>';
                    
                    // Cek paket untuk penghuni ini
                    $penghuni_id = $row['id'];
                    echo '<hr>';
                    echo '<h5>Paket untuk Penghuni ini:</h5>';
                    
                    $query_paket = "SELECT COUNT(*) as total FROM paket WHERE penghuni_id = ?";
                    $stmt_paket = $conn->prepare($query_paket);
                    $stmt_paket->bind_param('i', $penghuni_id);
                    $stmt_paket->execute();
                    $result_paket = $stmt_paket->get_result();
                    $row_paket = $result_paket->fetch_assoc();
                    
                    echo '<div class="alert alert-info">Total Paket: <strong>' . $row_paket['total'] . '</strong></div>';
                } else {
                    echo '<div class="alert alert-warning">❌ Profil penghuni TIDAK DITEMUKAN</div>';
                    echo '<p>User dengan ID ' . $user_id . ' belum memiliki profil penghuni di database.</p>';
                    echo '<p><strong>Solusi:</strong> Admin perlu membuat profil penghuni melalui menu Data Master → Penghuni → Tambah Penghuni Baru</p>';
                }
                ?>

                <hr>

                <div class="alert alert-info">
                    <h6>Informasi untuk Admin:</h6>
                    <p>Jika resident ini tidak memiliki profil penghuni, ikuti langkah berikut:</p>
                    <ol>
                        <li>Login sebagai admin</li>
                        <li>Buka menu <strong>Data Master</strong> → <strong>Penghuni</strong></li>
                        <li>Klik <strong>Tambah Penghuni Baru</strong></li>
                        <li>Isi form dengan data unit, nomor HP, kontak darurat, dll</li>
                        <li>Klik <strong>Simpan</strong></li>
                    </ol>
                </div>

                <a href="<?php echo BASE_URL; ?>/dashboard.php" class="btn btn-primary">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
