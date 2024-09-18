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
            $usuario = $usuarioCtrl->verificarUsuario($nombre, $password);
            if ($usuario) {
                session_start();
                $_SESSION['usuario'] = $usuario['nombre'];
                echo json_encode(array("mensaje" => "Login exitoso", "usuario" => $usuario['nombre']));
            } else {
                echo json_encode(array("mensaje" => "Credenciales incorrectas"));
            }
        } else {
            echo json_encode(array("mensaje" => "Faltan datos en la solicitud"));
        }
    } elseif ($data['operacion'] === 'logout') {
        session_start();
        if (isset($_SESSION['usuario'])) {
            session_destroy();
            echo json_encode(array("mensaje" => "Logout exitoso"));
        } else {
            echo json_encode(array("mensaje" => "No hay sesión activa"));
        }
    } else {
        echo json_encode(array("mensaje" => "Operación no válida"));
    }
} else {
    echo json_encode(array("mensaje" => "No se especifico ninguna operacion"));
}
?>