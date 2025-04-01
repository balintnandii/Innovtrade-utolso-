<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../fpdf/fpdf.php';

if (!isset($_SESSION['szamla_adatok'])) {
    exit("❌ Nincsenek számla adatok.");
}

$adatok = $_SESSION['szamla_adatok'];
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->SetTitle(mb_convert_encoding('InnovTrade Számla', 'ISO-8859-2', 'UTF-8'));
$pdf->SetAuthor('InnovTrade');

// Logó középre
$logoPath = __DIR__ . '/../kepek/logo.png';
if (file_exists($logoPath)) {
    $pdf->Image($logoPath, 90, 10, 30); 
}
$pdf->Ln(30);

// Fejléc
$pdf->SetFillColor(52, 152, 219);
$pdf->SetTextColor(255);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 12, mb_convert_encoding('InnovTrade Számla', 'ISO-8859-2', 'UTF-8'), 0, 1, 'C', true);
$pdf->Ln(5);

// Ügyféladatok
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, mb_convert_encoding('Név: ' . $adatok['felhasznalo_nev'], 'ISO-8859-2', 'UTF-8'), 0, 1);
$pdf->Cell(0, 8, mb_convert_encoding('Email: ' . $adatok['felhasznalo_email'], 'ISO-8859-2', 'UTF-8'), 0, 1);
$pdf->Cell(0, 8, mb_convert_encoding('Dátum: ' . $adatok['datum'], 'ISO-8859-2', 'UTF-8'), 0, 1);
$pdf->Cell(0, 8, mb_convert_encoding('Számlaszám: ' . $adatok['szamla_sorszam'], 'ISO-8859-2', 'UTF-8'), 0, 1);
$pdf->Ln(6);

// Táblázat fejléce
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(60, 10, mb_convert_encoding('Autó', 'ISO-8859-2', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell(30, 10, mb_convert_encoding('Napok', 'ISO-8859-2', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell(40, 10, mb_convert_encoding('Napidíj (Ft)', 'ISO-8859-2', 'UTF-8'), 1, 0, 'C', true);
$pdf->Cell(50, 10, mb_convert_encoding('Összesen (Ft)', 'ISO-8859-2', 'UTF-8'), 1, 1, 'C', true);

// Tartalom
$pdf->SetFont('Arial', '', 10);
foreach ($adatok['tetel_lista'] as $tetel) {
    $pdf->Cell(60, 10, mb_convert_encoding($tetel['termek'], 'ISO-8859-2', 'UTF-8'), 1);
    $pdf->Cell(30, 10, $tetel['darab'], 1, 0, 'C');
    $pdf->Cell(40, 10, number_format($tetel['netar'], 0, ',', ' '), 1, 0, 'R');
    $pdf->Cell(50, 10, number_format($tetel['osszeg'], 0, ',', ' '), 1, 1, 'R');
}

$pdf->Ln(6);

// Végösszeg
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, mb_convert_encoding('Végösszeg: ' . number_format($adatok['teljes_osszeg'], 0, ',', ' ') . ' Ft', 'ISO-8859-2', 'UTF-8'), 0, 1, 'R');
$pdf->Ln(10);

// Céges adatok
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 6, mb_convert_encoding("InnovTrade Kft.\nEmail: info@innovtrade.hu\nAdószám: 12345678-1-42\nSzékhely: 1111 Budapest, Fő utca 1.\nTelefonszám: +36 1 234 5678\nBankszámlaszám: 11700000-12345678", 'ISO-8859-2', 'UTF-8'));
$pdf->Ln(8);

$pdf->MultiCell(0, 6, mb_convert_encoding("Köszönjük, hogy az InnovTrade szolgáltatását választotta!\nA számla elektronikus formátumban érvényes, és kérjük, őrizze meg.\nÜgyfélszolgálatunk 0-24 elérhető a fenti elérhetőségeken.", 'ISO-8859-2', 'UTF-8'));

// QR-kód generálása oldalra visszairányításhoz
$qr_content = urlencode("https://localhost/Rózsamarcell/InnovTrade/index.php");
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . $qr_content;
$qr_image_path = __DIR__ . "/qr_temp.png";
file_put_contents($qr_image_path, file_get_contents($qr_url));
if (file_exists($qr_image_path)) {
    $pdf->Image($qr_image_path, 90, $pdf->GetY() + 10, 30);
}

// Mentés
$filename = __DIR__ . "/szamla_" . md5($adatok['felhasznalo_email']) . ".pdf";
$pdf->Output('F', $filename);
$_SESSION['szamla_fajl'] = $filename;

// QR törlése
if (file_exists($qr_image_path)) {
    unlink($qr_image_path);
}
?>
