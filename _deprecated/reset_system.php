<?php
/**
 * RESET SYSTEM SCRIPT
 * Menghapus semua data penghuni, paket, dan notifikasi
 * Hanya menyisakan admin dan receptionist
 * 
 * Jalankan script ini dengan mengakses: http://localhost/sipap/reset_system.php
 */

// Prevent access dari browser langsung
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['confirm_reset'])) {
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset System - AureliaBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 500px;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .danger-box {
            background-color: #f8d7da;
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        h1 {
            color: #dc3545;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-danger {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-secondary {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-top: 10px;
        }
        .info-list {
            margin-top: 15px;
            padding-left: 20px;
        }
        .info-list li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h1>⚠️ Reset AureliaBox System</h1>
        
        <div class="danger-box">
            <h5 class="text-danger">PERHATIAN - AKSI TIDAK DAPAT DIBATALKAN!</h5>
            <p class="mb-0">Script ini akan menghapus data berikut secara permanen:</p>
        </div>

        <div class="warning-box">
            <h6>Data yang akan dihapus:</h6>
            <ul class="info-list mb-0">
                <li>✓ Semua data penghuni (residents)</li>
                <li>✓ Semua data paket (packages)</li>
                <li>✓ Semua notifikasi (notifications)</li>
                <li>✓ Semua users penghuni</li>
            </ul>
        </div>

        <div class="alert alert-info">
            <h6>Data yang akan dipertahankan:</h6>
            <ul class="info-list mb-0">
                <li>✓ User: admin</li>
                <li>✓ User: receptionist</li>
            </ul>
        </div>

        <form method="POST" onsubmit="return confirm('YAKIN INGIN RESET? Data akan dihapus PERMANEN!')">
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="confirmBox" name="confirm_reset" value="1" required>
                <label class="form-check-label" for="confirmBox">
                    Saya memahami bahwa aksi ini tidak dapat dibatalkan dan data akan dihapus permanen
                </label>
            </div>
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-exclamation-triangle"></i> RESET SISTEM SEKARANG
            </button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
    <?php
    exit;
}

// Include database config
require_once 'config/database.php';

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Start transaction
    $conn->begin_transaction();

    // 1. Hapus semua notifikasi
    $query1 = "DELETE FROM notifikasi";
    if (!$conn->query($query1)) {
        throw new Exception("Gagal menghapus notifikasi: " . $conn->error);
    }
    $notif_count = $conn->affected_rows;

    // 2. Hapus semua paket
    $query2 = "DELETE FROM paket";
    if (!$conn->query($query2)) {
        throw new Exception("Gagal menghapus paket: " . $conn->error);
    }
    $paket_count = $conn->affected_rows;

    // 3. Hapus semua penghuni
    $query3 = "DELETE FROM penghuni";
    if (!$conn->query($query3)) {
        throw new Exception("Gagal menghapus penghuni: " . $conn->error);
    }
    $penghuni_count = $conn->affected_rows;

    // 4. Hapus semua users dengan role 'penghuni'
    $query4 = "DELETE FROM users WHERE role = 'penghuni'";
    if (!$conn->query($query4)) {
        throw new Exception("Gagal menghapus users penghuni: " . $conn->error);
    }
    $users_count = $conn->affected_rows;

    // Commit transaction
    $conn->commit();

    // Sukses
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Successful - AureliaBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 500px;
            text-align: center;
        }
        h1 {
            color: #28a745;
            margin-bottom: 20px;
        }
        .stats {
            background-color: #f0f0f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .stat-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .stat-item:last-child {
            border-bottom: none;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h1>✓ Reset Berhasil!</h1>
        <p class="text-muted">Sistem telah direset sesuai permintaan.</p>

        <div class="stats">
            <div class="stat-item">
                <div class="stat-number"><?php echo $notif_count; ?></div>
                <div class="text-muted">Notifikasi dihapus</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $paket_count; ?></div>
                <div class="text-muted">Paket dihapus</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $penghuni_count; ?></div>
                <div class="text-muted">Penghuni dihapus</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $users_count; ?></div>
                <div class="text-muted">User penghuni dihapus</div>
            </div>
        </div>

        <div class="alert alert-info">
            <strong>User tersisa:</strong><br>
            • admin (password: password)<br>
            • receptionist (password: password)
        </div>

        <a href="index.php" class="btn btn-primary btn-lg">Kembali ke Login</a>
    </div>
</body>
</html>
    <?php

} catch (Exception $e) {
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Failed - AureliaBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 500px;
        }
        h1 {
            color: #dc3545;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>✗ Reset Gagal</h1>
        <div class="alert alert-danger">
            <strong>Error:</strong><br>
            <?php echo htmlspecialchars($e->getMessage()); ?>
        </div>
        <a href="reset_system.php" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>
    <?php
    exit;
}

$conn->close();
?>
