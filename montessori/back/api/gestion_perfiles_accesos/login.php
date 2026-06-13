<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../../config/Database.php';
include_once '../../models/Usuario.php'; 

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->dni) && !empty($data->password)) {
    
    $resultado_login = $usuario->login($data->dni, $data->password);

    if($resultado_login === "exito") {
        if($usuario->estado_activo == 1) {
            $usuario->obtenerPermisos();

            http_response_code(200); 
            echo json_encode([
                "mensaje" => "Login exitoso",
                "token" => $usuario->token_sesion,
                "usuario" => [
                    "id" => $usuario->id_usuario,
                    "nombre" => $usuario->nombre,
                    "apellido" => $usuario->apellido,
                    "permisos" => $usuario->permisos
                ]
            ]);
        } else {
            http_response_code(401); 
            echo json_encode(["mensaje" => "Usuario inactivo, contacte a dirección."]);
        }
    } else if ($resultado_login === "pendiente_aprobacion") {
        // NUEVO: Le avisamos que su clave ya se guardó, pero le falta la habilitación final
        http_response_code(403);
        echo json_encode([
            "mensaje" => "Su cuenta se encuentra en revisión. Aguarde a que Dirección habilite su acceso.",
            "requiere_pin" => false 
        ]);
    } else if ($resultado_login === "bloqueado") {
        http_response_code(403);
        echo json_encode([
            "mensaje" => "Cuenta bloqueada por seguridad. Utilice la recuperación por PIN.",
            "requiere_pin" => true 
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["mensaje" => "DNI o contraseña incorrectos."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["mensaje" => "Faltan credenciales."]);
}
?>