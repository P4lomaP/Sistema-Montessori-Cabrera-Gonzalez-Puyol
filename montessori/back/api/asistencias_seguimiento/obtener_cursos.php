<?php
header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$dbname = 'montessori_db';
$user = 'root';
$pass = '';

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id_curso, anio_grado, division, turno FROM cursos ORDER BY anio_grado ASC, division ASC";
    $stmt = $pdo->query($sql);
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'ok',
        'data' => $cursos
    ]);

} catch (Exception $e) {

    echo json_encode([
        'status' => 'error',
        'mensaje' => 'Error al obtener los cursos: ' . $e->getMessage()
    ]);
}
?>