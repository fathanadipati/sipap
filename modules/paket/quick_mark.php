<?php
/**
 * API untuk quick-mark paket sebagai diambil
 * Menggunakan AJAX dari list atau dashboard
 */

// Set proper headers before any output
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

// Initialize response
$response = [
    'success' => false,
    'message' => 'Unknown error'
];

try {
    // Check role
    if (!($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'receptionist')) {
        throw new Exception('Access denied');
    }
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id === 0) {
        throw new Exception('ID paket tidak valid');
    }
    
    // Get current paket
    $query = "SELECT id, status, tanggal_diambil FROM paket WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    
    $stmt->bind_param('i', $id);
    
    if (!$stmt->execute()) {
        throw new Exception('Database execute error: ' . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Paket tidak ditemukan');
    }
    
    $paket = $result->fetch_assoc();
    
    // Jika sudah diambil, jangan ubah lagi
    if ($paket['status'] === 'diambil') {
        throw new Exception('Paket sudah ditandai sebagai Diambil sebelumnya');
    }
    
    // Update status
    $new_status = 'diambil';
    $update_query = "UPDATE paket SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    
    if (!$update_stmt) {
        throw new Exception('Update prepare error: ' . $conn->error);
    }
    
    $update_stmt->bind_param('si', $new_status, $id);
    
    if (!$update_stmt->execute()) {
        throw new Exception('Update execute error: ' . $update_stmt->error);
    }
    
    // Set tanggal_diambil
    $time_query = "UPDATE paket SET tanggal_diambil = NOW() WHERE id = ?";
    $time_stmt = $conn->prepare($time_query);
    
    if (!$time_stmt) {
        throw new Exception('Time update prepare error: ' . $conn->error);
    }
    
    $time_stmt->bind_param('i', $id);
    $time_stmt->execute(); // Jika gagal, tetap lanjutkan
    
    $response = [
        'success' => true,
        'message' => '✓ Paket telah ditandai sebagai Diambil',
        'new_status' => $new_status
    ];
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ];
}

// Output JSON
echo json_encode($response);
exit;
?>
