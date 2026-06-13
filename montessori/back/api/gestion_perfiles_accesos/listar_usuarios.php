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
    /*
        Listado general de usuarios para Gestión de Accesos.

        Devuelve:
        - cuentas habilitadas para ingresar al sistema
        - cuentas deshabilitadas temporalmente
        - cuentas pendientes de activación
        - cuentas bloqueadas o pendientes de desbloqueo
        - perfiles asignados a cada usuario
    */

    $query = "
        SELECT 
            u.id_usuario,
            u.dni,
            u.nombre,
            u.apellido,
            u.correo,
            u.estado_activo,
            u.intentos_fallidos,
            u.estado_desbloqueo,
            COUNT(DISTINCT up.perfiles_id_perfil) AS cantidad_perfiles,
            GROUP_CONCAT(
                DISTINCT p.nombre_perfil 
                ORDER BY p.nombre_perfil ASC 
                SEPARATOR ', '
            ) AS perfiles
        FROM usuarios u
        LEFT JOIN usuario_perfil up 
            ON u.id_usuario = up.usuarios_id_usuario
        LEFT JOIN perfiles p 
            ON up.perfiles_id_perfil = p.id_perfil
        GROUP BY 
            u.id_usuario,
            u.dni,
            u.nombre,
            u.apellido,
            u.correo,
            u.estado_activo,
            u.intentos_fallidos,
            u.estado_desbloqueo
        ORDER BY 
            u.apellido ASC,
            u.nombre ASC
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $usuarios = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $estadoActivo = intval($row["estado_activo"]);
        $intentosFallidos = intval($row["intentos_fallidos"]);
        $estadoDesbloqueo = $row["estado_desbloqueo"] ?? "ninguno";

        /*
            Estado textual para mostrar mejor en el front.
        */

        if ($estadoActivo === 0) {
            $estadoCuenta = "deshabilitada";
        } else if ($estadoDesbloqueo === "pendiente_aprobacion") {
            $estadoCuenta = "pendiente_desbloqueo";
        } else if ($estadoDesbloqueo === "bloqueado" || $intentosFallidos >= 3) {
            $estadoCuenta = "bloqueada";
        } else {
            $estadoCuenta = "habilitada";
        }

        $usuarios[] = [
            "id_usuario" => intval($row["id_usuario"]),
            "dni" => $row["dni"],
            "nombre" => $row["nombre"],
            "apellido" => $row["apellido"],
            "correo" => $row["correo"],
            "estado_activo" => $estadoActivo,
            "intentos_fallidos" => $intentosFallidos,
            "estado_desbloqueo" => $estadoDesbloqueo,
            "estado_cuenta" => $estadoCuenta,
            "cantidad_perfiles" => intval($row["cantidad_perfiles"]),
            "perfiles" => $row["perfiles"] ? $row["perfiles"] : "Sin perfiles asignados"
        ];
    }

    http_response_code(200);
    echo json_encode([
        "mensaje" => "Usuarios obtenidos correctamente.",
        "total" => count($usuarios),
        "usuarios" => $usuarios
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "mensaje" => "Error al obtener los usuarios.",
        "error" => $e->getMessage()
    ]);
}
?>