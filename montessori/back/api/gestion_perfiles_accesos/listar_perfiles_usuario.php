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
    Validar usuario recibido.

    Espera:
    {
        "id_usuario": 3
    }
*/

if (empty($data->id_usuario)) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "Falta el id_usuario."
    ]);
    exit;
}

$idUsuario = intval($data->id_usuario);

/*
    Verificar que el usuario exista.
*/

$queryUsuario = "SELECT id_usuario, dni, nombre, apellido, correo, estado_activo, estado_desbloqueo
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
    Obtener perfiles asignados al usuario.
*/

$queryPerfiles = "SELECT 
                    p.id_perfil,
                    p.nombre_perfil,
                    p.descripcion
                  FROM usuario_perfil up
                  INNER JOIN perfiles p 
                    ON up.perfiles_id_perfil = p.id_perfil
                  WHERE up.usuarios_id_usuario = :id_usuario
                  ORDER BY p.nombre_perfil ASC";

$stmtPerfiles = $db->prepare($queryPerfiles);
$stmtPerfiles->bindParam(":id_usuario", $idUsuario);
$stmtPerfiles->execute();

$perfiles = [];

while ($row = $stmtPerfiles->fetch(PDO::FETCH_ASSOC)) {
    $perfiles[] = [
        "id_perfil" => $row["id_perfil"],
        "nombre" => $row["nombre_perfil"],
        "descripcion" => $row["descripcion"]
    ];
}

/*
    Obtener permisos finales del usuario.
    Esto es útil porque si el usuario tiene varios perfiles,
    acá se ve la suma de todos los permisos heredados.
*/

$queryPermisos = "SELECT DISTINCT 
                    per.modulo,
                    per.accion
                  FROM usuario_perfil up
                  INNER JOIN perfil_permiso pp
                    ON up.perfiles_id_perfil = pp.perfiles_id_perfil
                  INNER JOIN permisos per
                    ON pp.permisos_id_permiso = per.id_permiso
                  WHERE up.usuarios_id_usuario = :id_usuario
                  ORDER BY per.modulo ASC, per.accion ASC";

$stmtPermisos = $db->prepare($queryPermisos);
$stmtPermisos->bindParam(":id_usuario", $idUsuario);
$stmtPermisos->execute();

$permisos = [];

while ($row = $stmtPermisos->fetch(PDO::FETCH_ASSOC)) {
    $permisos[] = $row["modulo"] . "_" . $row["accion"];
}

http_response_code(200);
echo json_encode([
    "mensaje" => "Perfiles del usuario obtenidos correctamente.",
    "usuario" => [
        "id_usuario" => $usuario["id_usuario"],
        "dni" => $usuario["dni"],
        "nombre" => $usuario["nombre"],
        "apellido" => $usuario["apellido"],
        "correo" => $usuario["correo"],
        "estado_activo" => $usuario["estado_activo"],
        "estado_desbloqueo" => $usuario["estado_desbloqueo"]
    ],
    "perfiles" => $perfiles,
    "cantidad_perfiles" => count($perfiles),
    "permisos_finales" => $permisos,
    "cantidad_permisos_finales" => count($permisos)
]);
?>