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

if (empty($data->modulo) || empty($data->accion)) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "Faltan datos obligatorios: módulo y acción."
    ]);
    exit;
}

$modulo = trim($data->modulo);
$accion = trim($data->accion);

$modulosPermitidos = [
    "dashboard",
    "usuarios",
    "perfiles",
    "permisos",
    "asistencia",
    "comedor",
    "armario",
    "biblioteca",
    "horarios",
    "documentos",
    "actividades"
];

if (!in_array($modulo, $modulosPermitidos)) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "El módulo seleccionado no es válido."
    ]);
    exit;
}

if (!preg_match('/^[a-z_]{2,40}$/', $accion)) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "La acción del permiso no es válida. Use solo letras minúsculas y guiones bajos."
    ]);
    exit;
}

try {
    $queryExiste = "
        SELECT 
            id_permiso,
            estado_activo
        FROM permisos
        WHERE modulo = :modulo
        AND accion = :accion
        LIMIT 1
    ";

    $stmtExiste = $db->prepare($queryExiste);
    $stmtExiste->bindParam(":modulo", $modulo);
    $stmtExiste->bindParam(":accion", $accion);
    $stmtExiste->execute();

    if ($stmtExiste->rowCount() > 0) {
        $permisoExistente = $stmtExiste->fetch(PDO::FETCH_ASSOC);
        $idPermiso = intval($permisoExistente["id_permiso"]);
        $estadoActivo = intval($permisoExistente["estado_activo"]);

        if ($estadoActivo === 1) {
            http_response_code(409);
            echo json_encode([
                "mensaje" => "Ese permiso ya existe en el sistema."
            ]);
            exit;
        }

        $queryReactivar = "
            UPDATE permisos
            SET estado_activo = 1
            WHERE id_permiso = :id_permiso
        ";

        $stmtReactivar = $db->prepare($queryReactivar);
        $stmtReactivar->bindParam(":id_permiso", $idPermiso);
        $stmtReactivar->execute();

        http_response_code(200);
        echo json_encode([
            "mensaje" => "El permiso existía eliminado y fue reactivado correctamente.",
            "permiso" => [
                "id_permiso" => $idPermiso,
                "modulo" => $modulo,
                "accion" => $accion,
                "nombre_permiso" => $modulo . "_" . $accion,
                "estado_activo" => 1
            ]
        ]);
        exit;
    }

    $queryInsert = "
        INSERT INTO permisos 
            (modulo, accion, estado_activo)
        VALUES 
            (:modulo, :accion, 1)
    ";

    $stmtInsert = $db->prepare($queryInsert);
    $stmtInsert->bindParam(":modulo", $modulo);
    $stmtInsert->bindParam(":accion", $accion);

    if ($stmtInsert->execute()) {
        http_response_code(201);

        echo json_encode([
            "mensaje" => "Permiso creado correctamente.",
            "permiso" => [
                "id_permiso" => intval($db->lastInsertId()),
                "modulo" => $modulo,
                "accion" => $accion,
                "nombre_permiso" => $modulo . "_" . $accion,
                "estado_activo" => 1
            ]
        ]);
    } else {
        http_response_code(500);

        echo json_encode([
            "mensaje" => "No se pudo crear el permiso."
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        "mensaje" => "Error al crear el permiso.",
        "error" => $e->getMessage()
    ]);
}
?>