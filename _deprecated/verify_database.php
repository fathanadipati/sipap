<?php
/**
 * Script Verifikasi dan Perbaikan Database
 * Gunakan script ini jika login tidak berhasil
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';

$messages = [];
$errors = [];

// 1. Cek koneksi database
echo "<h2>🔍 VERIFIKASI DATABASE SIPAP</h2>";
echo "<hr>";

if ($conn->connect_error) {
    $errors[] = "❌ Koneksi database gagal: " . $conn->connect_error;
} else {
    $messages[] = "✅ Koneksi database berhasil";
}

// 2. Cek database sipap_db
$db_check = $conn->query("SELECT DATABASE()");
$db_result = $db_check->fetch_assoc();

if ($db_result['DATABASE()'] === 'sipap_db') {
    $messages[] = "✅ Database 'sipap_db' aktif";
} else {
    $errors[] = "❌ Database 'sipap_db' tidak aktif atau tidak ditemukan";
}

// 3. Cek tabel users
$tables_check = $conn->query("SHOW TABLES LIKE 'users'");
if ($tables_check->num_rows > 0) {
    $messages[] = "✅ Tabel 'users' ada";
} else {
    $errors[] = "❌ Tabel 'users' tidak ditemukan - database belum di-import!";
}

// 4. Cek user admin di database
$user_check = $conn->query("SELECT id, username, password, role FROM users WHERE username='admin'");
if ($user_check && $user_check->num_rows > 0) {
    $admin = $user_check->fetch_assoc();
    $messages[] = "✅ User 'admin' ditemukan (ID: " . $admin['id'] . ", Role: " . $admin['role'] . ")";
    
    // Cek password hash
    $test_password = 'password';
    if (password_verify($test_password, $admin['password'])) {
        $messages[] = "✅ Password hash untuk 'admin' valid (password: password)";
    } else {
        $errors[] = "❌ Password hash tidak sesuai - perlu di-reset!";
    }
} else {
    $errors[] = "❌ User 'admin' tidak ditemukan di tabel users";
}

// 5. Cek user resepsionis
$resp_check = $conn->query("SELECT id, username, password, role FROM users WHERE username='resepsionis'");
if ($resp_check && $resp_check->num_rows > 0) {
    $resp = $resp_check->fetch_assoc();
    $messages[] = "✅ User 'resepsionis' ditemukan (ID: " . $resp['id'] . ", Role: " . $resp['role'] . ")";
} else {
    $errors[] = "❌ User 'resepsionis' tidak ditemukan";
}

// Tampilkan hasil
echo "<div style='margin: 20px; font-family: Arial; line-height: 1.8;'>";

if (!empty($messages)) {
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin-bottom: 10px;'>";
    echo "<strong>✅ Status Baik:</strong><br>";
    foreach ($messages as $msg) {
        echo $msg . "<br>";
    }
    echo "</div>";
}

if (!empty($errors)) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin-bottom: 10px;'>";
    echo "<strong>❌ Masalah Ditemukan:</strong><br>";
    foreach ($errors as $err) {
        echo $err . "<br>";
    }
    echo "</div>";
    
    // Tawarkan solusi
    echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px;'>";
    echo "<strong>💡 Solusi:</strong><br>";
    echo "Klik tombol di bawah untuk memperbaiki database secara otomatis<br>";
    echo "</div>";
}

// Form untuk reset database
echo "<div style='margin-top: 20px;'>";
if (!empty($errors)) {
    echo "<h3>🔧 Perbaikan Otomatis</h3>";
    
    if (isset($_POST['action']) && $_POST['action'] === 'create_admin') {
        // Update/Create admin user
        $hashed_password = password_hash('password', PASSWORD_BCRYPT);
        
        // Cek apakah admin sudah ada
        $check = $conn->query("SELECT id FROM users WHERE username='admin'");
        
        if ($check->num_rows > 0) {
            // Update
            $update_sql = "UPDATE users SET password='$hashed_password', is_active=1 WHERE username='admin'";
            if ($conn->query($update_sql)) {
                echo "<p style='color: green;'><strong>✅ Password admin berhasil di-reset menjadi: password</strong></p>";
            } else {
                echo "<p style='color: red;'><strong>❌ Error: " . $conn->error . "</strong></p>";
            }
        } else {
            // Insert
            $insert_sql = "INSERT INTO users (username, email, password, role, nama_lengkap, is_active) 
                          VALUES ('admin', 'admin@sipap.local', '$hashed_password', 'admin', 'Administrator', 1)";
            if ($conn->query($insert_sql)) {
                echo "<p style='color: green;'><strong>✅ User admin berhasil dibuat dengan password: password</strong></p>";
            } else {
                echo "<p style='color: red;'><strong>❌ Error: " . $conn->error . "</strong></p>";
            }
        }
        
        // Refresh page
        echo "<script>setTimeout(() => location.reload(), 2000);</script>";
    }
    
    // Tombol reset
    if (strpos(implode('', $errors), 'Password hash tidak sesuai') !== false || 
        strpos(implode('', $errors), 'User \'admin\' tidak ditemukan') !== false) {
        echo "<form method='POST'>";
        echo "<input type='hidden' name='action' value='create_admin'>";
        echo "<button type='submit' style='padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;'>";
        echo "🔧 Reset Password Admin ke 'password'</button>";
        echo "</form>";
    }
}

// Cek koneksi database
if (!$conn->connect_error && strpos(implode('', $errors), 'Database') === false && strpos(implode('', $errors), 'Tabel') === false) {
    echo "<h3>📊 Data Users Saat Ini</h3>";
    $all_users = $conn->query("SELECT id, username, email, role, is_active FROM users ORDER BY id");
    
    if ($all_users->num_rows > 0) {
        echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>";
        echo "<tr style='background: #e9ecef;'><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Aktif</th></tr>";
        
        while ($row = $all_users->fetch_assoc()) {
            $active = $row['is_active'] ? '✅ Ya' : '❌ Tidak';
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['username']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['role']}</td>";
            echo "<td>{$active}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
}

echo "</div>";

// Link kembali
echo "<hr>";
echo "<p><a href='login.php' style='color: #007bff; text-decoration: none;'>← Kembali ke Login</a></p>";
echo "</div>";
?>
