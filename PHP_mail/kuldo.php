<?php
use PHPMailer\PHPMailer\PHPMailer;
function VerificationSend(){

    //$email = $_POST['email'];

    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";

    $mail = new PHPMailer();

    //SMTP Settings
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "lipak101@gmail.com";
    $mail->Password = "";
    $mail->Port = 465; //587
    $mail->SMTPSecure = "ssl"; //tls

$email='lipak101@gmail.com';//ide küldi az e-mail-t

//küldendő adatok
$targy="Ez a tárgy őúóüö";
$oldal_cime="Ez az oldal címe őúóüö";
$tartalma="úőüöóúűáé";


    //Email Settings
	//$mail->charSet = "UTF-8";
    $mail->isHTML(true);
    $mail->setFrom($email, $oldal_cime);
	
    $mail->addAddress($email);

    $mail->Subject = $targy;
    $mail->Body =$tartalma;
    $mail->CharSet = 'UTF-8';  //karakterkódolás
    //$mail->send();
	if(!$mail->Send())
{
   echo "Hiba a levél küldésekor. Próbálja újra!";
   exit;
}

echo "Az üzenet sikeresen továbbítva.";
}
//levél küldése
VerificationSend();

       

?>