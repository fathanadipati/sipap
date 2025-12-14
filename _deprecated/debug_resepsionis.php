<?php
/**
 * Script Cek User Resepsionis
 * Untuk troubleshoot login resepsionis yang gagal
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Login Resepsionis - SIPAP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; padding: 20px; font-family: Arial, sans-serif; }
        .container { max-width: 900px; margin: 0 auto; }
        .info-box { background: #e7f3ff; border: 1px solid #b3d9ff; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error-box { background: #ffe7e7; border: 1px solid #ffb3b3; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .success-box { background: #e7ffe7; border: 1px solid #b3ffb3; padding: 15px; border-radius: 5px; margin: 10px 0; }
        table { background: white; border-collapse: collapse; width: 100%; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #f8f9fa; font-weight: bold; }
        .btn-action { margin-top: 10px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>

<div class="container">
    <h1>🔍 Debug Login Resepsionis</h1>
    <p>Script ini membantu mencari masalah login untuk role resepsionis</p>
    <hr>

    <?php
    // 1. Cek koneksi database
    echo "<h3>1️⃣ Status Koneksi Database</h3>";
    
    if ($conn->connect_error) {
        echo "<div class='error-box'>";
        echo "❌ <strong>Koneksi Gagal:</strong> " . $conn->connect_error;
        echo "</div>";
        die("Database connection failed!");
    } else {
        echo "<div class='success-box'>";
        echo "✅ <strong>Koneksi Berhasil</strong> ke database: " . DB_NAME;
        echo "</div>";
    }

    // 2. Cek tabel users
    echo "<h3>2️⃣ Status Tabel Users</h3>";
    
    $tables_check = $conn->query("SHOW TABLES LIKE 'users'");
    if ($tables_check->num_rows > 0) {
        echo "<div class='success-box'>";
        echo "✅ Tabel 'users' ada di database";
        echo "</div>";
    } else {
        echo "<div class='error-box'>";
        echo "❌ Tabel 'users' TIDAK ditemukan - Coba import database.sql";
        echo "</div>";
    }

    // 3. Cek user resepsionis
    echo "<h3>3️⃣ Cek User 'resepsionis'</h3>";
    
    $resep_check = $conn->query("SELECT id, username, email, password, role, is_active FROM users WHERE username='resepsionis'");
    
    if (!$resep_check) {
        echo "<div class='error-box'>";
        echo "❌ <strong>Query Error:</strong> " . $conn->error;
        echo "</div>";
    } else if ($resep_check->num_rows === 0) {
        echo "<div class='error-box'>";
        echo "❌ <strong>User 'resepsionis' TIDAK ditemukan di database!</strong>";
        echo "<br>Solusi: Klik tombol 'Create Resepsionis User' di bawah";
        echo "</div>";
    } else {
        echo "<div class='success-box'>";
        echo "✅ User 'resepsionis' DITEMUKAN";
        echo "</div>";
        
        $resep = $resep_check->fetch_assoc();
        
        echo "<table>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        echo "<tr><td>ID</td><td>" . $resep['id'] . "</td></tr>";
        echo "<tr><td>Username</td><td><code>" . $resep['username'] . "</code></td></tr>";
        echo "<tr><td>Email</td><td>" . $resep['email'] . "</td></tr>";
        echo "<tr><td>Role</td><td><strong>" . $resep['role'] . "</strong></td></tr>";
        echo "<tr><td>Active</td><td>" . ($resep['is_active'] ? '✅ Ya' : '❌ Tidak') . "</td></tr>";
        echo "<tr><td>Password Hash</td><td><code>" . substr($resep['password'], 0, 20) . "...</code></td></tr>";
        echo "</table>";
        
        // Test password verification
        echo "<h4>Test Password Verification:</h4>";
        $test_pass = 'password';
        if (password_verify($test_pass, $resep['password'])) {
            echo "<div class='success-box'>";
            echo "✅ Password <code>password</code> benar untuk user resepsionis";
            echo "</div>";
        } else {
            echo "<div class='error-box'>";
            echo "❌ Password <code>password</code> SALAH untuk user resepsionis";
            echo "<br>Clik tombol 'Reset Password Resepsionis' untuk set ulang ke 'password'";
            echo "</div>";
        }
    }

    // 4. Semua user di database
    echo "<h3>4️⃣ Daftar Semua User di Database</h3>";
    
    $all_users = $conn->query("SELECT id, username, email, role, is_active FROM users ORDER BY id");
    
    if ($all_users->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Active</th></tr>";
        
        while ($row = $all_users->fetch_assoc()) {
            $active = $row['is_active'] ? '✅' : '❌';
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td><code>" . $row['username'] . "</code></td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td><strong>" . ucfirst($row['role']) . "</strong></td>";
            echo "<td>" . $active . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='error-box'>";
        echo "❌ Tidak ada user di database!";
        echo "</div>";
    }

    // 5. Form untuk create/reset user
    echo "<h3>5️⃣ Perbaikan Otomatis</h3>";
    
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $hashed_pass = password_hash('password', PASSWORD_BCRYPT);
        
        if ($action === 'create_resepsionis') {
            // Cek apakah resepsionis sudah ada
            $check = $conn->query("SELECT id FROM users WHERE username='resepsionis'");
            
            if ($check->num_rows > 0) {
                // Update password
                $update_sql = "UPDATE users SET password='$hashed_pass', is_active=1 WHERE username='resepsionis'";
                if ($conn->query($update_sql)) {
                    echo "<div class='success-box'>";
                    echo "✅ Password resepsionis berhasil di-reset menjadi: <code>password</code>";
                    echo "</div>";
                } else {
                    echo "<div class='error-box'>";
                    echo "❌ Error: " . $conn->error;
                    echo "</div>";
                }
            } else {
                // Insert user baru
                $insert_sql = "INSERT INTO users (username, email, password, role, nama_lengkap, is_active) 
                              VALUES ('resepsionis', 'resepsionis@sipap.local', '$hashed_pass', 'resepsionis', 'Resepsionis Apartemen', 1)";
                if ($conn->query($insert_sql)) {
                    echo "<div class='success-box'>";
                    echo "✅ User resepsionis berhasil dibuat dengan password: <code>password</code>";
                    echo "</div>";
                } else {
                    echo "<div class='error-box'>";
                    echo "❌ Error: " . $conn->error;
                    echo "</div>";
                }
            }
            
            echo "<script>setTimeout(() => location.reload(), 2000);</script>";
        }
    }
    
    // Tombol action
    echo "<form method='POST'>";
    echo "<input type='hidden' name='action' value='create_resepsionis'>";
    echo "<button type='submit' class='btn btn-primary btn-action'>";
    echo "🔧 Create/Reset User Resepsionis (password: password)";
    echo "</button>";
    echo "</form>";
    
    // Link kembali
    echo "<hr>";
    echo "<p><a href='login.php' class='btn btn-secondary'>← Kembali ke Login</a></p>";
    ?>
</div>

</body>
</html>
