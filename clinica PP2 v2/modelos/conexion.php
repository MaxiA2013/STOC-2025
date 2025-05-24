<?php

class Conexion {
    private $_con;
    private $servidor;
    private $usuarios;
    private $password;
    private $base_datos;

    public function __construct() {
        $this->servidor = "localhost";
        $this->usuarios = "root";
        $this->password = "";
        $this->base_datos = "clinica";
    }

    public function conectar() {
        $this->_con = new mysqli($this->servidor, $this->usuarios, $this->password, $this->base_datos);
        if ($this->_con->connect_error) {
            die("Conexión fallida: " . $this->_con->connect_error);
        } else {
            //echo "Conexión exitosa.";
        }
    }

    public function desconectar() {
        $this->_con->close();
    }

    public function consultar($query) {
        $this->conectar();
        $resultado = $this->_con->query($query);
        $this->desconectar();
        return $resultado;
    }

    public function insertar($query) {
        $this->conectar();
        $this->_con->query($query);
        $id = $this->_con->insert_id;
        $this->desconectar();
        return $id;
    }
    public function actualizar($query) {
        $this->conectar();
        $resultado = $this->_con->query($query);
        if (!$resultado) {
            echo "Error en la consulta: " . $this->_con->error;
            $this->desconectar();
            return false;
        }
        $this->desconectar();
        return true;
    }

    public function eliminar($query){
        $this->conectar();
        $this->_con->query($query);
        $this->_con->commit();
        $this->desconectar();
        return true;
      }


}
?>