<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";
    public $id_usuario;
    public $dni;
    public $nombre;
    public $apellido;
    public $correo;
    public $estado_activo;
    public $permisos = [];
    public $intentos_fallidos;
    public $token_sesion;
    public $pin_recuperacion;
    public $estado_desbloqueo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($dni_input, $password_input) {
        $query = "SELECT 
                    id_usuario, 
                    dni,
                    nombre, 
                    apellido, 
                    password_hash, 
                    estado_activo, 
                    intentos_fallidos, 
                    estado_desbloqueo 
                  FROM " . $this->table_name . " 
                  WHERE dni = :dni 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dni", $dni_input);
        $stmt->execute();

        if ($stmt->rowCount() <= 0) {
            return "no_existe";
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id_usuario = $row['id_usuario'];
        $this->dni = $row['dni'];
        $this->nombre = $row['nombre'];
        $this->apellido = $row['apellido'];
        $this->estado_activo = $row['estado_activo'];
        $this->intentos_fallidos = $row['intentos_fallidos'];
        $this->estado_desbloqueo = $row['estado_desbloqueo'];

        if ($row['estado_desbloqueo'] === 'pendiente_aprobacion') {
            return "pendiente_aprobacion";
        }

        if ($row['estado_desbloqueo'] === 'bloqueado' || $row['intentos_fallidos'] >= 3) {
            $query_bloqueo = "UPDATE " . $this->table_name . "
                              SET estado_desbloqueo = 'bloqueado'
                              WHERE id_usuario = :id_usuario";

            $stmt_bloqueo = $this->conn->prepare($query_bloqueo);
            $stmt_bloqueo->bindParam(":id_usuario", $this->id_usuario);
            $stmt_bloqueo->execute();

            return "bloqueado";
        }

        if (password_verify($password_input, $row['password_hash'])) {
            $token = bin2hex(random_bytes(32));
            $expiracion = date('Y-m-d H:i:s', strtotime('+2 hours'));

            $query_update = "UPDATE " . $this->table_name . " 
                             SET intentos_fallidos = 0, 
                                 token_sesion = :token, 
                                 token_expiracion = :expiracion,
                                 estado_desbloqueo = 'ninguno'
                             WHERE id_usuario = :id_usuario";

            $stmt_update = $this->conn->prepare($query_update);
            $stmt_update->bindParam(":token", $token);
            $stmt_update->bindParam(":expiracion", $expiracion);
            $stmt_update->bindParam(":id_usuario", $this->id_usuario);
            $stmt_update->execute();

            $this->token_sesion = $token;

            return "exito";
        }

        $nuevos_intentos = intval($row['intentos_fallidos']) + 1;

        if ($nuevos_intentos >= 3) {
            $query_fail = "UPDATE " . $this->table_name . " 
                           SET intentos_fallidos = :intentos,
                               estado_desbloqueo = 'bloqueado'
                           WHERE id_usuario = :id_usuario";

            $stmt_fail = $this->conn->prepare($query_fail);
            $stmt_fail->bindParam(":intentos", $nuevos_intentos);
            $stmt_fail->bindParam(":id_usuario", $this->id_usuario);
            $stmt_fail->execute();

            return "bloqueado";
        }

        $query_fail = "UPDATE " . $this->table_name . " 
                       SET intentos_fallidos = :intentos
                       WHERE id_usuario = :id_usuario";

        $stmt_fail = $this->conn->prepare($query_fail);
        $stmt_fail->bindParam(":intentos", $nuevos_intentos);
        $stmt_fail->bindParam(":id_usuario", $this->id_usuario);
        $stmt_fail->execute();

        return "credenciales_invalidas";
    }

    public function obtenerPermisos() {
        $query = "SELECT DISTINCT p.modulo, p.accion 
                  FROM usuario_perfil up
                  INNER JOIN perfil_permiso pp ON up.perfiles_id_perfil = pp.perfiles_id_perfil
                  INNER JOIN permisos p ON pp.permisos_id_permiso = p.id_permiso
                  WHERE up.usuarios_id_usuario = :id_usuario";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->execute();

        $lista_permisos = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lista_permisos[] = $row['modulo'] . "_" . $row['accion'];
        }

        $this->permisos = $lista_permisos;

        return $this->permisos;
    }

    public function registrar($password_plana) {
        $password_hasheada = password_hash($password_plana, PASSWORD_BCRYPT);

        $query = "INSERT INTO " . $this->table_name . " 
                  (dni, nombre, apellido, correo, password_hash, estado_activo, intentos_fallidos, estado_desbloqueo) 
                  VALUES (:dni, :nombre, :apellido, :correo, :password, 0, 0, 'ninguno')";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":dni", $this->dni);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":password", $password_hasheada);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function activarCuenta() {
        $query = "UPDATE " . $this->table_name . " 
                  SET estado_activo = 1 
                  WHERE id_usuario = :id_usuario";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $this->id_usuario);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function generarPinRecuperacion($dni_input) {
        $pin = sprintf("%06d", mt_rand(1, 999999));

        $query = "UPDATE " . $this->table_name . " 
                  SET pin_recuperacion = :pin,
                      estado_desbloqueo = 'bloqueado'
                  WHERE dni = :dni 
                  AND intentos_fallidos >= 3";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pin", $pin);
        $stmt->bindParam(":dni", $dni_input);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $this->pin_recuperacion = $pin;
            return true;
        }

        return false;
    }

    public function solicitarDesbloqueoConPin($dni_input, $pin_input) {
        $query = "UPDATE " . $this->table_name . " 
                  SET estado_desbloqueo = 'pendiente_aprobacion', 
                      pin_recuperacion = NULL 
                  WHERE dni = :dni 
                  AND pin_recuperacion = :pin";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dni", $dni_input);
        $stmt->bindParam(":pin", $pin_input);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function aprobarDesbloqueo() {
        $query = "UPDATE " . $this->table_name . " 
                  SET intentos_fallidos = 0, 
                      estado_desbloqueo = 'ninguno',
                      pin_recuperacion = NULL
                  WHERE id_usuario = :id_usuario 
                  AND estado_desbloqueo = 'pendiente_aprobacion'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $this->id_usuario);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function obtenerPendientes() {
        $query = "SELECT id_usuario, dni, nombre, apellido 
                  FROM " . $this->table_name . " 
                  WHERE estado_desbloqueo = 'pendiente_aprobacion'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function cerrarSesion($token_actual) {
        $query = "UPDATE " . $this->table_name . " 
                  SET token_sesion = NULL, 
                      token_expiracion = NULL 
                  WHERE token_sesion = :token";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token_actual);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function asignarPerfil($id_perfil) {
        $query = "INSERT IGNORE INTO usuario_perfil 
                  (usuarios_id_usuario, perfiles_id_perfil) 
                  VALUES (:id_usuario, :id_perfil)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":id_perfil", $id_perfil);

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function solicitarDesbloqueoYCambiarClave($dni_input, $pin_input, $nueva_clave) {
        $query_check = "SELECT id_usuario 
                        FROM " . $this->table_name . " 
                        WHERE dni = :dni 
                        AND pin_recuperacion = :pin 
                        AND intentos_fallidos >= 3
                        LIMIT 1";

        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(":dni", $dni_input);
        $stmt_check->bindParam(":pin", $pin_input);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            $password_hasheada = password_hash($nueva_clave, PASSWORD_BCRYPT);

            $query_update = "UPDATE " . $this->table_name . " 
                             SET password_hash = :password, 
                                 pin_recuperacion = NULL, 
                                 estado_desbloqueo = 'pendiente_aprobacion'
                             WHERE dni = :dni";

            $stmt_update = $this->conn->prepare($query_update);
            $stmt_update->bindParam(":password", $password_hasheada);
            $stmt_update->bindParam(":dni", $dni_input);

            if ($stmt_update->execute()) {
                return true;
            }
        }

        return false;
    }
}
?>