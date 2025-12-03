<?php

<<<<<<< HEAD
class Conexion {
=======
class Conexion
{
>>>>>>> origin/mi-ramita
    private $_con;
    private $servidor;
    private $usuarios;
    private $password;
    private $base_datos;

<<<<<<< HEAD
    public function __construct() {
=======
    public function __construct()
    {
>>>>>>> origin/mi-ramita
        $this->servidor = "localhost";
        $this->usuarios = "root";
        $this->password = "";
        $this->base_datos = "clinica";
    }

<<<<<<< HEAD
    public function conectar() {
=======
    public function conectar()
    {
>>>>>>> origin/mi-ramita
        $this->_con = new mysqli($this->servidor, $this->usuarios, $this->password, $this->base_datos);
        if ($this->_con->connect_error) {
            die("Conexión fallida: " . $this->_con->connect_error);
        } else {
            //echo "Conexión exitosa.";
        }
    }

<<<<<<< HEAD
    public function desconectar() {
        $this->_con->close();
    }

    public function consultar($query) {
=======
    public function desconectar()
    {
        $this->_con->close();
    }

    public function consultar($query)
    {
>>>>>>> origin/mi-ramita
        $this->conectar();
        $resultado = $this->_con->query($query);
        $this->desconectar();
        return $resultado;
    }

<<<<<<< HEAD
    public function consultarArray($query) {
    $this->conectar();
    $resultado = $this->_con->query($query);
    $datos = [];
    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }
    }
    $this->desconectar();
    return $datos;
}


    public function insertar($query) {
=======
    public function consultarArray($query)
    {
        $this->conectar();
        $resultado = $this->_con->query($query);
        $datos = [];
        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $datos[] = $fila;
            }
        }
        $this->desconectar();
        return $datos;
    }


    public function insertar($query)
    {
>>>>>>> origin/mi-ramita
        $this->conectar();
        $this->_con->query($query);
        $id = $this->_con->insert_id;
        $this->desconectar();
        return $id;
    }
<<<<<<< HEAD
    public function actualizar($query) {
=======
    
    public function actualizar($query)
    {
>>>>>>> origin/mi-ramita
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

<<<<<<< HEAD
    public function eliminar($query){
=======
    public function eliminar($query)
    {
>>>>>>> origin/mi-ramita
        $this->conectar();
        $this->_con->query($query);
        $this->_con->commit();
        $this->desconectar();
        return true;
    }

<<<<<<< HEAD
    public function getConexion(){
        $this->conectar();
        return $this->_con;
    }


}
?>
=======
    public function getConexion()
    {
        $this->conectar();
        return $this->_con;
    }
}
>>>>>>> origin/mi-ramita
