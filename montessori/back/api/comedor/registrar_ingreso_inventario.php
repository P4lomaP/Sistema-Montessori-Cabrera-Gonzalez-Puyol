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

if (!$data) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "No se recibieron datos válidos en formato JSON."
    ]);
    exit;
}

if (empty($data->insumo) || empty($data->cantidad) || empty($data->unidad)) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "Faltan datos obligatorios del insumo."
    ]);
    exit;
}

$insumo = trim($data->insumo);
$cantidad = floatval($data->cantidad);
$unidad = trim($data->unidad);
$numeroComprobante = !empty($data->numero_comprobante) ? trim($data->numero_comprobante) : null;

if (strlen($insumo) < 2) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "El nombre del insumo no es válido."
    ]);
    exit;
}

if ($cantidad <= 0) {
    http_response_code(400);
    echo json_encode([
        "mensaje" => "La cantidad debe ser mayor a cero."
    ]);
    exit;
}

try {
    $idUsuario = 1;

    $query = "
        INSERT INTO comedor_inventario
            (usuarios_id_usuario, insumo, cantidad, unidad, numero_comprobante)
        VALUES
            (:usuarios_id_usuario, :insumo, :cantidad, :unidad, :numero_comprobante)
    ";

    $stmt = $db->prepare($query);

    $stmt->bindParam(":usuarios_id_usuario", $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(":insumo", $insumo);
    $stmt->bindParam(":cantidad", $cantidad);
    $stmt->bindParam(":unidad", $unidad);
    $stmt->bindParam(":numero_comprobante", $numeroComprobante);

    $stmt->execute();

    http_response_code(201);

    echo json_encode([
        "mensaje" => "Ingreso de mercadería registrado correctamente.",
        "movimiento" => [
            "id_movimiento" => intval($db->lastInsertId()),
            "insumo" => $insumo,
            "cantidad" => $cantidad,
            "unidad" => $unidad,
            "numero_comprobante" => $numeroComprobante
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);

    echo json_encode([
        "mensaje" => "Error al registrar el ingreso de inventario.",
        "error" => $e->getMessage()
    ]);
}
?>