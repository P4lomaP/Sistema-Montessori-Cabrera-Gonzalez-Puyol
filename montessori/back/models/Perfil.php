<?php
class Perfil {
    private $conn;
    private $table_name = "perfiles";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerResumen() {
    
        $query = "SELECT p.id_perfil, p.nombre_perfil, p.descripcion,
                         COUNT(DISTINCT up.usuarios_id_usuario) as cantidad_usuarios,
                         GROUP_CONCAT(DISTINCT CONCAT(perm.modulo, '_', perm.accion) SEPARATOR ', ') as permisos
                  FROM " . $this->table_name . " p
                  LEFT JOIN usuario_perfil up ON p.id_perfil = up.perfiles_id_perfil
                  LEFT JOIN perfil_permiso pp ON p.id_perfil = pp.perfiles_id_perfil
                  LEFT JOIN permisos perm ON pp.permisos_id_permiso = perm.id_permiso
                  GROUP BY p.id_perfil";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function crear($nombre, $descripcion) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nombre_perfil = :nombre, descripcion = :descripcion";
                  
        $stmt = $this->conn->prepare($query);

        $nombre = htmlspecialchars(strip_tags($nombre));
        $descripcion = htmlspecialchars(strip_tags($descripcion));

        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descripcion", $descripcion);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function asignarPermiso($id_perfil, $id_permiso) {
        $query = "INSERT IGNORE INTO perfil_permiso (perfiles_id_perfil, permisos_id_permiso) 
                  VALUES (:id_perfil, :id_permiso)";
                  
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_perfil", $id_perfil);
        $stmt->bindParam(":id_permiso", $id_permiso);

        if($stmt->execute()) {
            
            if($stmt->rowCount() > 0) {
                return true;
            }
        }
        return false;
    }
}
?>