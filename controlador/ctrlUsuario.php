<?php
include_once "../conexion/config.php";
class ctrlUsuario {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    public function registrarUsuario($nombre, $password, $id_persona) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nombre, password, persona_id_persona) VALUES (:nombre, :password, :id_persona)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id_persona', $id_persona);
        return $stmt->execute();
    }
    public function listarUsuarios() {
        $sql = "SELECT id_usuario, nombre FROM usuarios";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function verificarUsuario($nombre, $password) {
        $sql = "SELECT * FROM usuarios WHERE nombre = :nombre";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        } else {
            return false;
        }
    }
}
?>