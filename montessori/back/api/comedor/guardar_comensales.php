<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../helpers/auth.php';
include_once '../../config/Database.php';
include_once '../../models/Comedor.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');

$database = new Database();
$db = $database->getConnection();
$comedor = new Comedor($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id_usuario) && !empty($data->tipo_servicio) && isset($data->alumnos)) {

    $hora_actual = date("H:i");
    $hora_limite = "08:00";
    $fecha_actual = date("Y-m-d");
    $hora_registro_completa = date("Y-m-d H:i:s");

    if ($hora_actual > $hora_limite) {
        http_response_code(403); 
        echo json_encode(["mensaje" => "Horario de carga finalizado. El sistema se bloquea a las 08:00 AM para garantizar la logística de la cocina."]);
        exit(); 
    }

    $exitos = 0;
    $errores = 0;

    foreach($data->alumnos as $alumno) {
        if($comedor->registrarAsistencia(
            $alumno->id_matricula, 
            $data->id_usuario, 
            $fecha_actual, 
            $data->tipo_servicio, 
            $alumno->asiste, 
            $hora_registro_completa
        )) {
            $exitos++;
        } else {
            $errores++;
        }
    }

    http_response_code(201);
    echo json_encode([
        "mensaje" => "Carga de comensales procesada exitosamente.",
        "registros_exitosos" => $exitos,
        "errores" => $errores
    ]);

} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Datos incompletos. Se requiere ID de usuario, tipo de servicio y la lista de alumnos."]);
}
?>