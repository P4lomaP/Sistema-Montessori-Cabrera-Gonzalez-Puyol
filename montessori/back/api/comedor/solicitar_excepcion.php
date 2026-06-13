<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../helpers/auth.php';
include_once '../../config/Database.php';
include_once '../../models/Comedor.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

$database = new Database();
$db = $database->getConnection();
$comedor = new Comedor($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_docente) && !empty($data->motivo)) {
    
    $fecha_actual = date("Y-m-d");

    if($comedor->solicitarExcepcion($data->id_docente, $fecha_actual, $data->motivo)) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Solicitud enviada a Dirección exitosamente. Aguarde a que un directivo habilite el sistema."]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "Error interno. No se pudo enviar la solicitud."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos. Se requiere el ID del docente y el motivo del atraso."]);
}
?>