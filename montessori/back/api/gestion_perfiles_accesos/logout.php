<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../../config/Database.php';
include_once '../../models/Usuario.php'; 
include_once '../helpers/auth.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->token)) {
    
    if($usuario->cerrarSesion($data->token)) {
        http_response_code(200);
        echo json_encode(["mensaje" => "Sesión cerrada correctamente. Token invalidado."]);
    } else {
        http_response_code(400); 
        echo json_encode(["mensaje" => "Token inválido o la sesión ya estaba cerrada."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Falta enviar el token para cerrar sesión."]);
}
?>