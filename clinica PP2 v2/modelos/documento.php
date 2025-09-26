<?php
require_once "conexion.php";

class Documento {
    private int $id_documento;
    private string $tipo_documento; // nombre del documento
    private string $descripcion;    // descripción del documento
    private int $obligatorio;       // 1 = sí, 0 = no

    // --- Getters y setters ---
    public function getIdDocumento(): int {
        return $this->id_documento;
    }
    public function setIdDocumento($id_documento): self {
        $this->id_documento = $id_documento;
        return $this;
    }

    public function getTipoDocumento(): string {
        return $this->tipo_documento;
    }
    public function setTipoDocumento($tipo_documento): self {
        $this->tipo_documento = $tipo_documento;
        return $this;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion): self {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getObligatorio(): int {
        return $this->obligatorio;
    }
    public function setObligatorio($obligatorio): self {
        $this->obligatorio = (int)$obligatorio;
        return $this;
    }

    // --- Métodos CRUD ---
    public function guardarDocumento() {
        $conn = new Conexion();
        $query = "INSERT INTO documento (tipo_documento, descripcion, obligatorio) 
                  VALUES ('$this->tipo_documento', '$this->descripcion', $this->obligatorio)";
        $id = $conn->insertar($query);
        $this->setIdDocumento($id);
    }

    public function actualizarDocumento() {
        $conn = new Conexion();
        $query = "UPDATE documento 
                  SET tipo_documento = '$this->tipo_documento', 
                      descripcion = '$this->descripcion', 
                      obligatorio = $this->obligatorio
                  WHERE id_documento = $this->id_documento";
        $conn->actualizar($query);
    }

    public function eliminarDocumento() {
        $conn = new Conexion();
        $query = "DELETE FROM documento WHERE id_documento = $this->id_documento";
        $conn->eliminar($query);
    }

    public function consultarVariosDocumento() {
        $conn = new Conexion();
        $query = "SELECT * FROM documento";
        return $conn->consultar($query);
    }
}
?>
