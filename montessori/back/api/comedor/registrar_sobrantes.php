<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../helpers/auth.php'; 
include_once '../../config/Database.php';
include_once '../../models/Comedor.php';

$database = new Database();
$db = $database->getConnection();
$comedor = new Comedor($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_usuario) && !empty($data->cantidad_raciones)) {
    
    $fecha_actual = date("Y-m-d");

    if($comedor->registrarSobrantes(
        $data->id_usuario, 
        $fecha_actual, 
        $data->tipo_servicio, 
        $data->cantidad_raciones, 
        $data->observacion ?? ""
    )) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Registro de sobrantes guardado con éxito. ¡Gracias por optimizar los insumos!"]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "Error al guardar los sobrantes."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos obligatorios para el registro de sobrantes."]);
}
?>