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

$umbral = isset($_GET['umbral']) ? (int)$_GET['umbral'] : 15;

$stmt = $asistencia->obtenerAlertasDashboard($umbral);
$alertas = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $alertas[] = [
        "id_alumno" => $row['alumnos_id_alumno'],
        "alumno" => $row['apellido'] . ", " . $row['nombre'],
        "curso" => $row['anio_grado'] . " " . $row['division'],
        "total_faltas" => (int) $row['total_faltas'],
        "estado" => "Riesgo de Abandono"
    ];
}

http_response_code(200);
echo json_encode([
    "mensaje" => "Alertas de ausentismo calculadas exitosamente.",
    "umbral_critico" => $umbral,
    "datos" => $alertas
]);
?>