<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../helpers/auth.php';
include_once '../../config/Database.php';
include_once '../../models/Incidente.php';

$database = new Database();
$db = $database->getConnection();
$incidente = new Incidente($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_matricula) && !empty($data->id_usuario) && !empty($data->fecha_incidencia) && !empty($data->gravedad) && !empty($data->observacion)) {
    
    if($incidente->registrar($data->id_matricula, $data->id_usuario, $data->fecha_incidencia, $data->gravedad, $data->observacion)) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Observación de conducta vinculada al legajo exitosamente."]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "Error interno. No se pudo registrar la incidencia."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos obligatorios para registrar la observación."]);
}
?>