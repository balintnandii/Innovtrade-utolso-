<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['szamla_fajl']) || !file_exists($_SESSION['szamla_fajl'])) {
    die("❌ Hiba: hiányzó e-mail cím vagy számla fájl.");
}

require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

$email = $_SESSION['email'];
$nev = isset($_SESSION['felhasznalo']) ? $_SESSION['felhasznalo'] : "Felhasználó";
$csatolmany = $_SESSION['szamla_fajl'];

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'InnovTrade1999@gmail.com';
    $mail->Password = 'rmjopiibhdwpjoyk';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);
    $mail->setFrom('InnovTrade1999@gmail.com', 'InnovTrade');
    $mail->addAddress($email, $nev);
    $mail->addAttachment($csatolmany);

    $mail->Subject = 'Foglalás visszaigazolás - Számla mellékelve';
    $mail->Body = "<h3>Kedves {$nev},</h3><p>Köszönjük foglalását. A számlát csatoltan küldjük.</p><p>Üdvözlettel,<br>InnovTrade</p>";

    $mail->send();
} catch (Exception $e) {
    echo "❌ Email küldési hiba: {$mail->ErrorInfo}";
    exit;
}
?>
