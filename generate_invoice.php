<?php
session_start();

require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/fpdf/fpdf.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// === 1. ALAPADATOK === //
$felhasznaloNev = $_SESSION['felhasznalo'] ?? 'Ismeretlen felhasználó';
$felhasznaloEmail = $_SESSION['email'] ?? 'mrclrozsa@gmail.com';
$datum = date('Y-m-d');
$szamlaSzam = "INV" . date("Ymd") . rand(1000, 9999);

// === 2. TERMÉK LISTA (teszthez fixen) === //
$termekek = [
    ['termek' => 'Audi R8', 'ora' => 3, 'ar' => 180000]
];
$osszeg = 0;

// === 3. PDF GENERÁLÁS === //
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Fejléc
$pdf->SetTextColor(0, 0, 128);
$pdf->Cell(0, 10, 'InnovTrade Számla', 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(8);

// Számlainformációk
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, "Számlaszám: $szamlaSzam", 0, 1);
$pdf->Cell(0, 8, "Dátum: $datum", 0, 1);
$pdf->Cell(0, 8, "Ügyfél neve: $felhasznaloNev", 0, 1);
$pdf->Cell(0, 8, "Email: $felhasznaloEmail", 0, 1);
$pdf->Ln(10);

// Táblázat fejléc
$pdf->SetFillColor(200, 220, 255);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Autó', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Óra', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Óradíj (Ft)', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Összesen (Ft)', 1, 1, 'C', true);

// Tartalom
$pdf->SetFont('Arial', '', 12);
foreach ($termekek as $tetel) {
    $auto = $tetel['termek'];
    $ora = $tetel['ora'];
    $ar = $tetel['ar'];
    $osszeg += $ora * $ar;

    $pdf->Cell(60, 10, $auto, 1);
    $pdf->Cell(30, 10, $ora, 1, 0, 'C');
    $pdf->Cell(50, 10, number_format($ar, 0, ',', ' '), 1, 0, 'R');
    $pdf->Cell(50, 10, number_format($ora * $ar, 0, ',', ' '), 1, 1, 'R');
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(140, 10, 'Végösszeg:', 1);
$pdf->Cell(50, 10, number_format($osszeg, 0, ',', ' ') . ' Ft', 1, 1, 'R');

// === 4. PDF MENTÉSE === //
$pdfPath = __DIR__ . '/szamla_' . md5($felhasznaloEmail) . '.pdf';
$pdf->Output('F', $pdfPath);

// === 5. E-MAIL KÜLDÉS === //
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'InnovTrade1999@gmail.com';
    $mail->Password = 'rmjopiibhdwpjoyk'; // Gmail app password
    $mail->SMTPSecure = 'ssl'; // Régi PHPMailer támogatás
    $mail->Port = 465;

    $mail->CharSet = 'UTF-8';
    $mail->setFrom('InnovTrade1999@gmail.com', 'InnovTrade');
    $mail->addAddress($felhasznaloEmail, $felhasznaloNev);
    $mail->addAttachment($pdfPath);

    $mail->isHTML(true);
    $mail->Subject = 'Foglalás visszaigazolás - Számla mellékelve';
    $mail->Body = "<h3>Kedves {$felhasznaloNev},</h3><p>Köszönjük a foglalást. A számlát mellékelve küldjük.</p><p>Üdvözlettel,<br><strong>InnovTrade</strong></p>";

    $mail->send();
}
//     // === 6. HTML ÜZENET A FELHASZNÁLÓNAK === //
//     echo "<h2 style='font-family: sans-serif; color: green; text-align: center; margin-top: 50px;'>✅ A számlát e-mailben elküldtük a(z) <strong>$felhasznaloEmail</strong> címre.</h2>";
// } catch (Exception $e) {
//     echo "<p style='color: red;'>❌ Hiba az e-mail küldésekor: " . $mail->ErrorInfo . "</p>";
//}
?>
