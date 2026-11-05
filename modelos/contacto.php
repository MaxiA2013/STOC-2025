<?php
require_once 'conexion.php';

 class Contacto{
        private $id_contacto;
        private $tipo_contacto;
        private $descripcion;

    public function getIdContacto()
    {
        return $this->id_contacto;
    }

    public function setIdContacto($id_contacto): self
    {
        $this->id_contacto = $id_contacto;

        return $this;
    }

    public function getTipoContacto() 
    {
        return $this->tipo_contacto;
    }

    public function setTipoContacto($tipo_contacto): self
    {
        $this->tipo_contacto = $tipo_contacto;

        return $this;
    }

    public function getDescripcion() 
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function guardarContacto(){
        $con = new Conexion();
        $query = "INSERT INTO contacto (tipo_contacto, descripcion) VALUES ('{$this->tipo_contacto}', '{$this->descripcion}')";
        $id = $con->insertar($query);
        $this->id_contacto = $id;
    }

    public function actualizarContacto(){
        $con = new Conexion();
        $query = "UPDATE contacto SET tipo_contacto='{$this->tipo_contacto}', descripcion='{$this->descripcion}' WHERE id_contacto={$this->id_contacto}";
        $con->actualizar($query);
    }

    public function eliminarContacto(){
        $con = new Conexion();
        $query = "DELETE FROM contacto WHERE id_contacto={$this->id_contacto}";
        $con->eliminar($query);
    }

    public function consultarContacto($id){
        $con = new Conexion();
        $query = "SELECT * FROM contacto WHERE id_contacto={$id}";
        $datos = $con->consultar($query);
    }

    public function consultarVariosContactos(){
        $con = new Conexion();
        $query = "SELECT * FROM contacto";
        $datos = $con->consultar($query);
        return $datos;
    }

    public function existeContacto(){
        $con = new Conexion();
        $query = "SELECT tipo_contacto FROM contacto WHERE tipo_contacto LIKE '$this->tipo_contacto'";
        return $con->consultar($query);
    }
 }
?>