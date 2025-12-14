<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';

if (!isLoggedIn()) {
    die('Not logged in');
}

// Get penghuni_id
$query = "SELECT id FROM penghuni WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Not a penghuni');
}

$penghuni = $result->fetch_assoc();
$penghuni_id = $penghuni['id'];

// Get all notifikasi
$query = "SELECT n.id, n.pesan, n.is_read, n.created_at FROM notifikasi n WHERE n.penghuni_id = ? ORDER BY n.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $penghuni_id);
$stmt->execute();
$result = $stmt->get_result();

echo "Total notifikasi: " . $result->num_rows . "\n";
echo "Penghuni ID: " . $penghuni_id . "\n\n";

while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Read: " . ($row['is_read'] ? 'YES' : 'NO') . " | Pesan: " . substr($row['pesan'], 0, 50) . "\n";
}

// Get unread count
$query = "SELECT COUNT(*) as count FROM notifikasi WHERE penghuni_id = ? AND is_read = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $penghuni_id);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc();

echo "\nUnread count: " . $count['count'];
?>
