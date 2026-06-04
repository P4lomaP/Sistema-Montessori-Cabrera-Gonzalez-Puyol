<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../../config/Database.php';
include_once '../../models/Usuario.php'; 

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_usuario)) {
    $usuario->id_usuario = $data->id_usuario;
    
    if($usuario->activarCuenta()) {
        http_response_code(200);
        echo json_encode(["mensaje" => "Cuenta activada correctamente."]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "No se pudo activar la cuenta."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Falta el ID del usuario a activar."]);
}
?>