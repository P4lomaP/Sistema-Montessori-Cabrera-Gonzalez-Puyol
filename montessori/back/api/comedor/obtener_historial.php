<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../helpers/auth.php';
include_once '../../config/Database.php';
include_once '../../models/Comedor.php';

$database = new Database();
$db = $database->getConnection();
$comedor = new Comedor($db);

$fecha_inicio = isset($_GET['inicio']) ? $_GET['inicio'] : date("Y-m-01"); 
$fecha_fin = isset($_GET['fin']) ? $_GET['fin'] : date("Y-m-d"); 

$stmt = $comedor->obtenerHistorial($fecha_inicio, $fecha_fin);
$historial = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $historial[] = [
        "fecha" => $row['fecha'],
        "tipo_servicio" => $row['tipo_servicio'],
        "total_asistencias" => (int) $row['total_asistencias']
    ];
}

http_response_code(200);
echo json_encode([
    "mensaje" => "Historial de consumo.",
    "periodo" => ["inicio" => $fecha_inicio, "fin" => $fecha_fin],
    "datos" => $historial
]);
?>