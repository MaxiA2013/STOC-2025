<?php
#tabla Contacto_Persona :Bianca
require_once "conexion.php";
require_once "contacto.php";
require_once "persona.php";
    Class Contacto_Persona{
        private $id_contacto_persona;
        private $detalles;
        private $id_persona;
        private $id_contacto;



        public function guardarContacto_Persona(){
            $con = new Conexion();
            $query = "INSERT INTO Contacto_Persona (detalles, id_contacto, id_persona) VALUES ('$this->detalles', $this->id_contacto, $this->id_contacto)";
            $id = $con->insertar($query);
            $this->id_contacto_persona = $id;
        }

        public function actualizarContacto_Persona(){
            $con = new Conexion();
            $query = "UPDATE Contacto_Persona SET detalles='$this->detalles', id_contacto=$this->id_contacto, id_persona=$this->id_persona WHERE id_contacto_persona=$this->id_contacto_persona";
            $con->modificar($query);
        }

        public function eliminarContacto_Persona(){
            $con = new Conexion();
            $query = "DELETE FROM Contacto_Persona WHERE id_contacto_persona=$this->id_contacto_persona";
            $con->eliminar($query);
        }













    }
    

?>
