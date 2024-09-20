<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    die();
}

include_once "../controlador/ctrlUsuario.php";
$usuarioCtrl = new ctrlUsuario($conexion);
$data = json_decode(file_get_contents("php://input"), true
);
if (isset($data['operacion'])) {
    if ($data['operacion'] === 'registrarUsuario') {
        if (isset($data['nombre']) && isset($data['password'])&& isset($data['id_persona'])) {
            $nombre = $data['nombre'];
            $password = $data['password'];
            $id_persona = $data['id_persona'];
            if ($usuarioCtrl->registrarUsuario($nombre, $password, $id_persona)) {
                echo json_encode(array("mensaje" => "Usuario registrado correctamente"));
            } else {
                echo json_encode(array("mensaje" => "Error al registrar el usuario"));
            }
        } else {
            echo json_encode(array("mensaje" => "Faltan datos en la solicitud"));
        }
    } elseif ($data['operacion'] === 'listarUsuarios') {
        $usuarios = $usuarioCtrl->listarUsuarios();
        if (!empty($usuarios)) {
            echo json_encode(array("mensaje" => "Lista de usuarios", "usuarios" => $usuarios));
        } else {
            echo json_encode(array("mensaje" => "No hay usuarios registrados"));
        }
    } elseif ($data['operacion'] === 'login') {
        if (isset($data['nombre']) && isset($data['password'])) {
            $nombre = $data['nombre'];
            $password = $data['password'];
            $usuarios = $usuarioCtrl->verificarUsuario($nombre, $password);
            if ($usuarios) {
                session_start();
                $_SESSION['usuario'] = $usuarios['nombre'];
                $_SESSION['id_usuario'] = $usuarios['id_usuario'];
                if ($usuarioCtrl->marcarActivo($usuarios['id_usuario'])) {
                    echo json_encode(array(
                        "mensaje" => "Login exitoso",
                        "usuario" => $usuarios['nombre'],
                        "estado" => true
                    ));
                } else {
                    echo json_encode(array("mensaje" => "Error al marcar el usuario como activo"));
                }
            } else {
                echo json_encode(array("mensaje" => "Credenciales incorrectas"));
            }
        } else {
            echo json_encode(array("mensaje" => "Faltan datos en la solicitud"));
        }
    
    } elseif ($data['operacion'] === 'logout') {
    
        session_start();
        if (isset($_SESSION['usuario']) && isset($_SESSION['id_usuario'])) {
            $id_usuario = $_SESSION['id_usuario'];
            if ($usuarioCtrl->marcarInactivo($id_usuario)) {
                session_destroy();
                echo json_encode(array("mensaje" => "Logout exitoso", "estado" => false));
            } else {
                echo json_encode(array("mensaje" => "Error al marcar el usuario como inactivo"));
            }
        } else {
            echo json_encode(array("mensaje" => "No hay sesión activa"));
        }
    }
}
?>