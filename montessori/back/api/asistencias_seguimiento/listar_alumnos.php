<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../helpers/auth.php';
include_once '../../config/Database.php';
include_once '../../models/Alumno.php';

$database = new Database();
$db = $database->getConnection();
$alumno = new Alumno($db);

$stmt = $alumno->listarActivos();
$num = $stmt->rowCount();

if($num > 0) {
    $alumnos_arr = array();
    $alumnos_arr["alumnos"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $alumno_item = array(
            "id_alumno" => $id_alumno,
            "dni" => $dni,
            "nombre" => $nombre,
            "apellido" => $apellido
        );
        array_push($alumnos_arr["alumnos"], $alumno_item);
    }

    http_response_code(200);
    echo json_encode($alumnos_arr);
} else {
    http_response_code(404);
    echo json_encode(array("mensaje" => "No se encontraron alumnos activos."));
}
?>