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

/*
    Validación de token.

    Acepta:
    1. Authorization: Bearer TOKEN
    2. JSON: { "token": "TOKEN" }
*/

$headers = getallheaders();
$token = null;

if (isset($headers['Authorization'])) {
    $token = str_replace('Bearer ', '', $headers['Authorization']);
}

if (!$token && !empty($data->token)) {
    $token = $data->token;
}

if (!$token) {
    http_response_code(401);
    echo json_encode([
        "mensaje" => "Acceso denegado. Se requiere iniciar sesión."
    ]);
    exit;
}

$queryToken = "SELECT id_usuario 
               FROM usuarios 
               WHERE token_sesion = :token 
               LIMIT 1";

$stmtToken = $db->prepare($queryToken);
$stmtToken->bindParam(":token", $token);
$stmtToken->execute();

if ($stmtToken->rowCount() == 0) {
    http_response_code(401);
    echo json_encode([
        "mensaje" => "Token inválido o sesión expirada."
    ]);
    exit;
}

/*
    Validar datos recibidos.

    Espera:
    {
        "id_usuario": 3,
        "id_perfil": 2
    }
*/

if (empty($data->id_usuario) || empty($data->id_perfil)) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "Faltan datos obligatorios: id_usuario e id_perfil."
    ]);
    exit;
}

$idUsuario = intval($data->id_usuario);
$idPerfil = intval($data->id_perfil);

/*
    Verificar que el usuario exista.
*/

$queryUsuario = "SELECT id_usuario, dni, nombre, apellido 
                 FROM usuarios 
                 WHERE id_usuario = :id_usuario 
                 LIMIT 1";

$stmtUsuario = $db->prepare($queryUsuario);
$stmtUsuario->bindParam(":id_usuario", $idUsuario);
$stmtUsuario->execute();

if ($stmtUsuario->rowCount() == 0) {
    http_response_code(404);
    echo json_encode([
        "mensaje" => "El usuario seleccionado no existe."
    ]);
    exit;
}

$usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

/*
    Verificar que el perfil exista.
*/

$queryPerfil = "SELECT id_perfil, nombre_perfil 
                FROM perfiles 
                WHERE id_perfil = :id_perfil 
                LIMIT 1";

$stmtPerfil = $db->prepare($queryPerfil);
$stmtPerfil->bindParam(":id_perfil", $idPerfil);
$stmtPerfil->execute();

if ($stmtPerfil->rowCount() == 0) {
    http_response_code(404);
    echo json_encode([
        "mensaje" => "El perfil seleccionado no existe."
    ]);
    exit;
}

$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);

/*
    Verificar que el usuario tenga ese perfil asignado.
*/

$queryExiste = "SELECT usuarios_id_usuario, perfiles_id_perfil
                FROM usuario_perfil
                WHERE usuarios_id_usuario = :id_usuario
                AND perfiles_id_perfil = :id_perfil
                LIMIT 1";

$stmtExiste = $db->prepare($queryExiste);
$stmtExiste->bindParam(":id_usuario", $idUsuario);
$stmtExiste->bindParam(":id_perfil", $idPerfil);
$stmtExiste->execute();

if ($stmtExiste->rowCount() == 0) {
    http_response_code(200);
    echo json_encode([
        "mensaje" => "El usuario no tenía asignado ese perfil.",
        "usuario" => [
            "id_usuario" => $usuario["id_usuario"],
            "dni" => $usuario["dni"],
            "nombre" => $usuario["nombre"],
            "apellido" => $usuario["apellido"]
        ],
        "perfil" => [
            "id_perfil" => $perfil["id_perfil"],
            "nombre" => $perfil["nombre_perfil"]
        ]
    ]);
    exit;
}

/*
    Evitar que un usuario quede sin perfiles si quieren mantener siempre al menos uno.
    Si no querés esta regla, podés borrar este bloque.
*/

$queryCantidad = "SELECT COUNT(*) AS cantidad
                  FROM usuario_perfil
                  WHERE usuarios_id_usuario = :id_usuario";

$stmtCantidad = $db->prepare($queryCantidad);
$stmtCantidad->bindParam(":id_usuario", $idUsuario);
$stmtCantidad->execute();

$rowCantidad = $stmtCantidad->fetch(PDO::FETCH_ASSOC);
$cantidadPerfiles = intval($rowCantidad["cantidad"]);

if ($cantidadPerfiles <= 1) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "No se puede quitar el último perfil del usuario. Primero asigne otro perfil y luego quite este."
    ]);
    exit;
}

/*
    Quitar perfil.
*/

$queryDelete = "DELETE FROM usuario_perfil
                WHERE usuarios_id_usuario = :id_usuario
                AND perfiles_id_perfil = :id_perfil";

$stmtDelete = $db->prepare($queryDelete);
$stmtDelete->bindParam(":id_usuario", $idUsuario);
$stmtDelete->bindParam(":id_perfil", $idPerfil);

if ($stmtDelete->execute() && $stmtDelete->rowCount() > 0) {
    http_response_code(200);
    echo json_encode([
        "mensaje" => "Perfil quitado correctamente al usuario.",
        "usuario" => [
            "id_usuario" => $usuario["id_usuario"],
            "dni" => $usuario["dni"],
            "nombre" => $usuario["nombre"],
            "apellido" => $usuario["apellido"]
        ],
        "perfil_quitado" => [
            "id_perfil" => $perfil["id_perfil"],
            "nombre" => $perfil["nombre_perfil"]
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "mensaje" => "No se pudo quitar el perfil al usuario."
    ]);
}
?>