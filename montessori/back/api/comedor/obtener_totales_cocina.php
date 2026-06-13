<?php

header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$dbname = 'montessori_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmtFecha = $pdo->query("SELECT MAX(fecha) as ultima_fecha FROM asistencia_diaria");
    $resultadoFecha = $stmtFecha->fetch(PDO::FETCH_ASSOC);

    $hoy = $resultadoFecha['ultima_fecha'] ? $resultadoFecha['ultima_fecha'] : date('Y-m-d');

    $stmtMenu = $pdo->prepare("SELECT tipo_servicio, descripcion FROM comedor_menu WHERE fecha = ?");
    $stmtMenu->execute([$hoy]);
    $menuData = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);

    $menu = [
        'desayuno' => '',
        'almuerzo' => '',
        'merienda' => ''
    ];

    foreach ($menuData as $m) {
        $tipo = strtolower($m['tipo_servicio']);
        if (array_key_exists($tipo, $menu)) {
            $menu[$tipo] = $m['descripcion'];
        }
    }

    $sqlComensales = "
        SELECT 
            ad.matriculas_id_matricula AS id_matricula,
            CONCAT('Alumno (Matrícula ', ad.matriculas_id_matricula, ')') AS nombre_completo,
            'Secundario - División A' AS curso,
            IF(ad.matriculas_id_matricula = 2, 'Menú para Celíacos', 'Ninguna') AS restriccion_alimentaria,
            1 AS asiste
        FROM asistencia_diaria ad
        WHERE ad.fecha = ? AND LOWER(ad.estado) = 'presente'
    ";
    
    $stmtComensales = $pdo->prepare($sqlComensales);
    $stmtComensales->execute([$hoy]);
    $comensales = $stmtComensales->fetchAll(PDO::FETCH_ASSOC);

    $raciones = count($comensales);

    echo json_encode([
        'status' => 'ok',
        'raciones' => $raciones,
        'menu' => $menu,
        'comensales' => $comensales
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'Error al conectar con la cocina: ' . $e->getMessage()
    ]);
}
?>