<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../../config/Database.php';
include_once '../../models/Perfil.php'; 

$database = new Database();
$db = $database->getConnection();

$perfil = new Perfil($db);

$stmt = $perfil->obtenerResumen();
$cantidad = $stmt->rowCount();

if($cantidad > 0) {
    $perfiles_arr = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $item = array(
            "id_perfil" => $row['id_perfil'],
            "nombre" => $row['nombre_perfil'],
            "descripcion" => $row['descripcion'],
            "cantidad_usuarios" => $row['cantidad_usuarios'],

            "permisos" => $row['permisos'] ? $row['permisos'] : "Sin permisos asignados"
        );
        array_push($perfiles_arr, $item);
    }
    
    http_response_code(200);
    echo json_encode($perfiles_arr);
} else {
    http_response_code(200);
    echo json_encode(array("mensaje" => "No se encontraron perfiles en el sistema."));
}
?>