<?php
require_once "conexion.php";

class Doctor
{
    private $id_doctor;
    private $numero_matricula_profesional;
    private $usuario_id_usuario;
    private $precio_consulta;

    public function __construct($id_doctor = '', $numero_matricula_profesional = '', $usuario_id_usuario = '', $precio_consulta = '')
    {
        $this->id_doctor = $id_doctor;
        $this->numero_matricula_profesional = $numero_matricula_profesional;
        $this->usuario_id_usuario = $usuario_id_usuario;
        $this->precio_consulta = $precio_consulta;
    }

    public function guardar()
    {
        $conexion = new Conexion();
        $query = "INSERT INTO doctor (numero_matricula_profesional, usuario_id_usuario, precio_consulta) 
                  VALUES ('$this->numero_matricula_profesional', '$this->usuario_id_usuario', '$this->precio_consulta')";
        return $conexion->insertar($query);
    }

    public function actualizar()
    {
        $conexion = new Conexion();
        $query = "UPDATE doctor 
                  SET numero_matricula_profesional = '$this->numero_matricula_profesional', 
                      usuario_id_usuario = '$this->usuario_id_usuario', 
                      precio_consulta = '$this->precio_consulta'
                  WHERE id_doctor = '$this->id_doctor'";
        return $conexion->actualizar($query);
    }

    /**
     * Eliminar doctor con eliminación en cascada de agendas y turnos.
     * Primero borra turnos ligados a agendas del doctor,
     * luego borra las agendas del doctor,
     * y finalmente borra el registro en doctor.
     */
    public function eliminar($id)
    {
        $conexion = new Conexion();

        // 1) Eliminar turnos asociados a las agendas de este doctor
        // Usa DELETE con JOIN para borrar sólo los turnos relacionados.
        $queryTurnos = "DELETE t FROM turno t
                        INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
                        WHERE a.doctor_id_doctor = '$id'";
        $conexion->eliminar($queryTurnos);

        // 2) Eliminar agendas del doctor
        $queryAgendas = "DELETE FROM agenda WHERE doctor_id_doctor = '$id'";
        $conexion->eliminar($queryAgendas);

        // 3) Eliminar el doctor
        $queryDoctor = "DELETE FROM doctor WHERE id_doctor = '$id'";
        return $conexion->eliminar($queryDoctor);
    }

    //busca los doctores relacionando la tabla personas para obtener atributos de nombre y apellido
    public function all_doctores()
    {
        $conexion = new Conexion();
        $query = "SELECT d.id_doctor, d.numero_matricula_profesional, d.precio_consulta,
                        d.usuario_id_usuario,
                        u.nombre_usuario, p.nombre, p.apellido
                FROM doctor d
                JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
                JOIN persona p ON u.persona_id_persona = p.id_persona";
        return $conexion->consultar($query);
    }
    //busca los doctores directamente de la tabla doctores
    public function todos_docs()
    {
        $conexion = new Conexion();
        $query = "SELECT * FROM doctor";
        return $conexion->consultar($query);
    }

    // Obtener usuarios disponibles para asignar doctor (sin doctor aún) y traer sus perfiles
    public function userDisp()
    {
        $conexion = new Conexion();
        $query = "
            SELECT u.id_usuario, p.nombre, p.apellido, u.nombre_usuario,
                GROUP_CONCAT(IFNULL(per.nombre_perfil,'') SEPARATOR ', ') AS perfiles
            FROM usuario u
            JOIN persona p ON u.persona_id_persona = p.id_persona
            LEFT JOIN usuario_has_perfil up ON up.usuario_id_usuario = u.id_usuario
            LEFT JOIN perfil per ON per.id_perfil = up.perfil_id_perfil
            WHERE u.id_usuario NOT IN (SELECT usuario_id_usuario FROM doctor)
            GROUP BY u.id_usuario
            ORDER BY p.nombre, p.apellido ";
        return $conexion->consultar($query);

    }

    // getters / setters (sin cambios)
    public function getId_doctor()
    {
        return $this->id_doctor;
    }

    public function setId_doctor($id_doctor)
    {
        $this->id_doctor = $id_doctor;
        return $this;
    }

    public function getNumero_matricula_profesional()
    {
        return $this->numero_matricula_profesional;
    }

    public function setNumero_matricula_profesional($numero_matricula_profesional)
    {
        $this->numero_matricula_profesional = $numero_matricula_profesional;
        return $this;
    }

    public function getUsuario_id_usuario()
    {
        return $this->usuario_id_usuario;
    }

    public function setUsuario_id_usuario($usuario_id_usuario)
    {
        $this->usuario_id_usuario = $usuario_id_usuario;
        return $this;
    }

    public function getPrecio_Consulta()
    {
        return $this->precio_consulta;
    }

    public function setPrecio_Consulta($precio_consulta)
    {
        $this->precio_consulta = $precio_consulta;
        return $this;
    }
}
