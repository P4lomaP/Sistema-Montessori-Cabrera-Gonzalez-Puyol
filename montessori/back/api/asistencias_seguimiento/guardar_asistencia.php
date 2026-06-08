<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../helpers/auth.php'; 
include_once '../../config/Database.php';
include_once '../../models/Asistencia.php';

$database = new Database();
$db = $database->getConnection();
$asistencia = new Asistencia($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->fecha) && !empty($data->id_usuario) && !empty($data->lista_asistencia)) {
    
    $errores = 0;

    foreach($data->lista_asistencia as $registro) {

        if(!$asistencia->registrar($data->fecha, $registro->estado, $registro->id_matricula, $data->id_usuario)) {
            $errores++; 
        }
    }

    if($errores == 0) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Planilla de asistencia guardada y firmada exitosamente."]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "Hubo errores al guardar algunos registros. Revise la base de datos."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos obligatorios para procesar la planilla."]);
}
?>