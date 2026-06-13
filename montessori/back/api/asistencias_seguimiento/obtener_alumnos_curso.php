<?php
header('Content-Type: application/json; charset=utf-8');
$host = 'localhost'; $dbname = 'montessori_db'; $user = 'root'; $pass = '';

// Recibimos el ID del curso que manda el JavaScript
$id_curso = isset($_GET['id_curso']) ? $_GET['id_curso'] : null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!$id_curso) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Seleccioná un curso primero.']);
        exit;
    }

    // Buscamos a los alumnos que estén matriculados en ese curso específico
    $sql = "
        SELECT 
            m.id_matricula, 
            a.dni, 
            CONCAT(a.apellido, ', ', a.nombre) AS nombre_completo 
        FROM matriculas m
        JOIN alumnos a ON m.alumnos_id_alumno = a.id_alumno
        WHERE m.cursos_id_curso = ?
        ORDER BY a.apellido ASC
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_curso]);
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'ok', 'data' => $alumnos]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error de BD: ' . $e->getMessage()]);
}
?>