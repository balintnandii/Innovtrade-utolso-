<?php
include 'db.php';

$sql = "SELECT v.auto_nev, v.szoveg, u.felhasznalonev 
        FROM velemenyek v 
        JOIN users u ON v.felhasznalo_id = u.id 
        ORDER BY v.letrehozas_datum DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>{$row['felhasznalonev']} ({$row['auto_nev']}):</strong> {$row['szoveg']}</p>";
    }
} else {
    echo "<p>Még nincs vélemény. Légy te az első!</p>";
}
$conn->close();
?>
