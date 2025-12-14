<?php
/**
 * API untuk mendapatkan statistik paket terbaru
 * Digunakan untuk auto-refresh dashboard paket
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';

requireLogin();

header('Content-Type: application/json');

$role = $_SESSION['role'];
// Normalize old role names to new ones
if ($role === 'resepsionis') $role = 'receptionist';
if ($role === 'penghuni') $role = 'resident';

$user_id = $_SESSION['user_id'];

$response = [];

try {
    if ($role === 'receptionist') {
        // Statistik untuk Receptionist
        $paket_loker = $conn->query("SELECT COUNT(*) as total FROM paket WHERE status = 'disimpan'")->fetch_assoc()['total'];
        $paket_diambil_hari = $conn->query("SELECT COUNT(*) as total FROM paket WHERE status = 'diambil' AND DATE(tanggal_diambil) = CURDATE()")->fetch_assoc()['total'];
        $total_paket = $conn->query("SELECT COUNT(*) as total FROM paket")->fetch_assoc()['total'];
        
        $response = [
            'success' => true,
            'paket_loker' => $paket_loker,
            'paket_diambil_hari' => $paket_diambil_hari,
            'total_paket' => $total_paket
        ];
        
    } elseif ($role === 'admin') {
        // Statistik untuk Admin
        $total_penghuni = $conn->query("SELECT COUNT(*) as total FROM penghuni")->fetch_assoc()['total'];
        $total_paket = $conn->query("SELECT COUNT(*) as total FROM paket")->fetch_assoc()['total'];
        $paket_loker = $conn->query("SELECT COUNT(*) as total FROM paket WHERE status = 'disimpan'")->fetch_assoc()['total'];
        $total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
        
        $response = [
            'success' => true,
            'total_penghuni' => $total_penghuni,
            'total_paket' => $total_paket,
            'paket_loker' => $paket_loker,
            'total_users' => $total_users
        ];
        
    } elseif ($role === 'resident') {
        // Statistik untuk Resident
        $penghuni = $conn->query("SELECT id FROM penghuni WHERE user_id = $user_id")->fetch_assoc();
        $penghuni_id = $penghuni['id'] ?? null;
        
        if ($penghuni_id) {
            $paket_menunggu = $conn->query("SELECT COUNT(*) as total FROM paket WHERE penghuni_id = $penghuni_id AND status IN ('diterima', 'disimpan')")->fetch_assoc()['total'];
            $paket_diambil = $conn->query("SELECT COUNT(*) as total FROM paket WHERE penghuni_id = $penghuni_id AND status = 'diambil'")->fetch_assoc()['total'];
            
            $response = [
                'success' => true,
                'paket_menunggu' => $paket_menunggu,
                'paket_diambil' => $paket_diambil
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Data penghuni tidak ditemukan'
            ];
        }
    }
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
?>
