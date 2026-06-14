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
include_once '../../models/Perfil.php'; 

$database = new Database();
$db = $database->getConnection();

try {
    $query = "
        SELECT 
            p.id_perfil,
            p.nombre_perfil,
            p.descripcion,
            p.estado_activo,

            COUNT(DISTINCT up.usuarios_id_usuario) AS cantidad_usuarios,
            COUNT(DISTINCT per.id_permiso) AS cantidad_permisos,

            GROUP_CONCAT(
                DISTINCT CONCAT(per.modulo, '_', per.accion)
                ORDER BY per.modulo ASC, per.accion ASC
                SEPARATOR ', '
            ) AS permisos

        FROM perfiles p

        LEFT JOIN usuario_perfil up 
            ON p.id_perfil = up.perfiles_id_perfil

        LEFT JOIN perfil_permiso pp 
            ON p.id_perfil = pp.perfiles_id_perfil

        LEFT JOIN permisos per 
            ON pp.permisos_id_permiso = per.id_permiso

        WHERE p.estado_activo = 1

        GROUP BY 
            p.id_perfil,
            p.nombre_perfil,
            p.descripcion,
            p.estado_activo

        ORDER BY p.nombre_perfil ASC
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $perfiles = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $perfiles[] = [
            "id_perfil" => intval($row["id_perfil"]),
            "id" => intval($row["id_perfil"]),
            "nombre" => $row["nombre_perfil"],
            "nombre_perfil" => $row["nombre_perfil"],
            "descripcion" => $row["descripcion"],
            "estado_activo" => intval($row["estado_activo"]),
            "cantidad_usuarios" => intval($row["cantidad_usuarios"]),
            "cantidad_permisos" => intval($row["cantidad_permisos"]),
            "permisos" => $row["permisos"] ? $row["permisos"] : "Sin permisos asignados"
        ];
    }

    http_response_code(200);

    echo json_encode([
        "mensaje" => count($perfiles) > 0
            ? "Perfiles obtenidos correctamente."
            : "No hay perfiles activos registrados.",
        "total" => count($perfiles),
        "perfiles" => $perfiles
    ]);

} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        "mensaje" => "Error al obtener los perfiles.",
        "error" => $e->getMessage()
    ]);
}
?>