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
    empty($data->id_perfil) ||
    empty($data->nombre_perfil) ||
    empty($data->descripcion)
) {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan datos obligatorios: id_perfil, nombre_perfil y descripcion."]);
    exit;
}

$idPerfil = intval($data->id_perfil);
$nombrePerfil = trim($data->nombre_perfil);
$descripcion = trim($data->descripcion);

if ($idPerfil <= 0) {
    http_response_code(400);
    echo json_encode(["mensaje" => "El ID del perfil no es válido."]);
    exit;
}

if (strlen($nombrePerfil) < 3) {
    http_response_code(400);
    echo json_encode(["mensaje" => "El nombre del perfil debe tener al menos 3 caracteres."]);
    exit;
}

try {
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
        echo json_encode(["mensaje" => "El perfil seleccionado no existe."]);
        exit;
    }

    /*
        Evitar nombres duplicados en otro perfil.
    */

    $queryDuplicado = "SELECT id_perfil 
                       FROM perfiles 
                       WHERE nombre_perfil = :nombre_perfil 
                       AND id_perfil <> :id_perfil 
                       LIMIT 1";

    $stmtDuplicado = $db->prepare($queryDuplicado);
    $stmtDuplicado->bindParam(":nombre_perfil", $nombrePerfil);
    $stmtDuplicado->bindParam(":id_perfil", $idPerfil);
    $stmtDuplicado->execute();

    if ($stmtDuplicado->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(["mensaje" => "Ya existe otro perfil con ese nombre."]);
        exit;
    }

    /*
        Actualizar perfil.
    */

    $queryUpdate = "UPDATE perfiles
                    SET nombre_perfil = :nombre_perfil,
                        descripcion = :descripcion
                    WHERE id_perfil = :id_perfil";

    $stmtUpdate = $db->prepare($queryUpdate);
    $stmtUpdate->bindParam(":nombre_perfil", $nombrePerfil);
    $stmtUpdate->bindParam(":descripcion", $descripcion);
    $stmtUpdate->bindParam(":id_perfil", $idPerfil);

    if ($stmtUpdate->execute()) {
        http_response_code(200);
        echo json_encode([
            "mensaje" => "Perfil actualizado correctamente.",
            "perfil" => [
                "id_perfil" => $idPerfil,
                "nombre_perfil" => $nombrePerfil,
                "descripcion" => $descripcion
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "No se pudo actualizar el perfil."]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "mensaje" => "Error al actualizar el perfil.",
        "error" => $e->getMessage()
    ]);
}
?>