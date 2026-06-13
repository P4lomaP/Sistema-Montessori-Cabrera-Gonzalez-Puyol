<?php
class Asistencia {
    private $conn;
    private $table_name = "asistencia_diaria";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar($fecha, $estado, $id_matricula, $id_usuario) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (fecha, estado, matriculas_id_matricula, usuarios_id_usuario) 
                  VALUES (:fecha, :estado, :id_matricula, :id_usuario)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":id_matricula", $id_matricula);
        $stmt->bindParam(":id_usuario", $id_usuario); 

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function obtenerHistorialPorAlumno($id_matricula) {
        $query = "SELECT fecha, estado 
                  FROM asistencia_diaria 
                  WHERE matriculas_id_matricula = :id_matricula 
                  ORDER BY fecha DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_matricula", $id_matricula);
        $stmt->execute();
        
        return $stmt;
    }

    public function obtenerAlertasDashboard($umbral_faltas = 15) {
        $query = "SELECT m.alumnos_id_alumno, a.nombre, a.apellido, c.anio_grado, c.division, COUNT(ad.id_asistencia) as total_faltas
                  FROM asistencia_diaria ad
                  INNER JOIN matriculas m ON ad.matriculas_id_matricula = m.id_matricula
                  INNER JOIN alumnos a ON m.alumnos_id_alumno = a.id_alumno
                  INNER JOIN cursos c ON m.cursos_id_curso = c.id_curso
                  WHERE ad.estado = 'Ausente'
                  GROUP BY m.alumnos_id_alumno, a.nombre, a.apellido, c.anio_grado, c.division
                  HAVING total_faltas >= :umbral
                  ORDER BY total_faltas DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":umbral", $umbral_faltas, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }
}
?>