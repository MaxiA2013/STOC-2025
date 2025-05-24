<?php
#tabla maestra contacto :Bianca
require_once("conexion.php");
    class Contacto{
        private $id_contacto;
        private $descripcion;

        /**
         * Get the value of id_contacto
         */ 
        public function getId_contacto()
        {
                return $this->id_contacto;
        }

        /**
         * Set the value of id_contacto
         *
         * @return  self
         */ 
        public function setId_contacto($id_contacto)
        {
                $this->id_contacto = $id_contacto;

                return $this;
        }

        /**
         * Get the value of descripcion
         */ 
        public function getDescripcion()
        {
                return $this->descripcion;
        }

        /**
         * Set the value of descripcion
         *
         * @return  self
         */ 
        public function setDescripcion($descripcion)
        {
                $this->descripcion = $descripcion;

                return $this;
        }

        public function __construct($descripcion){ #contructor de la tabla
            $this->setDescripcion($descripcion);
        }

        public function agregarContacto(){
                $con = new Conexion();
                $query = "INSERT INTO contacto (descripcion) VALUES ('".$this->getDescripcion()."')"; #query
                $id=$con->insertar($query); #guarda el id de la bd en la variable
                $this->setId_contacto($id); #inserta el id de la bd en la clase
        }

        public function modificarTipoContacto(){
                $con = new Conexion();
                $query = "UPDATE contacto SET descripcion WHERE id_contacto =".$this->getId_contacto(); #query
                $con->modificar($query); #actualiza
        }

        public function eliminarTipoContacto(){
                $con = new Conexion();
                $query = "UPDATE contacto SET activo = 0  WHERE id_contacto =".$this->getId_contacto(); #query
                $con->eliminar($query);
        }

        public function consultarVariosContactos(){
                $con = new Conexion();
                $query = "SELECT * FROM contacto WHERE activo = 1"; #query
                $datos = $con->consultar($query); #devuelve un array asociativo
                return $datos;
        }

        public function consultarContacto($id){
                $con = new Conexion();
                $query = "SELECT * FROM contacto WHERE id_contacto = ".$id; #query
                $datos = $con->consultar($query); #devuelve un array asociativo
                return $datos; #devuelve el primer elemento del array
        }
    }

    #$tipo= new Contacto('telefono fijo');
    #$tipo->agregarContacto();                 si anda :3
?>
