<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireRole('admin');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    header('Location: ' . BASE_URL . '/modules/paket/list.php');
    exit();
}

// Delete paket dan notifikasinya
$stmt = $conn->prepare("DELETE FROM notifikasi WHERE paket_id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM paket WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

header('Location: ' . BASE_URL . '/modules/paket/list.php?msg=deleted');
exit();
?>
