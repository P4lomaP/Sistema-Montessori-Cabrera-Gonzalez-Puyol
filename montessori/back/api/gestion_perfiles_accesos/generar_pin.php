<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once '../../config/Database.php';
include_once '../../models/Usuario.php'; 

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->dni)) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "Falta el DNI del usuario."
    ]);
    exit;
}

$query = "SELECT correo FROM usuarios WHERE dni = :dni LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":dni", $data->dni);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    http_response_code(404);
    echo json_encode([
        "mensaje" => "El DNI ingresado no se encuentra registrado en el establecimiento."
    ]);
    exit;
}

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$correoReal = trim($row['correo']);

if (!filter_var($correoReal, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "El usuario no tiene un correo electrónico válido registrado."
    ]);
    exit;
}

if (!$usuario->generarPinRecuperacion($data->dni)) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "No se pudo generar el PIN. Verifique que la cuenta esté bloqueada."
    ]);
    exit;
}

$mail = new PHPMailer(true);

try {

    // Configuración SMTP Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    $mail->Username   = 'sistema.montessori.arg@gmail.com';

    // CAMBIAR SI GENERAN UNA NUEVA CLAVE DE APLICACIÓN
    $mail->Password   = 'zzju nbgm jqbb vfcp';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // Remitente
    $mail->setFrom(
        'sistema.montessori.arg@gmail.com',
        'Sistema Montessori'
    );

    // Destinatario
    $mail->addAddress($correoReal);

    // Contenido
    $mail->isHTML(true);

    $mail->Subject = 'PIN de Recuperación - Sistema Montessori';

    $mail->Body = "
<div style='font-family: Arial, sans-serif; background:#f1f5f9; padding:40px 12px; margin:0;'>
    <div style='max-width:520px; margin:auto; background:#ffffff; border-radius:24px; overflow:hidden; border:1px solid #dbeafe; box-shadow:0 18px 45px rgba(30,64,175,0.12);'>

        <div style='background:linear-gradient(135deg,#1d4ed8,#0284c7,#06b6d4); padding:38px 24px; text-align:center;'>
            <div style='width:70px; height:70px; margin:auto; background:rgba(255,255,255,0.18); border-radius:22px; display:flex; align-items:center; justify-content:center; font-size:34px;'>
                🎓
            </div>

            <h1 style='margin:18px 0 4px 0; color:#ffffff; font-size:26px; letter-spacing:2px; font-weight:800;'>
                MONTESSORI
            </h1>

            <p style='margin:0; color:#dbeafe; font-size:12px; letter-spacing:2px; text-transform:uppercase; font-weight:700;'>
                Portal Institucional Educativo
            </p>
        </div>

        <div style='padding:38px 34px; text-align:center;'>

            <div style='width:58px; height:58px; margin:auto; background:#eff6ff; border-radius:18px; display:flex; align-items:center; justify-content:center; font-size:28px;'>
                🔐
            </div>

            <h2 style='color:#0f172a; font-size:22px; margin:18px 0 8px 0;'>
                Recuperación de contraseña
            </h2>

            <p style='color:#475569; font-size:14px; line-height:1.6; margin:0;'>
                Se solicitó un código de verificación para recuperar el acceso a su cuenta en el Sistema Montessori.
            </p>

            <div style='margin:30px 0;'>
                <p style='font-size:11px; color:#64748b; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; margin-bottom:10px;'>
                    Código PIN
                </p>

                <div style='display:inline-block; background:#eff6ff; border:2px dashed #93c5fd; border-radius:18px; padding:18px 30px;'>
                    <span style='font-family:monospace; color:#1d4ed8; font-size:40px; font-weight:800; letter-spacing:8px;'>
                        {$usuario->pin_recuperacion}
                    </span>
                </div>
            </div>

            <div style='background:#f8fafc; border-left:5px solid #1d4ed8; border-radius:14px; padding:16px 18px; text-align:left;'>
                <p style='margin:0; color:#334155; font-size:13px; line-height:1.6;'>
                    <strong>Importante:</strong><br>
                    No comparta este código con nadie. Si usted no solicitó esta recuperación, puede ignorar este correo.
                </p>
            </div>

            <p style='font-size:11px; color:#94a3b8; margin-top:28px;'>
                Este mensaje fue generado automáticamente por el Sistema Montessori.
            </p>

        </div>
    </div>
</div>
";

    $mail->AltBody =
        "Su PIN de recuperación Montessori es: "
        . $usuario->pin_recuperacion;

    $mail->send();

    // Ocultar correo
    $partes = explode("@", $correoReal);

    $oculto =
        substr($partes[0], 0, 2)
        . str_repeat("*", max(1, strlen($partes[0]) - 2));

    $correoOfuscado = $oculto . "@" . $partes[1];

    http_response_code(200);

    echo json_encode([
        "mensaje" => "PIN generado y enviado exitosamente.",
        "correo_destino" => $correoOfuscado
    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        "mensaje" => "El PIN se generó pero hubo un error al enviar el correo.",
        "error" => $mail->ErrorInfo
    ]);
}
?>