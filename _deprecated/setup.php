<?php
/**
 * AureliaBox - Smart Package Management System
 * 
 * Script untuk membuat data dummy/demo
 * Jalankan di browser: http://localhost/sipap/setup.php
 * Atau jalankan dari command line: php setup.php
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';

$setup_error = '';
$setup_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_demo_data'])) {
    try {
        // Check apakah sudah ada penghuni
        $check = $conn->query("SELECT COUNT(*) as count FROM penghuni");
        $count = $check->fetch_assoc()['count'];

        if ($count == 0) {
            // Buat data dummy penghuni dengan user
            $demo_data = [
                [
                    'username' => 'penghuni',
                    'email' => 'penghuni@sipap.local',
                    'password' => 'password',
                    'nama_lengkap' => 'Budi Santoso',
                    'nomor_unit' => '101',
                    'nomor_hp' => '08123456789',
                    'blok' => 'A',
                    'lantai' => 1
                ],
                [
                    'username' => 'penghuni2',
                    'email' => 'penghuni2@sipap.local',
                    'password' => 'password',
                    'nama_lengkap' => 'Siti Nurhaliza',
                    'nomor_unit' => '102',
                    'nomor_hp' => '08234567890',
                    'blok' => 'A',
                    'lantai' => 1
                ],
                [
                    'username' => 'penghuni3',
                    'email' => 'penghuni3@sipap.local',
                    'password' => 'password',
                    'nama_lengkap' => 'Ahmad Wijaya',
                    'nomor_unit' => '201',
                    'nomor_hp' => '08345678901',
                    'blok' => 'B',
                    'lantai' => 2
                ]
            ];

            $count_created = 0;
            foreach ($demo_data as $data) {
                // Buat user
                $hashed = hashPassword($data['password']);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, nama_lengkap, is_active) VALUES (?, ?, ?, 'penghuni', ?, 1)");
                $stmt->bind_param('ssss', $data['username'], $data['email'], $hashed, $data['nama_lengkap']);
                
                if ($stmt->execute()) {
                    $user_id = $conn->insert_id;
                    
                    // Buat penghuni
                    $stmt2 = $conn->prepare("INSERT INTO penghuni (user_id, nomor_unit, nomor_hp, blok, lantai) VALUES (?, ?, ?, ?, ?)");
                    $lantai = (int)$data['lantai'];
                    $stmt2->bind_param('issis', $user_id, $data['nomor_unit'], $data['nomor_hp'], $data['blok'], $lantai);
                    
                    if ($stmt2->execute()) {
                        $count_created++;
                    }
                }
            }

            if ($count_created > 0) {
                $setup_success = "$count_created penghuni demo berhasil dibuat!";
            } else {
                $setup_error = 'Gagal membuat data penghuni demo.';
            }
        } else {
            $setup_error = 'Data penghuni sudah ada. Skip pembuatan data demo.';
        }
    } catch (Exception $e) {
        $setup_error = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup SIPAP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .setup-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 2rem;
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <h1 class="mb-4"><i class="bi bi-tools"></i> Setup SIPAP</h1>

        <?php if ($setup_error): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> <?php echo $setup_error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($setup_success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $setup_success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Status Database</h5>
            </div>
            <div class="card-body">
                <?php
                // Check tabel
                $check_users = $conn->query("SHOW TABLES LIKE 'users'");
                $check_penghuni = $conn->query("SHOW TABLES LIKE 'penghuni'");
                $check_paket = $conn->query("SHOW TABLES LIKE 'paket'");
                $check_notifikasi = $conn->query("SHOW TABLES LIKE 'notifikasi'");

                $tables = [
                    'users' => $check_users->num_rows > 0,
                    'penghuni' => $check_penghuni->num_rows > 0,
                    'paket' => $check_paket->num_rows > 0,
                    'notifikasi' => $check_notifikasi->num_rows > 0
                ];

                $all_exist = array_reduce($tables, function($carry, $item) {
                    return $carry && $item;
                }, true);
                ?>

                <p class="mb-3">
                    <strong>Database:</strong> 
                    <span class="badge bg-<?php echo $all_exist ? 'success' : 'warning'; ?>">
                        <?php echo $all_exist ? 'OK' : 'SETUP DIPERLUKAN'; ?>
                    </span>
                </p>

                <ul class="list-unstyled">
                    <?php foreach ($tables as $table => $exists): ?>
                    <li class="mb-2">
                        <i class="bi bi-<?php echo $exists ? 'check-circle text-success' : 'x-circle text-danger'; ?>"></i>
                        Tabel <code><?php echo $table; ?></code>
                        <span class="badge <?php echo $exists ? 'bg-success' : 'bg-danger'; ?>">
                            <?php echo $exists ? 'Ada' : 'Belum'; ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (!$all_exist): ?>
                <div class="alert alert-warning mt-3">
                    <strong>⚠️ Setup Database Diperlukan!</strong>
                    <ol class="mb-0 mt-2">
                        <li>Buka phpMyAdmin atau MySQL Workbench</li>
                        <li>Import file <code>database.sql</code></li>
                        <li>Atau jalankan script SQL secara manual</li>
                    </ol>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Data Demo</h5>
            </div>
            <div class="card-body">
                <p>Klik tombol di bawah untuk membuat data penghuni demo:</p>
                <form method="POST">
                    <button type="submit" name="create_demo_data" class="btn btn-primary" value="1">
                        <i class="bi bi-plus-circle"></i> Buat Data Demo
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-success btn-lg">
                <i class="bi bi-box-arrow-in-right"></i> Lanjut ke Login
            </a>
        </div>

        <div class="mt-3 alert alert-info">
            <small>
                <strong>Akun Default:</strong><br>
                Admin: <code>admin</code> / <code>password</code><br>
                Resepsionis: <code>resepsionis</code> / <code>password</code>
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
