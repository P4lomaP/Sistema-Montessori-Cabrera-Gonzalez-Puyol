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

if(!empty($data->dni) && !empty($data->pin)) {
    if($usuario->solicitarDesbloqueoConPin($data->dni, $data->pin)) {
        http_response_code(200);
        echo json_encode(["mensaje" => "PIN correcto. Su solicitud fue enviada a Dirección para su aprobación."]);
    } else {
        http_response_code(401);
        echo json_encode(["mensaje" => "PIN incorrecto o expirado."]);
    }
}
?>