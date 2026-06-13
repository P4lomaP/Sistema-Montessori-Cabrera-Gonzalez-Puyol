<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reporte_sigef_' . date("Y-m-d") . '.csv');

include_once '../../config/Database.php';
include_once '../../models/Comedor.php';

$db = (new Database())->getConnection();
$comedor = new Comedor($db);

$fecha_inicio = isset($_GET['inicio']) ? $_GET['inicio'] : date("Y-m-01");
$fecha_fin = isset($_GET['fin']) ? $_GET['fin'] : date("Y-m-d");

$stmt = $comedor->obtenerHistorial($fecha_inicio, $fecha_fin);

$salida = fopen('php://output', 'w');

fputcsv($salida, ['Fecha', 'Tipo de Servicio', 'Total Raciones Consumidas']);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($salida, [$row['fecha'], $row['tipo_servicio'], $row['total_asistencias']]);
}

fclose($salida);
?>