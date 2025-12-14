<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireLogin();

header('Content-Type: application/json');

// Get penghuni_id
$query = "SELECT id FROM penghuni WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['count' => 0, 'notifikasi' => []]);
    exit();
}

$penghuni = $result->fetch_assoc();
$penghuni_id = $penghuni['id'];

// Get unread notifikasi
$query = "SELECT n.id, n.pesan, n.created_at, p.id as paket_id
         FROM notifikasi n
         JOIN paket p ON n.paket_id = p.id
         WHERE n.penghuni_id = ? AND n.is_read = 0
         ORDER BY n.created_at DESC
         LIMIT 5";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $penghuni_id);
$stmt->execute();
$result = $stmt->get_result();

$notifikasi = [];
while ($row = $result->fetch_assoc()) {
    $notifikasi[] = [
        'id' => $row['id'],
        'message' => htmlspecialchars($row['pesan']),
        'created_at' => date('d M H:i', strtotime($row['created_at'])),
        'link' => BASE_URL . '/modules/paket/view.php?id=' . $row['paket_id']
    ];
}

echo json_encode([
    'count' => $result->num_rows,
    'notifikasi' => $notifikasi
]);
?>
