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
include_once '../../models/Usuario.php'; 

$database = new Database();
$db = $database->getConnection();

$usuario = new Usuario($db);

$data = json_decode(file_get_contents("php://input"));

if (
    empty($data->dni) ||
    empty($data->nombre) ||
    empty($data->apellido) ||
    empty($data->correo) ||
    empty($data->password)
) {
    http_response_code(400);
    echo json_encode(["mensaje" => "Datos incompletos para el registro."]);
    exit;
}

$dni = trim($data->dni);
$nombre = trim($data->nombre);
$apellido = trim($data->apellido);
$correo = trim($data->correo);
$password = trim($data->password);

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

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["mensaje" => "La contraseña debe tener al menos 6 caracteres."]);
    exit;
}

try {
    $queryExiste = "SELECT id_usuario 
                    FROM usuarios 
                    WHERE dni = :dni OR correo = :correo 
                    LIMIT 1";

    $stmtExiste = $db->prepare($queryExiste);
    $stmtExiste->bindParam(":dni", $dni);
    $stmtExiste->bindParam(":correo", $correo);
    $stmtExiste->execute();

    if ($stmtExiste->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(["mensaje" => "Ya existe un usuario registrado con ese DNI o correo."]);
        exit;
    }

    $usuario->dni = $dni;
    $usuario->nombre = $nombre;
    $usuario->apellido = $apellido;
    $usuario->correo = $correo;

    if ($usuario->registrar($password)) {
        http_response_code(201);
        echo json_encode([
            "mensaje" => "Usuario registrado correctamente. La cuenta queda pendiente de activación."
        ]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "No se pudo registrar el usuario."]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "mensaje" => "Error al registrar el usuario.",
        "error" => $e->getMessage()
    ]);
}
?>