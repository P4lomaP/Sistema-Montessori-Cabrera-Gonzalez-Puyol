<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

/* ==============================================================
   HELPERS
============================================================== */

function tablaExiste($db, $tabla) {
    try {
        $query = "
            SELECT COUNT(*) AS total
            FROM information_schema.tables
            WHERE table_schema = DATABASE()
            AND table_name = :tabla
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(":tabla", $tabla);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return intval($row["total"] ?? 0) > 0;

    } catch (Exception $e) {
        return false;
    }
}

function columnaExiste($db, $tabla, $columna) {
    try {
        $query = "
            SELECT COUNT(*) AS total
            FROM information_schema.columns
            WHERE table_schema = DATABASE()
            AND table_name = :tabla
            AND column_name = :columna
        ";

        $stmt = $db->prepare($query);
        $stmt->bindParam(":tabla", $tabla);
        $stmt->bindParam(":columna", $columna);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return intval($row["total"] ?? 0) > 0;

    } catch (Exception $e) {
        return false;
    }
}

function contarSQL($db, $query) {
    try {
        $stmt = $db->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return intval($row["total"] ?? 0);

    } catch (Exception $e) {
        return 0;
    }
}

/* ==============================================================
   DASHBOARD
============================================================== */

try {
    $usuariosActivos = 0;
    $pendientes = 0;
    $perfilesActivos = 0;
    $permisosActivos = 0;
    $alumnosActivos = 0;

    /* =============================
       USUARIOS
    ============================= */

    if (tablaExiste($db, "usuarios")) {
        if (columnaExiste($db, "usuarios", "estado_activo")) {
            $usuariosActivos = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM usuarios
                WHERE estado_activo = 1
            ");

            $pendientes = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM usuarios
                WHERE estado_activo = 0
            ");
        } else {
            $usuariosActivos = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM usuarios
            ");
        }
    }

    /* =============================
       PERFILES
    ============================= */

    if (tablaExiste($db, "perfiles")) {
        if (columnaExiste($db, "perfiles", "estado_activo")) {
            $perfilesActivos = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM perfiles
                WHERE estado_activo = 1
            ");
        } else {
            $perfilesActivos = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM perfiles
            ");
        }
    }

    /* =============================
       PERMISOS
    ============================= */

    if (tablaExiste($db, "permisos")) {
        if (columnaExiste($db, "permisos", "estado_activo")) {
            $permisosActivos = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM permisos
                WHERE estado_activo = 1
            ");
        } else {
            $permisosActivos = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM permisos
            ");
        }
    }

    /* =============================
       ALUMNOS
    ============================= */

    if (tablaExiste($db, "alumnos")) {
        if (columnaExiste($db, "alumnos", "estado_activo")) {
            $alumnosActivos = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM alumnos
                WHERE estado_activo = 1
            ");
        } else {
            $alumnosActivos = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM alumnos
            ");
        }
    }

    /* =============================
       ASISTENCIA
    ============================= */

    $asistencia = [
        "presentes" => 0,
        "ausentes" => 0,
        "alertas" => 0
    ];

    if (tablaExiste($db, "asistencia_intervenciones")) {
        $asistencia["alertas"] = contarSQL($db, "
            SELECT COUNT(*) AS total
            FROM asistencia_intervenciones
            WHERE DATE(fecha) = CURDATE()
        ");
    }

    /*
        Soporte para tabla asistencias
    */
    if (tablaExiste($db, "asistencias")) {
        if (columnaExiste($db, "asistencias", "fecha") && columnaExiste($db, "asistencias", "estado")) {
            $asistencia["presentes"] = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM asistencias
                WHERE DATE(fecha) = CURDATE()
                AND (
                    LOWER(estado) = 'presente'
                    OR estado = '1'
                )
            ");

            $asistencia["ausentes"] = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM asistencias
                WHERE DATE(fecha) = CURDATE()
                AND (
                    LOWER(estado) = 'ausente'
                    OR estado = '0'
                )
            ");
        }
    }

    /*
        Soporte para tabla asistencia_diaria
    */
    if (tablaExiste($db, "asistencia_diaria")) {
        if (columnaExiste($db, "asistencia_diaria", "fecha")) {
            if (columnaExiste($db, "asistencia_diaria", "estado")) {
                $asistencia["presentes"] = contarSQL($db, "
                    SELECT COUNT(*) AS total
                    FROM asistencia_diaria
                    WHERE DATE(fecha) = CURDATE()
                    AND (
                        LOWER(estado) = 'presente'
                        OR estado = '1'
                    )
                ");

                $asistencia["ausentes"] = contarSQL($db, "
                    SELECT COUNT(*) AS total
                    FROM asistencia_diaria
                    WHERE DATE(fecha) = CURDATE()
                    AND (
                        LOWER(estado) = 'ausente'
                        OR estado = '0'
                    )
                ");
            }

            if (columnaExiste($db, "asistencia_diaria", "presente")) {
                $asistencia["presentes"] = contarSQL($db, "
                    SELECT COUNT(*) AS total
                    FROM asistencia_diaria
                    WHERE DATE(fecha) = CURDATE()
                    AND presente = 1
                ");

                $asistencia["ausentes"] = contarSQL($db, "
                    SELECT COUNT(*) AS total
                    FROM asistencia_diaria
                    WHERE DATE(fecha) = CURDATE()
                    AND presente = 0
                ");
            }
        }
    }

    /* =============================
       MENÚ DEL DÍA
    ============================= */

    $menu = [
        "desayuno" => null,
        "almuerzo" => null,
        "merienda" => null
    ];

    if (tablaExiste($db, "comedor_menu")) {
        try {
            $queryMenu = "
                SELECT tipo_servicio, descripcion
                FROM comedor_menu
                WHERE fecha = CURDATE()
            ";

            $stmtMenu = $db->prepare($queryMenu);
            $stmtMenu->execute();

            while ($row = $stmtMenu->fetch(PDO::FETCH_ASSOC)) {
                $tipo = strtolower(trim($row["tipo_servicio"]));

                if ($tipo === "desayuno") {
                    $menu["desayuno"] = $row["descripcion"];
                }

                if ($tipo === "almuerzo") {
                    $menu["almuerzo"] = $row["descripcion"];
                }

                if ($tipo === "merienda") {
                    $menu["merienda"] = $row["descripcion"];
                }
            }

        } catch (Exception $e) {}
    }

    $partesMenu = [];

    if (!empty($menu["desayuno"])) {
        $partesMenu[] = "Desayuno: " . $menu["desayuno"];
    }

    if (!empty($menu["almuerzo"])) {
        $partesMenu[] = "Almuerzo: " . $menu["almuerzo"];
    }

    if (!empty($menu["merienda"])) {
        $partesMenu[] = "Merienda: " . $menu["merienda"];
    }

    $menuTexto = count($partesMenu) > 0
        ? implode(" | ", $partesMenu)
        : "Sin menú cargado";

    /* =============================
       COMEDOR / INVENTARIO
    ============================= */

    $stockBajo = 0;
    $ultimosIngresos = 0;

    if (tablaExiste($db, "comedor_inventario")) {
        if (columnaExiste($db, "comedor_inventario", "unidad")) {
            $stockBajo = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM (
                    SELECT insumo, unidad, SUM(cantidad) AS stock_total
                    FROM comedor_inventario
                    GROUP BY insumo, unidad
                    HAVING stock_total <= 5
                ) AS stock
            ");
        } else {
            $stockBajo = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM (
                    SELECT insumo, SUM(cantidad) AS stock_total
                    FROM comedor_inventario
                    GROUP BY insumo
                    HAVING stock_total <= 5
                ) AS stock
            ");
        }

        if (columnaExiste($db, "comedor_inventario", "fecha")) {
            $ultimosIngresos = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM comedor_inventario
                WHERE DATE(fecha) = CURDATE()
            ");
        } else {
            $ultimosIngresos = contarSQL($db, "
                SELECT COUNT(*) AS total
                FROM comedor_inventario
            ");
        }
    }

    /* =============================
       RESTRICCIONES ALIMENTARIAS
    ============================= */

    $restriccionesAlimentarias = 0;

    if (
        tablaExiste($db, "alumnos") &&
        columnaExiste($db, "alumnos", "restriccion_alimentaria")
    ) {
        $restriccionesAlimentarias = contarSQL($db, "
            SELECT COUNT(*) AS total
            FROM alumnos
            WHERE restriccion_alimentaria IS NOT NULL
            AND TRIM(restriccion_alimentaria) <> ''
            AND LOWER(TRIM(restriccion_alimentaria)) <> 'ninguna'
        ");
    }

    /* =============================
       ALERTAS
    ============================= */

    $alertas = [];

    if ($pendientes > 0) {
        $alertas[] = [
            "tipo" => "warning",
            "texto" => "Hay " . $pendientes . " usuario/s pendiente/s de activación o revisión."
        ];
    }

    if ($stockBajo > 0) {
        $alertas[] = [
            "tipo" => "warning",
            "texto" => "Hay " . $stockBajo . " insumo/s con stock bajo en comedor."
        ];
    }

    if ($restriccionesAlimentarias > 0) {
        $alertas[] = [
            "tipo" => "info",
            "texto" => "Hay " . $restriccionesAlimentarias . " alumno/s con restricción alimentaria registrada."
        ];
    }

    if ($asistencia["alertas"] > 0) {
        $alertas[] = [
            "tipo" => "warning",
            "texto" => "Hay " . $asistencia["alertas"] . " intervención/es de asistencia registradas hoy."
        ];
    }

    /* =============================
       GRÁFICO DE INVENTARIO
    ============================= */

    $graficoInventario = [];

    if (tablaExiste($db, "comedor_inventario")) {
        try {
            if (columnaExiste($db, "comedor_inventario", "unidad")) {
                $queryInventario = "
                    SELECT 
                        insumo,
                        unidad,
                        SUM(cantidad) AS cantidad
                    FROM comedor_inventario
                    GROUP BY insumo, unidad
                    ORDER BY cantidad ASC
                    LIMIT 6
                ";
            } else {
                $queryInventario = "
                    SELECT 
                        insumo,
                        'Unidades' AS unidad,
                        SUM(cantidad) AS cantidad
                    FROM comedor_inventario
                    GROUP BY insumo
                    ORDER BY cantidad ASC
                    LIMIT 6
                ";
            }

            $stmtInventario = $db->prepare($queryInventario);
            $stmtInventario->execute();

            while ($row = $stmtInventario->fetch(PDO::FETCH_ASSOC)) {
                $graficoInventario[] = [
                    "insumo" => $row["insumo"],
                    "unidad" => $row["unidad"],
                    "cantidad" => floatval($row["cantidad"])
                ];
            }

        } catch (Exception $e) {
            $graficoInventario = [];
        }
    }

    /* =============================
       GRÁFICO DE PERMISOS POR MÓDULO
    ============================= */

    $graficoPermisosModulo = [];

    if (tablaExiste($db, "permisos")) {
        try {
            if (columnaExiste($db, "permisos", "estado_activo")) {
                $queryPermisosModulo = "
                    SELECT 
                        modulo,
                        COUNT(*) AS total
                    FROM permisos
                    WHERE estado_activo = 1
                    AND modulo IS NOT NULL
                    AND TRIM(modulo) <> ''
                    GROUP BY modulo
                    ORDER BY total DESC
                ";
            } else {
                $queryPermisosModulo = "
                    SELECT 
                        modulo,
                        COUNT(*) AS total
                    FROM permisos
                    WHERE modulo IS NOT NULL
                    AND TRIM(modulo) <> ''
                    GROUP BY modulo
                    ORDER BY total DESC
                ";
            }

            $stmtPermisosModulo = $db->prepare($queryPermisosModulo);
            $stmtPermisosModulo->execute();

            while ($row = $stmtPermisosModulo->fetch(PDO::FETCH_ASSOC)) {
                $graficoPermisosModulo[] = [
                    "modulo" => $row["modulo"],
                    "total" => intval($row["total"])
                ];
            }

        } catch (Exception $e) {
            $graficoPermisosModulo = [];
        }
    }

    /* =============================
       RESPUESTA FINAL
    ============================= */

    http_response_code(200);

    echo json_encode([
        "mensaje" => "Dashboard obtenido correctamente.",
        "usuarios_activos" => $usuariosActivos,
        "pendientes" => $pendientes,
        "perfiles_activos" => $perfilesActivos,
        "permisos_activos" => $permisosActivos,
        "alumnos_activos" => $alumnosActivos,
        "asistencia" => $asistencia,
        "comedor" => [
            "raciones" => $alumnosActivos,
            "menu" => $menu,
            "menu_texto" => $menuTexto,
            "stock_bajo" => $stockBajo,
            "ultimos_ingresos" => $ultimosIngresos
        ],
        "alertas" => $alertas,
        "graficos" => [
            "inventario" => $graficoInventario,
            "permisos_por_modulo" => $graficoPermisosModulo
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        "mensaje" => "Error al obtener datos del dashboard.",
        "error" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>