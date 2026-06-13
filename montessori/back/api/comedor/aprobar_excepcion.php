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

if(!empty($data->id_excepcion) && !empty($data->id_directivo)) {
    
    if($comedor->aprobarExcepcion($data->id_excepcion, $data->id_directivo)) {
        http_response_code(200);
        echo json_encode(["mensaje" => "Excepción aprobada correctamente. El docente ya cuenta con habilitación para cargar la asistencia del comedor."]);
    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "No se pudo aprobar la excepción. Verifique que el ID de la solicitud sea correcto."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos. Se requiere el ID de la excepción y el ID del directivo que autoriza."]);
}
?>