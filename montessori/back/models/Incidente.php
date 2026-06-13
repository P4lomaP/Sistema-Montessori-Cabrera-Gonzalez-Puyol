<?php
class Incidente {
    private $conn;
    private $table_name = "incidencias_conducta"; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar($id_matricula, $id_usuario, $fecha_incidencia, $gravedad, $observacion) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (matriculas_id_matricula, usuarios_id_usuario, fecha_incidencia, gravedad, observacion) 
                  VALUES (:id_matricula, :id_usuario, :fecha_incidencia, :gravedad, :observacion)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id_matricula", $id_matricula);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":fecha_incidencia", $fecha_incidencia);
        $stmt->bindParam(":gravedad", $gravedad);
        $stmt->bindParam(":observacion", $observacion);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function listarPorAlumno($id_alumno) {
        $query = "SELECT fecha_incidencia, gravedad, observacion 
                  FROM incidencia_conducta 
                  WHERE id_alumno = :id_alumno 
                  ORDER BY fecha_incidencia DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_alumno", $id_alumno);
        $stmt->execute();
        
        return $stmt;
    }

    public function registrarIntervencion($id_alumno, $id_usuario, $tipo_intervencion, $descripcion) {
        $query = "INSERT INTO asistencia_intervenciones (id_alumno, usuarios_id_usuario, tipo_intervencion, descripcion) 
                  VALUES (:id_alumno, :id_usuario, :tipo, :desc)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_alumno", $id_alumno);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":tipo", $tipo_intervencion);
        $stmt->bindParam(":desc", $descripcion);
        return $stmt->execute();
    }
}
?>