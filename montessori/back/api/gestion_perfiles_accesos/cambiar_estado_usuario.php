<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id_usuario) || !isset($data->estado_activo)) {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos obligatorios: id_usuario y estado_activo."]);
    exit;
}

$idUsuario = intval($data->id_usuario);
$estadoActivo = intval($data->estado_activo);

if ($estadoActivo !== 0 && $estadoActivo !== 1) {
    http_response_code(400);
    echo json_encode(["mensaje" => "El estado enviado no es válido."]);
    exit;
}

/*
    Evitar desactivar el usuario principal del sistema.
    Asumimos que el usuario ID 1 es el administrador/directivo principal.
*/

if ($idUsuario === 1 && $estadoActivo === 0) {
    http_response_code(400);
    echo json_encode(["mensaje" => "No se puede desactivar el usuario principal del sistema."]);
    exit;
}

$queryUsuario = "SELECT id_usuario, dni, nombre, apellido, estado_activo
                 FROM usuarios
                 WHERE id_usuario = :id_usuario
                 LIMIT 1";

$stmtUsuario = $db->prepare($queryUsuario);
$stmtUsuario->bindParam(":id_usuario", $idUsuario);
$stmtUsuario->execute();

if ($stmtUsuario->rowCount() == 0) {
    http_response_code(404);
    echo json_encode(["mensaje" => "El usuario seleccionado no existe."]);
    exit;
}

$usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

$queryUpdate = "UPDATE usuarios
                SET estado_activo = :estado_activo
                WHERE id_usuario = :id_usuario";

$stmtUpdate = $db->prepare($queryUpdate);
$stmtUpdate->bindParam(":estado_activo", $estadoActivo);
$stmtUpdate->bindParam(":id_usuario", $idUsuario);

if ($stmtUpdate->execute()) {
    $accion = $estadoActivo === 1 ? "activada" : "desactivada";

    http_response_code(200);
    echo json_encode([
        "mensaje" => "Cuenta " . $accion . " correctamente.",
        "usuario" => [
            "id_usuario" => $usuario["id_usuario"],
            "dni" => $usuario["dni"],
            "nombre" => $usuario["nombre"],
            "apellido" => $usuario["apellido"],
            "estado_activo" => $estadoActivo
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode(["mensaje" => "No se pudo actualizar el estado de la cuenta."]);
}
?>