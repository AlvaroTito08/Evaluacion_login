<?php
include_once "../conexion/config.php";

class ctrlPersona {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function existePersona($correo) {
        $sql = "SELECT * FROM persona WHERE correo = :correo";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        return $stmt->rowCount() > 0;
    }

    public function registrarPersona($nombres, $apellido1, $apellido2, $fecha_nacimiento, $correo, $telefono) {
        $sql = "INSERT INTO persona (nombres, apellido1, apellido2, fecha_nacimiento, correo, telefono)
                VALUES (:nombres, :apellido1, :apellido2, :fecha_nacimiento, :correo, :telefono)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombres' => $nombres,
            ':apellido1' => $apellido1,
            ':apellido2' => $apellido2,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':correo' => $correo,
            ':telefono' => $telefono
        ]);
    }
    public function listarPersonas() {
        $sql = "SELECT * FROM persona";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>