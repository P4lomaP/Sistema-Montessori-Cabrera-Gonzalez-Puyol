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

try {
    $query = "
        SELECT 
            per.id_permiso,
            per.modulo,
            per.accion,
            per.estado_activo,

            COUNT(DISTINCT pp.perfiles_id_perfil) AS cantidad_perfiles,
            COUNT(DISTINCT up.usuarios_id_usuario) AS cantidad_usuarios

        FROM permisos per

        LEFT JOIN perfil_permiso pp
            ON per.id_permiso = pp.permisos_id_permiso

        LEFT JOIN perfiles p
            ON pp.perfiles_id_perfil = p.id_perfil
            AND p.estado_activo = 1

        LEFT JOIN usuario_perfil up
            ON p.id_perfil = up.perfiles_id_perfil

        WHERE per.estado_activo = 1

        GROUP BY 
            per.id_permiso,
            per.modulo,
            per.accion,
            per.estado_activo

        ORDER BY 
            per.modulo ASC,
            per.accion ASC
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $permisos = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $permisos[] = [
            "id_permiso" => intval($row["id_permiso"]),
            "id" => intval($row["id_permiso"]),
            "modulo" => $row["modulo"],
            "accion" => $row["accion"],
            "estado_activo" => intval($row["estado_activo"]),
            "nombre_permiso" => $row["modulo"] . "_" . $row["accion"],
            "cantidad_perfiles" => intval($row["cantidad_perfiles"]),
            "cantidad_usuarios" => intval($row["cantidad_usuarios"])
        ];
    }

    http_response_code(200);

    echo json_encode([
        "mensaje" => count($permisos) > 0
            ? "Permisos obtenidos correctamente."
            : "No hay permisos activos registrados.",
        "total" => count($permisos),
        "permisos" => $permisos
    ]);

} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        "mensaje" => "Error al obtener los permisos.",
        "error" => $e->getMessage()
    ]);
}
?>