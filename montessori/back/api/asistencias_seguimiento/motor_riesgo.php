<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../helpers/auth.php';
include_once '../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

if(isset($_GET['id_matricula'])) {
    $id_matricula = $_GET['id_matricula'];

    $umbral_alerta = 5;  
    $umbral_critico = 10; 

    $query = "SELECT COUNT(*) as total_ausencias 
              FROM asistencia_diaria 
              WHERE matriculas_id_matricula = :id_matricula 
              AND estado = 'Ausente'";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id_matricula", $id_matricula);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_faltas = $row['total_ausencias'];

    $nivel_riesgo = "Normal";
    $accion_recomendada = "Ninguna";
    $requiere_intervencion = false;

    if ($total_faltas >= $umbral_critico) {
        $nivel_riesgo = "Crítico";
        $accion_recomendada = "Ejecutar Protocolo de Intervención (Citar Tutor / Gabinete)";
        $requiere_intervencion = true;
    } elseif ($total_faltas >= $umbral_alerta) {
        $nivel_riesgo = "Precaución";
        $accion_recomendada = "Notificar al Docente / Seguimiento";
    }

    http_response_code(200);
    echo json_encode([
        "id_matricula" => $id_matricula,
        "estadisticas" => [
            "total_inasistencias" => $total_faltas,
            "limite_permitido" => $umbral_critico
        ],
        "evaluacion" => [
            "nivel_riesgo" => $nivel_riesgo,
            "requiere_intervencion" => $requiere_intervencion,
            "accion_recomendada" => $accion_recomendada
        ]
    ]);

} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Falta especificar el ID de la matrícula para evaluar el riesgo."]);
}
?>