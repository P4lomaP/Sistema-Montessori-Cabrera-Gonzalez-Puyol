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

if (
    empty($data->id_usuario) ||
    empty($data->dni) ||
    empty($data->nombre) ||
    empty($data->apellido) ||
    empty($data->correo)
) {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos obligatorios del usuario."]);
    exit;
}

$idUsuario = intval($data->id_usuario);
$dni = trim($data->dni);
$nombre = trim($data->nombre);
$apellido = trim($data->apellido);
$correo = trim($data->correo);

if ($idUsuario <= 0) {
    http_response_code(400);
    echo json_encode(["mensaje" => "El ID del usuario no es válido."]);
    exit;
}

if (!preg_match('/^[0-9]{7,8}$/', $dni)) {
    http_response_code(400);
    echo json_encode(["mensaje" => "El DNI debe contener solo números y tener entre 7 y 8 dígitos."]);
    exit;
}

if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]{2,60}$/u', $nombre)) {
    http_response_code(400);
    echo json_encode(["mensaje" => "El nombre debe contener solo letras y tener al menos 2 caracteres."]);
    exit;
}

if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]{2,60}$/u', $apellido)) {
    http_response_code(400);
    echo json_encode(["mensaje" => "El apellido debe contener solo letras y tener al menos 2 caracteres."]);
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["mensaje" => "El correo electrónico no tiene un formato válido."]);
    exit;
}

try {
    $queryUsuario = "SELECT id_usuario 
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

    $queryDni = "SELECT id_usuario 
                 FROM usuarios 
                 WHERE dni = :dni 
                 AND id_usuario <> :id_usuario 
                 LIMIT 1";

    $stmtDni = $db->prepare($queryDni);
    $stmtDni->bindParam(":dni", $dni);
    $stmtDni->bindParam(":id_usuario", $idUsuario);
    $stmtDni->execute();

    if ($stmtDni->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(["mensaje" => "Ya existe otro usuario registrado con ese DNI."]);
        exit;
    }

    $queryCorreo = "SELECT id_usuario 
                    FROM usuarios 
                    WHERE correo = :correo 
                    AND id_usuario <> :id_usuario 
                    LIMIT 1";

    $stmtCorreo = $db->prepare($queryCorreo);
    $stmtCorreo->bindParam(":correo", $correo);
    $stmtCorreo->bindParam(":id_usuario", $idUsuario);
    $stmtCorreo->execute();

    if ($stmtCorreo->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(["mensaje" => "Ya existe otro usuario registrado con ese correo."]);
        exit;
    }

    $queryUpdate = "UPDATE usuarios
                    SET dni = :dni,
                        nombre = :nombre,
                        apellido = :apellido,
                        correo = :correo
                    WHERE id_usuario = :id_usuario";

    $stmtUpdate = $db->prepare($queryUpdate);
    $stmtUpdate->bindParam(":dni", $dni);
    $stmtUpdate->bindParam(":nombre", $nombre);
    $stmtUpdate->bindParam(":apellido", $apellido);
    $stmtUpdate->bindParam(":correo", $correo);
    $stmtUpdate->bindParam(":id_usuario", $idUsuario);

    if ($stmtUpdate->execute()) {
        http_response_code(200);
        echo json_encode([
            "mensaje" => "Usuario actualizado correctamente.",
            "usuario" => [
                "id_usuario" => $idUsuario,
                "dni" => $dni,
                "nombre" => $nombre,
                "apellido" => $apellido,
                "correo" => $correo
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "No se pudo actualizar el usuario."]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "mensaje" => "Error al actualizar el usuario.",
        "error" => $e->getMessage()
    ]);
}
?>