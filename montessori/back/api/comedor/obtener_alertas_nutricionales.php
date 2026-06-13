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

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date("Y-m-d");
$stmt = $comedor->obtenerAlertasNutricionales($fecha);
$alertas = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $alertas[] = [
        "alumno" => $row['apellido'] . ", " . $row['nombre'],
        "restriccion" => $row['restricciones_alimentarias'],
        "servicio" => $row['tipo_servicio']
    ];
}

http_response_code(200);
echo json_encode([
    "mensaje" => "Alertas nutricionales del día.",
    "fecha" => $fecha,
    "datos" => $alertas
]);
?>