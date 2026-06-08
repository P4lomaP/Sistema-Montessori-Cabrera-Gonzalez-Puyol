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
}
?>