<?php
class Comedor {
    private $conn;
    private $table_name = "comedor_asistencia";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrarAsistencia($id_matricula, $id_usuario, $fecha, $tipo_servicio, $asiste, $hora_registro) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (matriculas_id_matricula, usuarios_id_usuario, fecha, tipo_servicio, asiste, hora_registro) 
                  VALUES (:id_matricula, :id_usuario, :fecha, :tipo_servicio, :asiste, :hora_registro)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id_matricula", $id_matricula);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":tipo_servicio", $tipo_servicio);
        $stmt->bindParam(":asiste", $asiste);
        $stmt->bindParam(":hora_registro", $hora_registro);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function solicitarExcepcion($id_docente, $fecha, $motivo) {
        $query = "INSERT INTO comedor_excepciones 
                  (usuarios_id_docente, fecha_autorizada, motivo, estado) 
                  VALUES (:id_docente, :fecha, :motivo, 'Pendiente')";
                  
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id_docente", $id_docente);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":motivo", $motivo);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function aprobarExcepcion($id_excepcion, $id_directivo) {
        $query = "UPDATE comedor_excepciones 
                  SET usuarios_id_directivo = :id_directivo, 
                      estado = 'Aprobada' 
                  WHERE id_excepcion = :id_excepcion";
                  
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id_directivo", $id_directivo);
        $stmt->bindParam(":id_excepcion", $id_excepcion);

        if($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
    public function registrarSobrantes($id_usuario, $fecha, $tipo_servicio, $cantidad, $observacion) {
        $query = "INSERT INTO comedor_sobrantes 
                  (usuarios_id_usuario, fecha, tipo_servicio, cantidad_raciones, observacion) 
                  VALUES (:id_usuario, :fecha, :tipo, :cantidad, :obs)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":tipo", $tipo_servicio);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":obs", $observacion);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function obtenerTotalesCocina($fecha) {
        $query = "SELECT tipo_servicio, SUM(asiste) as total_raciones 
                  FROM " . $this->table_name . " 
                  WHERE fecha = :fecha AND asiste = 1 
                  GROUP BY tipo_servicio";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();
        return $stmt;
    }

    public function obtenerHistorial($fecha_inicio, $fecha_fin) {
        $query = "SELECT fecha, tipo_servicio, SUM(asiste) as total_asistencias 
                  FROM " . $this->table_name . " 
                  WHERE fecha BETWEEN :inicio AND :fin AND asiste = 1
                  GROUP BY fecha, tipo_servicio 
                  ORDER BY fecha DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":inicio", $fecha_inicio);
        $stmt->bindParam(":fin", $fecha_fin);
        $stmt->execute();
        return $stmt;
    }

    public function obtenerAlertasNutricionales($fecha) {
        $query = "SELECT a.nombre, a.apellido, a.restricciones_alimentarias, ca.tipo_servicio
                  FROM " . $this->table_name . " ca
                  INNER JOIN matriculas m ON ca.matriculas_id_matricula = m.id_matricula
                  INNER JOIN alumnos a ON m.alumnos_id_alumno = a.id_alumno
                  WHERE ca.fecha = :fecha 
                  AND ca.asiste = 1 
                  AND a.restricciones_alimentarias IS NOT NULL 
                  AND a.restricciones_alimentarias != ''";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();
        return $stmt;
    }

    public function guardarMenu($fecha, $tipo_servicio, $descripcion) {
        $query = "INSERT INTO comedor_menu (fecha, tipo_servicio, descripcion) 
                  VALUES (:fecha, :tipo, :desc)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":tipo", $tipo_servicio);
        $stmt->bindParam(":desc", $descripcion);
        return $stmt->execute();
    }

    public function registrarIngresoInventario($id_usuario, $insumo, $cantidad, $comprobante) {
        $query = "INSERT INTO comedor_inventario (usuarios_id_usuario, insumo, cantidad, numero_comprobante) 
                  VALUES (:id_usuario, :insumo, :cant, :comprobante)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":insumo", $insumo);
        $stmt->bindParam(":cant", $cantidad);
        $stmt->bindParam(":comprobante", $comprobante);
        return $stmt->execute();
    }  
}
?>