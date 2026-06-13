<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../helpers/auth.php';
include_once '../../config/Database.php';
include_once '../../models/Incidente.php';

$db = (new Database())->getConnection();
$incidente = new Incidente($db);
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_alumno) && !empty($data->id_usuario) && !empty($data->tipo_intervencion) && !empty($data->descripcion)) {

    if($incidente->registrarIntervencion($data->id_alumno, $data->id_usuario, $data->tipo_intervencion, $data->descripcion)) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Protocolo de intervención registrado en el legajo del alumno."]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "Error al guardar la intervención."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos. Especifique alumno, usuario, tipo y descripción."]);
}
?>