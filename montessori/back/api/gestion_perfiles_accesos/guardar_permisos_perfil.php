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
    echo json_encode(["mensaje" => "Acceso denegado. Se requiere iniciar sesión."]);
    exit;
}

$queryToken = "SELECT id_usuario FROM usuarios WHERE token_sesion = :token LIMIT 1";
$stmtToken = $db->prepare($queryToken);
$stmtToken->bindParam(":token", $token);
$stmtToken->execute();

if ($stmtToken->rowCount() == 0) {
    http_response_code(401);
    echo json_encode(["mensaje" => "Token inválido o sesión expirada."]);
    exit;
}

if (empty($data->id_perfil) || !isset($data->permisos) || !is_array($data->permisos)) {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos obligatorios: perfil o permisos."]);
    exit;
}

$idPerfil = intval($data->id_perfil);
$permisos = $data->permisos;

try {
    $db->beginTransaction();

    $queryPerfil = "SELECT id_perfil FROM perfiles WHERE id_perfil = :id_perfil LIMIT 1";
    $stmtPerfil = $db->prepare($queryPerfil);
    $stmtPerfil->bindParam(":id_perfil", $idPerfil);
    $stmtPerfil->execute();

    if ($stmtPerfil->rowCount() == 0) {
        $db->rollBack();
        http_response_code(404);
        echo json_encode(["mensaje" => "El perfil seleccionado no existe."]);
        exit;
    }

    $queryDelete = "DELETE FROM perfil_permiso WHERE perfiles_id_perfil = :id_perfil";
    $stmtDelete = $db->prepare($queryDelete);
    $stmtDelete->bindParam(":id_perfil", $idPerfil);
    $stmtDelete->execute();

    $cantidadAsignados = 0;

    foreach ($permisos as $permiso) {
        if (empty($permiso->modulo) || empty($permiso->accion)) {
            continue;
        }

        $modulo = trim($permiso->modulo);
        $accion = trim($permiso->accion);

        $queryBuscar = "SELECT id_permiso 
                        FROM permisos 
                        WHERE modulo = :modulo 
                        AND accion = :accion 
                        LIMIT 1";

        $stmtBuscar = $db->prepare($queryBuscar);
        $stmtBuscar->bindParam(":modulo", $modulo);
        $stmtBuscar->bindParam(":accion", $accion);
        $stmtBuscar->execute();

        if ($stmtBuscar->rowCount() > 0) {
            $rowPermiso = $stmtBuscar->fetch(PDO::FETCH_ASSOC);
            $idPermiso = $rowPermiso['id_permiso'];
        } else {
            $queryCrear = "INSERT INTO permisos (modulo, accion) VALUES (:modulo, :accion)";
            $stmtCrear = $db->prepare($queryCrear);
            $stmtCrear->bindParam(":modulo", $modulo);
            $stmtCrear->bindParam(":accion", $accion);
            $stmtCrear->execute();

            $idPermiso = $db->lastInsertId();
        }

        $queryAsignar = "INSERT IGNORE INTO perfil_permiso 
                         (perfiles_id_perfil, permisos_id_permiso) 
                         VALUES (:id_perfil, :id_permiso)";

        $stmtAsignar = $db->prepare($queryAsignar);
        $stmtAsignar->bindParam(":id_perfil", $idPerfil);
        $stmtAsignar->bindParam(":id_permiso", $idPermiso);
        $stmtAsignar->execute();

        $cantidadAsignados++;
    }

    $db->commit();

    http_response_code(200);
    echo json_encode([
        "mensaje" => "Permisos actualizados correctamente.",
        "permisos_asignados" => $cantidadAsignados
    ]);

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        "mensaje" => "Error al guardar los permisos del perfil.",
        "error" => $e->getMessage()
    ]);
}
?>