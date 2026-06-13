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

if(!empty($data->dni) && !empty($data->pin) && !empty($data->nueva_clave)) {

    if($usuario->solicitarDesbloqueoYCambiarClave($data->dni, $data->pin, $data->nueva_clave)) {
        http_response_code(200);
        echo json_encode(["mensaje" => "Su contraseña ha sido actualizada. La cuenta fue enviada a Dirección y está a la espera de ser desbloqueada."]);
    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "No se pudo procesar la solicitud. Verifique que el PIN sea correcto."]);
    }

} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos obligatorios (DNI, PIN o la nueva contraseña)."]);
}
?>