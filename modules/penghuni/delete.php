<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    header('Location: ' . BASE_URL . '/modules/penghuni/list.php');
    exit();
}

// Get user_id dari penghuni
$query = "SELECT user_id FROM penghuni WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: ' . BASE_URL . '/modules/penghuni/list.php');
    exit();
}

$penghuni = $result->fetch_assoc();
$user_id = $penghuni['user_id'];

// Delete penghuni
$stmt = $conn->prepare("DELETE FROM penghuni WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

// Delete user
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();

header('Location: ' . BASE_URL . '/modules/penghuni/list.php?msg=deleted');
exit();
?>
