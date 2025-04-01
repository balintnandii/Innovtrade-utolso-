<?php
// regisztracio.php
session_start();  // Ne felejtsd el a session_start()-t
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $felhasznalonev = $_POST['felhasznalonev'];
    $email = $_POST['email'];
    $jelszo = $_POST['jelszo'];
    $jelszo_megerositese = $_POST['jelszo_megerositese'];

    if ($jelszo !== $jelszo_megerositese) {
        echo "A jelszavak nem egyeznek meg!";
        exit();
    }

    $titkositettJelszo = password_hash($jelszo, PASSWORD_DEFAULT);

    $sql = "INSERT INTO felhasznalok (felhasznalonev, email, jelszo) VALUES ('$felhasznalonev', '$email', '$titkositettJelszo')";
    if ($kapcsolat->query($sql) === TRUE) {
        // Regisztráció sikeres: állítsd be a session változókat
        $_SESSION['felhasznalo'] = $felhasznalonev;
        $_SESSION['email'] = $email;
        header("Location: index.php?regisztracio=1");
        exit();
    } else {
        echo "Hiba: " . $sql . "<br>" . $kapcsolat->error;
    }
}
?>



