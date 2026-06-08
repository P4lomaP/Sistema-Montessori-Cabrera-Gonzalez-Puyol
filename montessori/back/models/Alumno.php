<?php
class Alumno {
    private $conn;
    private $table_name = "alumnos";
    public $id_alumno;
    public $dni;
    public $nombre;
    public $apellido;
    public $estado_activo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET dni = :dni, nombre = :nombre, apellido = :apellido, estado_activo = 1";
                  
        $stmt = $this->conn->prepare($query);

        $this->dni = htmlspecialchars(strip_tags($this->dni));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));

        $stmt->bindParam(":dni", $this->dni);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function listarActivos() {
        $query = "SELECT id_alumno, dni, nombre, apellido 
                  FROM " . $this->table_name . " 
                  WHERE estado_activo = 1 
                  ORDER BY apellido ASC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>