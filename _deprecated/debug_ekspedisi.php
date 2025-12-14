<?php
$conn = new mysqli('localhost', 'root', '', 'sipap_db');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$result = $conn->query('SELECT id, nomor_paket, nama_ekspedisi FROM paket ORDER BY id DESC LIMIT 10');

echo "Recent Pakets:\n";
echo str_repeat("=", 60) . "\n";

while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Nomor: " . $row['nomor_paket'] . " | Ekspedisi: '" . $row['nama_ekspedisi'] . "'\n";
}

$conn->close();
?>
