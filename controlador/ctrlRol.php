<?php
include_once "../conexion/config.php";
class ctrlRol {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    public function registrarRol($nombre, $descripcion) {
        $sql = "INSERT INTO rol (nombre, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);

        return $stmt->execute();
    }
    
    public function listarRoles() {
        $sql = "SELECT * FROM rol";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>