<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireRole('admin');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0 || $id === $_SESSION['user_id']) {
    header('Location: ' . BASE_URL . '/admin/users.php');
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

header('Location: ' . BASE_URL . '/admin/users.php?msg=deleted');
exit();
?>
