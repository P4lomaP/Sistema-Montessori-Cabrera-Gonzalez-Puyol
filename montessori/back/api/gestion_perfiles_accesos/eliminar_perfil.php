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

if (empty($data->id_perfil)) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "Falta el ID del perfil a eliminar."
    ]);
    exit;
}

$idPerfil = intval($data->id_perfil);

if ($idPerfil <= 0) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "El ID del perfil no es válido."
    ]);
    exit;
}

if ($idPerfil === 1) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "No se puede eliminar el perfil principal del sistema."
    ]);
    exit;
}

try {
    $queryPerfil = "
        SELECT 
            id_perfil,
            nombre_perfil,
            estado_activo
        FROM perfiles
        WHERE id_perfil = :id_perfil
        LIMIT 1
    ";

    $stmtPerfil = $db->prepare($queryPerfil);
    $stmtPerfil->bindParam(":id_perfil", $idPerfil);
    $stmtPerfil->execute();

    if ($stmtPerfil->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            "mensaje" => "El perfil seleccionado no existe."
        ]);
        exit;
    }

    $perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);

    if (intval($perfil["estado_activo"]) === 0) {
        http_response_code(400);
        echo json_encode([
            "mensaje" => "Este perfil ya fue eliminado anteriormente."
        ]);
        exit;
    }

    /*
        No permitimos eliminar perfiles que todavía tengan usuarios asignados.
        Primero se debe quitar ese perfil desde Usuarios y perfiles.
    */
    $queryUsuarios = "
        SELECT COUNT(*) AS total
        FROM usuario_perfil
        WHERE perfiles_id_perfil = :id_perfil
    ";

    $stmtUsuarios = $db->prepare($queryUsuarios);
    $stmtUsuarios->bindParam(":id_perfil", $idPerfil);
    $stmtUsuarios->execute();

    $resultadoUsuarios = $stmtUsuarios->fetch(PDO::FETCH_ASSOC);
    $totalUsuarios = intval($resultadoUsuarios["total"]);

    if ($totalUsuarios > 0) {
        http_response_code(400);
        echo json_encode([
            "mensaje" => "No se puede eliminar este perfil porque tiene usuarios asignados. Primero quite el perfil de esos usuarios."
        ]);
        exit;
    }

    /*
        Eliminación lógica:
        El registro NO se borra.
        Solo se marca como inactivo.
    */
    $queryUpdate = "
        UPDATE perfiles
        SET estado_activo = 0
        WHERE id_perfil = :id_perfil
    ";

    $stmtUpdate = $db->prepare($queryUpdate);
    $stmtUpdate->bindParam(":id_perfil", $idPerfil);

    if ($stmtUpdate->execute()) {
        http_response_code(200);

        echo json_encode([
            "mensaje" => "Perfil eliminado correctamente del sistema.",
            "perfil" => [
                "id_perfil" => $idPerfil,
                "nombre_perfil" => $perfil["nombre_perfil"],
                "estado_activo" => 0
            ]
        ]);
    } else {
        http_response_code(500);

        echo json_encode([
            "mensaje" => "No se pudo eliminar el perfil."
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        "mensaje" => "Error al eliminar el perfil.",
        "error" => $e->getMessage()
    ]);
}
?>