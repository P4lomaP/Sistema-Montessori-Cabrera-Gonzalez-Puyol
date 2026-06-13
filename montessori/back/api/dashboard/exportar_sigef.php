<?php
$host = 'localhost';
$dbname = 'montessori_db';
$user = 'root';
$pass = '';

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $filename = "Reporte_SIGEF_Montessori_" . date('Y-m-d') . ".csv";

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Fecha', 'ID Matricula Alumno', 'ID Usuario Carga', 'Estado de Asistencia']);

    $sql = "SELECT fecha, matriculas_id_matricula, usuarios_id_usuario, estado FROM asistencia_diaria ORDER BY fecha DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $row['fecha'],
            $row['matriculas_id_matricula'],
            $row['usuarios_id_usuario'],
            ucfirst($row['estado']) 
        ]);
    }

    fclose($output);
    exit;

} catch (PDOException $e) {
    echo "Error al generar el reporte: " . $e->getMessage();
}
?>