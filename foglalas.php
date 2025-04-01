<?php
// ⚠️ Hibák megjelenítése fejlesztéshez
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $autoNevekJson = $_POST['autonev'] ?? '';
    $autokLista = json_decode($autoNevekJson, true);
    $datum = $_POST['datum'] ?? '';
    $ido = $_POST['ido'] ?? '';
    $nev = isset($_SESSION['felhasznalo']) ? $_SESSION['felhasznalo'] : "Nincs megadva";
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : "Nincs megadva";

    // Ellenőrzés: minden kötelező adat megvan?
    if (empty($autoNevekJson) || empty($datum) || empty($ido)) {
        echo "<h3 style='color: red; text-align: center;'>❌ Hiányzó adatok a foglaláshoz!</h3>";
        exit;
    }

    // Dátumellenőrzés – csak holnaptól engedélyezett
    $maiDatum = date('Y-m-d');
    if (strtotime($datum) <= strtotime($maiDatum)) {
        echo "<h3 style='color: red; text-align: center;'>❌ Csak a mai nap utánra lehet foglalni!</h3>";
        exit;
    }

    // Autók árai
    $autoArak = array(
        "Fiat 500" => 15000, "Toyota Yaris" => 18000, "Volkswagen Polo" => 17000, "Kia Picanto" => 14000,
        "Renault Clio" => 16000, "Hyundai i20" => 16500, "Seat Ibiza" => 17500, "Opel Corsa" => 15800,
        "Renault Grand Scenic" => 20000, "Ford S-Max" => 22000, "Skoda Kodiaq" => 24000, "Toyota RAV4" => 23500,
        "Honda CR-V" => 22800, "Peugeot 5008" => 23800, "Volkswagen Tiguan" => 24500, "Mazda CX-5" => 23000,
        "BMW X5" => 50000, "Tesla Model S" => 70000, "Mercedes S-Class" => 90000, "Audi A8" => 85000,
        "Lexus LS" => 88000, "Jaguar XJ" => 95000, "Range Rover" => 100000, "Porsche Panamera" => 110000,
        "Ford Mustang" => 90000, "Chevrolet Camaro" => 85000, "Porsche 911" => 120000, "Ferrari F8" => 200000,
        "Lamborghini Huracan" => 250000, "McLaren 720S" => 300000, "Audi R8" => 180000, "Nissan GT-R" => 160000
    );

    $teljesOsszeg = 0;
    $szamlaTetelek = [];

    // Már lefoglalt autók lekérdezése
    $foglalasok = [];
    $eredmenyFoglalas = $kapcsolat->query("SELECT f.datum, fa.auto_nev FROM foglalasok f JOIN foglalas_autok fa ON f.id = fa.foglalas_id");
    if ($eredmenyFoglalas && $eredmenyFoglalas->num_rows > 0) {
        while ($sor = $eredmenyFoglalas->fetch_assoc()) {
            $foglalasok[$sor['auto_nev']][] = $sor['datum'];
        }
    }

    // Számla tételek feldolgozása
    foreach ($autokLista as $auto) {
        $autoNev = $auto['nev'];
        $napok = $auto['napok'];

        if (isset($foglalasok[$autoNev]) && in_array($datum, $foglalasok[$autoNev])) {
            echo "<h3 style='color: red; text-align: center;'>❌ A(z) {$autoNev} már le van foglalva erre a napra!</h3>";
            exit;
        }

        if (isset($autoArak[$autoNev])) {
            $netar = $autoArak[$autoNev];
            $osszeg = $netar * $napok;
            $teljesOsszeg += $osszeg;
            $szamlaTetelek[] = [
                'termek' => $autoNev,
                'darab'  => $napok,
                'netar'  => $netar,
                'osszeg' => $osszeg
            ];
        }
    }

    // Számlaadatok mentése
    $_SESSION['szamla_adatok'] = [
        'felhasznalo_nev' => $nev,
        'felhasznalo_email' => $email,
        'datum' => $datum,
        'szamla_sorszam' => "INV" . date("Ymd") . rand(1000, 9999),
        'tetel_lista' => $szamlaTetelek,
        'teljes_osszeg' => $teljesOsszeg
    ];
    $_SESSION['email'] = $email;
    $_SESSION['felhasznalo'] = $nev;

    // Felhasználó ID lekérése
    $sql = "SELECT id FROM felhasznalok WHERE email = '$email'";
    $eredmeny = $kapcsolat->query($sql);

    if ($eredmeny && $eredmeny->num_rows > 0) {
        $sor = $eredmeny->fetch_assoc();
        $felhasznalo_id = $sor['id'];
    } else {
        echo "<h3 style='color: red; text-align: center;'>❌ Felhasználó nem található!</h3>";
        exit;
    }

    // Foglalás mentése
    $kapcsolat->query("INSERT INTO foglalasok (felhasznalo_id, datum, ido, osszeg)
                      VALUES ('$felhasznalo_id', '$datum', '$ido', '$teljesOsszeg')");
    $foglalas_id = $kapcsolat->insert_id;

    // Autók mentése
    foreach ($autokLista as $auto) {
        $autoNev = $auto['nev'];
        $stmt = $kapcsolat->prepare("INSERT INTO foglalas_autok (foglalas_id, auto_nev) VALUES (?, ?)");
        $stmt->bind_param("is", $foglalas_id, $autoNev);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['szamla_elkeszult'] = true;

    require_once 'pdf/pdf_generalas.php';
    require_once 'szamla_kuldes.php';

    // ⬇️ Itt irányítjuk át a köszönő oldalra
    header("Location: foglalas_koszonjuk.php");
    exit();
}
?>
