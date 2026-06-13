<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../helpers/auth.php';
include_once '../../config/Database.php';
include_once '../../models/Comedor.php';

$db = (new Database())->getConnection();
$comedor = new Comedor($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->fecha) && !empty($data->tipo_servicio) && !empty($data->descripcion)) {
    if($comedor->guardarMenu($data->fecha, $data->tipo_servicio, $data->descripcion)) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Menú institucional guardado correctamente."]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "Error al guardar el menú."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos para registrar el menú."]);
}
?>