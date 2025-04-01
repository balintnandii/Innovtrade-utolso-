<?php
// db.php
$szolgaltato = "localhost";
$felhasznaloNev = "root";
$jelszo = "";
$adatbazisNev = "innovtrade"; // Az adatbázis neve (az SQL script alapján hozd létre)
$kapcsolat = new mysqli($szolgaltato, $felhasznaloNev, $jelszo, $adatbazisNev);
if ($kapcsolat->connect_error) {
    die("Kapcsolódási hiba: " . $kapcsolat->connect_error);
}
?>
