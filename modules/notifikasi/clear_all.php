<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('resident');

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Not POST - redirect to list
    header('Location: ' . BASE_URL . '/modules/notifikasi/list.php');
    exit();
}

// Set JSON response header
header('Content-Type: application/json');

try {
    // Get penghuni_id
    $query = "SELECT id FROM penghuni WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param('i', $_SESSION['user_id']);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $penghuni = $result->fetch_assoc();
    
    if (!$penghuni) {
        throw new Exception("Penghuni tidak ditemukan");
    }
    
    $penghuni_id = $penghuni['id'];
    
    // Mark all as read
    $update_stmt = $conn->prepare("UPDATE notifikasi SET is_read = 1 WHERE penghuni_id = ? AND is_read = 0");
    
    if (!$update_stmt) {
        throw new Exception("Prepare update failed: " . $conn->error);
    }
    
    $update_stmt->bind_param('i', $penghuni_id);
    
    if (!$update_stmt->execute()) {
        throw new Exception("Update failed: " . $update_stmt->error);
    }
    
    echo json_encode(['success' => true, 'message' => 'Semua notifikasi telah ditandai sebagai dibaca']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
