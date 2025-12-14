<?php
$conn = new mysqli('localhost', 'root', '', 'sipap_db');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Delete all pakets where nama_ekspedisi is '0' (corrupted data)
$delete_result = $conn->query("DELETE FROM paket WHERE nama_ekspedisi = '0'");

echo "Deleted pakets dengan nama_ekspedisi='0': " . $conn->affected_rows . " rows<br><br>";

// Show remaining pakets
$result = $conn->query('SELECT id, nomor_paket, nama_ekspedisi FROM paket ORDER BY id DESC LIMIT 10');

echo "Remaining Pakets:\n";
echo str_repeat("=", 60) . "\n";

while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Nomor: " . $row['nomor_paket'] . " | Ekspedisi: '" . $row['nama_ekspedisi'] . "'\n";
}

$conn->close();
?>
