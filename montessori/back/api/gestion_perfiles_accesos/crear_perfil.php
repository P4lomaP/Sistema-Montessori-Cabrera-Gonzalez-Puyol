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

if(!empty($data->nombre) && !empty($data->descripcion)) {
    
    $id_nuevo = $perfil->crear($data->nombre, $data->descripcion);
    
    if($id_nuevo) {
        http_response_code(201); 
        echo json_encode([
            "mensaje" => "Perfil creado exitosamente.",
            "id_perfil" => $id_nuevo
        ]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "No se pudo crear el perfil en la base de datos."]);
    }
} else {
    http_response_code(400); 
    echo json_encode(["mensaje" => "Faltan datos. Ingrese nombre y descripcion."]);
}
?>