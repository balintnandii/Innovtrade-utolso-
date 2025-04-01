<?php
session_start();

if (!isset($_SESSION['szamla_fajl']) || !file_exists($_SESSION['szamla_fajl'])) {
    echo "❌ A számla fájl nem található.";
    exit;
}

$filename = $_SESSION['szamla_fajl'];
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="szamla.pdf"');
readfile($filename);
exit;
?>
