<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';
include_once '../../models/Usuario.php'; 

$database = new Database();
$db = $database->getConnection();
$data = json_decode(file_get_contents("php://input"));

// Debug rápido: Si no recibe nada, avisa
if(!$data || empty($data->dni)) {
    echo json_encode(["error" => "No llegaron datos"]);
    exit;
}

$query = "SELECT pin_recuperacion FROM usuarios WHERE dni = :dni LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":dni", $data->dni);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row) {
    if($row['pin_recuperacion'] == $data->pin) {
        echo json_encode(["status" => "ok", "mensaje" => "PIN correcto"]);
    } else {
        echo json_encode(["status" => "error", "pin_esperado" => $row['pin_recuperacion'], "pin_recibido" => $data->pin]);
    }
} else {
    echo json_encode(["status" => "error", "mensaje" => "DNI no encontrado"]);
}
?>