<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../../config/Database.php';
include_once '../../models/Perfil.php'; 
include_once '../helpers/auth.php';

$database = new Database();
$db = $database->getConnection();
$perfil = new Perfil($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_perfil) && !empty($data->id_permiso)) {
    
    if($perfil->asignarPermiso($data->id_perfil, $data->id_permiso)) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Permiso asignado al perfil exitosamente."]);
    } else {
        http_response_code(200); 
        echo json_encode(["mensaje" => "El perfil ya tenía este permiso asignado o hubo un error."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos. Ingrese id_perfil e id_permiso."]);
}
?>