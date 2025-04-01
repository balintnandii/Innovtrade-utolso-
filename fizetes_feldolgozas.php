<?php
session_start();

// Teszt célból elfogad minden beküldött adatot, nem történik valódi fizetésfeldolgozás
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kartyaszam = $_POST['kartyaszam'] ?? '';
    $nev = $_POST['nev'] ?? '';
    $lejarat = $_POST['lejarat'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    // Egyszerű ellenőrzés (valódi banki integráció nélkül)
    if (strlen($kartyaszam) < 12 || strlen($cvv) < 3 || empty($nev) || empty($lejarat)) {
        echo "<div style='max-width: 600px; margin: 40px auto; font-family: Arial; color: red;'>
                <h2>❌ Hiba</h2>
                <p>Hibás vagy hiányos bankkártyaadatok!</p>
                <a href='bankkartyas_oldal.php' style='color: #0066cc;'>Vissza a fizetéshez</a>
              </div>";
        exit;
    }

    // Sikeres fizetés szimulációja
    echo "<div style='max-width: 600px; margin: 40px auto; font-family: Arial; color: green;'>
            <h2>✅ Fizetés sikeres</h2>
            <p>Köszönjük, " . htmlspecialchars($nev) . "! A tranzakció feldolgozásra került.</p>
            <a href='index.php' style='color: #0066cc;'>Vissza a főoldalra</a>
          </div>";

    // Opcionálisan törölheted a kosarat és státuszokat
    unset($_SESSION['kosar']);
    unset($_SESSION['szamla_elkeszult']);
    unset($_SESSION['szamla_fajl']);

} else {
    header("Location: index.php");
    exit;
}
?>
