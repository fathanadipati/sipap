<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

header('Content-Type: application/json');

try {
    // Query untuk mendapatkan nomor loker yang sudah ditempati (status disimpan)
    $query = "SELECT DISTINCT nomor_loker FROM paket 
              WHERE status = 'disimpan' 
              AND nomor_loker != 'WAREHOUSE' 
              AND nomor_loker IS NOT NULL
              ORDER BY nomor_loker";
    
    $result = $conn->query($query);
    
    $occupied_lokers = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $occupied_lokers[] = (int)$row['nomor_loker'];
        }
    }
    
    echo json_encode([
        'success' => true,
        'occupied_lokers' => $occupied_lokers
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
