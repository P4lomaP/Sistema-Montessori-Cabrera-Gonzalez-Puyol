<?php
include_once __DIR__ . '/../../config/Database.php';

$headers = apache_request_headers();
$token = '';

if (isset($headers['Authorization'])) {
    $token = str_replace("Bearer ", "", $headers['Authorization']);
}

if(empty($token)) {
    http_response_code(401);
    echo json_encode(["mensaje" => "Acceso denegado. Se requiere iniciar sesión."]);
    exit(); 
}

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id_usuario FROM usuarios WHERE token_sesion = :token AND token_expiracion > NOW() LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":token", $token);
$stmt->execute();

if($stmt->rowCount() == 0) {
    http_response_code(401);
    echo json_encode(["mensaje" => "Sesión inválida o expirada. Vuelva a loguearse."]);
    exit();
}

?>