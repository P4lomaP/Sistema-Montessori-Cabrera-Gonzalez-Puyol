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

if(!empty($data->id_usuario) && !empty($data->id_perfil)) {
    
    $usuario->id_usuario = $data->id_usuario;
    
    if($usuario->asignarPerfil($data->id_perfil)) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Perfil asignado al usuario exitosamente."]);
    } else {
        http_response_code(200);
        echo json_encode(["mensaje" => "El usuario ya tenía este perfil asignado."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos. Ingrese id_usuario e id_perfil."]);
}
?>