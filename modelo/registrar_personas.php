<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    die();
}
include_once "../controlador/ctrlPersona.php";
$personaCtrl = new ctrlPersona($conexion);
$data = json_decode(file_get_contents("php://input"), true
);
if (isset($data['operacion'])) {
    $operacion = $data['operacion'];
    if ($operacion === 'registrarPersona') {
        if (isset($data['nombres'], $data['apellido1'], $data['apellido2'], $data['fecha_nacimiento'], $data['correo'], $data['telefono'])) {
            $nombres = $data['nombres'];
            $apellido1 = $data['apellido1'];
            $apellido2 = $data['apellido2'];
            $fecha_nacimiento = $data['fecha_nacimiento'];
            $correo = $data['correo'];
            $telefono = $data['telefono'];

            $fecha_actual = new DateTime();
            $fecha_nacimiento_dt = new DateTime($fecha_nacimiento);
            $edad = $fecha_actual->diff($fecha_nacimiento_dt)->y;

            if ($personaCtrl->existePersona($correo)) {
                echo json_encode(array("mensaje" => "La persona ya se encuentra registrada."));
            } else {
                if ($edad >= 18) {
                    $resultado = $personaCtrl->registrarPersona($nombres, $apellido1, $apellido2, $fecha_nacimiento, $correo, $telefono);
                    echo json_encode($resultado ? array("mensaje" => "Persona registrada correctamente.") : array("mensaje" => "Error al registrar persona."));
                } else {
                    echo json_encode(array("mensaje" => "Debe ser mayor de edad para registrarse."));
                }
            }
        } else {
            echo json_encode(array("mensaje" => "Faltan datos en la solicitud."));
        }

    } elseif ($operacion === 'listarPersonas') {
        $personas = $personaCtrl->listarPersonas();
        if ($personas) {
            echo json_encode(array(
                "mensaje" => "Lista de personas",
                "personas" => $personas
            ));
        } else {
            echo json_encode(array(
                "mensaje" => "No hay personas registradas"
            ));
        }

    } else {
        echo json_encode(array("mensaje" => "Operacion no valida."));
    }
} else {
    echo json_encode(array("mensaje" => "Operacion no definida."));
}
?>