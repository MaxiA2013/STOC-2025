<?php

class Conexion
{
    private $conn;
    private $servidor;
    private $usuarios;
    private $password;
    private $base_datos;

    public function __construct() // <-- CORREGIDO
    {
        $this->servidor = "localhost";
        $this->usuarios = "root";
        $this->password = "";
        $this->base_datos = "clinica";
    }

    public function conectar()
    {
        $this->conn = new mysqli($this->servidor, $this->usuarios, $this->password, $this->base_datos);
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function desconectar()
    {
        $this->conn->close();
    }

    public function consultar($query)
    {
        $this->conectar();
        $resultado = $this->conn->query($query);
        $this->desconectar();
        return $resultado;
    }

    public function insertar($query)
    {
        $this->conectar();
        $this->conn->query($query);
        $id = $this->conn->insert_id;
        $this->desconectar();
        return $id;
    }

    public function actualizar($query)
    {
        $this->conectar();
        $resultado = $this->conn->query($query);
        if (!$resultado) {
            echo "Error en la consulta: " . $this->conn->error;
            $this->desconectar();
            return false;
        }
        $this->desconectar();
        return true;
    }

    public function eliminar($query)
    {
        $this->conectar();
        $this->conn->query($query);
        $this->desconectar();
        return true;
    }
}
?>