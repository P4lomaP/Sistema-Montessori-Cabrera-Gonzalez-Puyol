<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once '../../config/Database.php';
include_once '../../models/Usuario.php'; 

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->dni) && !empty($data->correo)) {
    if($usuario->generarPinRecuperacion($data->dni)) {
        
        $mail = new PHPMailer(true);
        try {

            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'sistema.montessori.arg@gmail.com';                     
            $mail->Password   = 'zzju nbgm jqbb vfcp';                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
            $mail->Port       = 465;                                    

            $mail->setFrom('sistema.montessori.arg@gmail.com', 'Sistema Montessori');
            $mail->addAddress($data->correo); 

            $mail->isHTML(true);                                  
            $mail->Subject = 'PIN de Recuperacion - Sistema Montessori';
            $mail->Body    = 'Buenas estimado/a, su cuenta ha sido bloqueada. Su PIN de recuperación es: <b>' . $usuario->pin_recuperacion . '</b>. Ingresalo en el sistema para solicitar el desbloqueo a Dirección.';
            $mail->AltBody = 'Su PIN de recuperacion es: ' . $usuario->pin_recuperacion;

            $mail->send();
            
            http_response_code(200);
            echo json_encode(["mensaje" => "PIN generado y enviado al correo exitosamente."]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["mensaje" => "El PIN se generó pero hubo un error al enviar el correo: {$mail->ErrorInfo}"]);
        }

    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "No se pudo generar el PIN. Verifique que la cuenta esté bloqueada."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Falta el DNI del usuario."]);
}
?>