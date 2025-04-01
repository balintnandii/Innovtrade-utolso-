<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $felhasznalonev = $_POST['felhasznalonev'];
    $jelszo = $_POST['jelszo'];

    $sql = "SELECT * FROM felhasznalok WHERE felhasznalonev='$felhasznalonev'";
    $result = $kapcsolat->query($sql);

    if ($result->num_rows > 0) {
        $sor = $result->fetch_assoc();
        if (password_verify($jelszo, $sor['jelszo'])) {
            $_SESSION['felhasznalo'] = $felhasznalonev;
            $_SESSION['email'] = $sor['email'];

            // Kosár visszatöltése
            if (!isset($_SESSION['kosar'])) {
                $_SESSION['kosar'] = [];
            }

            header("Location: index.php?bejelentkezes=1");
            exit();
        } else {
            echo "Hibás jelszó!";
        }
    } else {
        echo "Nincs ilyen felhasználó!";
    }
}
?>
