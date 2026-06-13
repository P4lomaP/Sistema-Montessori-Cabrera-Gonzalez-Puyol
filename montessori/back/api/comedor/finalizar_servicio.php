<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../helpers/auth.php'; // Siempre pasamos por el patovica
include_once '../../config/Database.php';
include_once '../../models/Comedor.php';

$database = new Database();
$db = $database->getConnection();
$comedor = new Comedor($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_usuario) && !empty($data->tipo_servicio) && isset($data->cantidad_sobrantes)) {
    
    $fecha_actual = date("Y-m-d");
    $cantidad = (int) $data->cantidad_sobrantes;
    $observacion = !empty($data->observacion) ? $data->observacion : "Servicio finalizado sin observaciones.";

    if($comedor->registrarSobrantes($data->id_usuario, $fecha_actual, $data->tipo_servicio, $cantidad, $observacion)) {
        http_response_code(201);
        echo json_encode([
            "mensaje" => "El servicio de " . $data->tipo_servicio . " se ha cerrado correctamente.",
            "raciones_sobrantes" => $cantidad
        ]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "Error interno. No se pudo finalizar el servicio."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos obligatorios. Indique el servicio y la cantidad de sobrantes (0 si no hubo)."]);
}
?>