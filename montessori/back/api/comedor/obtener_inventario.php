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

try {
    $query = "
        SELECT 
            insumo,
            unidad,
            SUM(cantidad) AS cantidad_total,
            MAX(fecha) AS ultima_actualizacion
        FROM comedor_inventario
        GROUP BY insumo, unidad
        ORDER BY insumo ASC
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $inventario = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cantidad = floatval($row["cantidad_total"]);

        if ($cantidad <= 5) {
            $estado = "Stock Bajo";
        } elseif ($cantidad <= 15) {
            $estado = "Stock Medio";
        } else {
            $estado = "Óptimo";
        }

        $inventario[] = [
            "insumo" => $row["insumo"],
            "cantidad" => $cantidad,
            "cantidad_total" => $cantidad,
            "unidad" => $row["unidad"] ?: "Unidades",
            "estado" => $estado,
            "ultima_actualizacion" => $row["ultima_actualizacion"]
        ];
    }

    http_response_code(200);

    echo json_encode([
        "mensaje" => "Inventario obtenido correctamente.",
        "total" => count($inventario),
        "inventario" => $inventario
    ]);

} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        "mensaje" => "Error al obtener el inventario.",
        "error" => $e->getMessage()
    ]);
}
?>