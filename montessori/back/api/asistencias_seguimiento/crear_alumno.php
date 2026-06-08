<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../../config/Database.php';
include_once '../../models/Alumno.php';
include_once '../helpers/auth.php';

$database = new Database();
$db = $database->getConnection();
$alumno = new Alumno($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->dni) && !empty($data->nombre) && !empty($data->apellido)) {
    
    $alumno->dni = $data->dni;
    $alumno->nombre = $data->nombre;
    $alumno->apellido = $data->apellido;
    
    try {
        if($alumno->crear()) {
            http_response_code(201);
            echo json_encode(["mensaje" => "Alumno registrado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo registrar el alumno."]);
        }
    } catch(PDOException $e) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Error: El DNI ingresado ya pertenece a otro alumno."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos obligatorios (DNI, nombre, apellido)."]);
}
?>