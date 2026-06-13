<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../helpers/auth.php';
include_once '../../config/Database.php';
include_once '../../models/Incidente.php';

$database = new Database();
$db = $database->getConnection();
$incidente = new Incidente($db);

if(isset($_GET['id_alumno'])) {
    
    $stmt = $incidente->listarPorAlumno($_GET['id_alumno']);
    $incidentes = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $incidentes[] = [
            "fecha" => $row['fecha_incidencia'],
            "gravedad" => $row['gravedad'], 
            "observacion" => $row['observacion']
        ];
    }

    http_response_code(200);
    echo json_encode([
        "mensaje" => "Historial de conducta obtenido.",
        "datos" => $incidentes
    ]);
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Falta el parámetro id_alumno."]);
}
?>