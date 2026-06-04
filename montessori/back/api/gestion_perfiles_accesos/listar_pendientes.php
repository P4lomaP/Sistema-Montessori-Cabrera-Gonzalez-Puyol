<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET"); 

include_once '../../config/Database.php';
include_once '../../models/Usuario.php'; 

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$stmt = $usuario->obtenerPendientes();
$cantidad = $stmt->rowCount();

if($cantidad > 0) {
    $pendientes_arr = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $item = array(
            "id_usuario" => $row['id_usuario'],
            "dni" => $row['dni'],
            "nombre" => $row['nombre'],
            "apellido" => $row['apellido']
        );
        array_push($pendientes_arr, $item);
    }
    
    http_response_code(200);
    echo json_encode($pendientes_arr);
} else {
   
    http_response_code(200); 
    echo json_encode(array("mensaje" => "No hay solicitudes de desbloqueo pendientes."));
}
?>