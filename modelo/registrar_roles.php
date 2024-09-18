<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    die();
}
include_once "../controlador/ctrlRol.php";
$rolCtrl = new ctrlRol($conexion);
$data = json_decode(file_get_contents("php://input"), true
);
if (isset($data['operacion'])) {
    if ($data['operacion'] === 'registrarRol') {
        if (isset($data['nombre']) && isset($data['descripcion'])) {
            if ($rolCtrl->registrarRol($data['nombre'], $data['descripcion'])) {
                echo json_encode(array("mensaje" => "Rol registrado correctamente"));
            } else {
                echo json_encode(array("mensaje" => "Error al registrar el rol"));
            }
        } else {
            echo json_encode(array("mensaje" => "Faltan datos en la solicitud"));
        }
    } elseif ($data['operacion'] === 'listarRoles') {
        $roles = $rolCtrl->listarRoles();
        if (!empty($roles)) {
            echo json_encode(array("mensaje" => "Lista de roles", "roles" => $roles));
        } else {
            echo json_encode(array("mensaje" => "No hay roles registrados"));
        }
    } else {
        echo json_encode(array("mensaje" => "Operacion no válida"));
    }
} else {
    echo json_encode(array("mensaje" => "No se especifico ninguna operacion"));
}
?>