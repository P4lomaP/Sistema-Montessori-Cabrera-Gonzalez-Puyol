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

if(!empty($data->dni) && !empty($data->nombre) && !empty($data->apellido) && !empty($data->password)) {
    
    $usuario->dni = $data->dni;
    $usuario->nombre = $data->nombre;
    $usuario->apellido = $data->apellido;
    
    if($usuario->registrar($data->password)) {
        http_response_code(201); // 201 = Creado
        echo json_encode(["mensaje" => "Registro exitoso. Pendiente de aprobación por Dirección."]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "No se pudo registrar el usuario."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Datos incompletos para el registro."]);
}
?>