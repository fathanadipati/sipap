<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireLogin();

header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id === 0) {
    echo json_encode(['success' => false]);
    exit();
}

$stmt = $conn->prepare("UPDATE notifikasi SET is_read = 1 WHERE id = ? AND penghuni_id IN (SELECT id FROM penghuni WHERE user_id = ?)");
$stmt->bind_param('ii', $id, $_SESSION['user_id']);
$stmt->execute();

echo json_encode(['success' => true]);
?>
