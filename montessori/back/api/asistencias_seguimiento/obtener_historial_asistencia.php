<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../helpers/auth.php';
include_once '../../config/Database.php';
include_once '../../models/Asistencia.php';

$database = new Database();
$db = $database->getConnection();
$asistencia = new Asistencia($db);

if(isset($_GET['id_matricula'])) {
    
    $stmt = $asistencia->obtenerHistorialPorAlumno($_GET['id_matricula']);
    $historial = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $historial[] = [
            "fecha" => $row['fecha'],
            "estado" => $row['estado'] 
        ];
    }

    http_response_code(200);
    echo json_encode([
        "mensaje" => "Historial de asistencia obtenido.",
        "datos" => $historial
    ]);
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Falta el parámetro id_matricula."]);
}
?>