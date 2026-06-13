<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once '../../config/Database.php';
<<<<<<< Updated upstream
include_once '../../models/Usuario.php'; 
=======
>>>>>>> Stashed changes

$database = new Database();
$db = $database->getConnection();

/*
    Validar token.
    Acepta token desde:
    1. Authorization: Bearer TOKEN
    2. JSON: { "token": "TOKEN" }
*/

$headers = getallheaders();
$token = null;

if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $authHeader);
}

if (!$token) {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->token)) {
        $token = $data->token;
    }
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
    Traer dos tipos de pendientes:
    1. Usuarios nuevos sin activar.
    2. Usuarios bloqueados que pidieron desbloqueo.
*/

$query = "
    SELECT 
        id_usuario,
        dni,
        nombre,
        apellido,
        estado_activo,
        intentos_fallidos,
        estado_desbloqueo,
        'activacion' AS tipo
    FROM usuarios
    WHERE estado_activo = 0

    UNION

    SELECT 
        id_usuario,
        dni,
        nombre,
        apellido,
        estado_activo,
        intentos_fallidos,
        estado_desbloqueo,
        'desbloqueo' AS tipo
    FROM usuarios
    WHERE estado_desbloqueo = 'pendiente_aprobacion'

    ORDER BY tipo ASC, apellido ASC, nombre ASC
";

$stmt = $db->prepare($query);
$stmt->execute();

$usuarios = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $usuarios[] = [
        "id_usuario" => $row["id_usuario"],
        "dni" => $row["dni"],
        "nombre" => $row["nombre"],
        "apellido" => $row["apellido"],
        "estado_activo" => $row["estado_activo"],
        "intentos_fallidos" => $row["intentos_fallidos"],
        "estado_desbloqueo" => $row["estado_desbloqueo"],
        "tipo" => $row["tipo"]
    ];
}

http_response_code(200);
echo json_encode([
    "mensaje" => "Listado obtenido correctamente.",
    "usuarios" => $usuarios
]);
?>