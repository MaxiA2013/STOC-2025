<?php
//En este archivo encontramos las funciones de la clase usuario.
require_once('conexion.php');

class Usuario
{
  private $id_usuario;
  private $nombre_usuario;
  private $email;
  private $password;
  private $fecha_alta;
  private $persona_id_persona;

  public function __construct($id_usuario = '', $nombre_usuario = '', $email = '', $password = '',$fecha_alta = '', $persona_id_persona = '', $clinica_id_clinica = '')
  {
    $this->id_usuario = $id_usuario;
    $this->nombre_usuario = $nombre_usuario;
    $this->email = $email;
    $this->password = $password;
    $this->fecha_alta = $fecha_alta;
    $this->persona_id_persona = $persona_id_persona;
  }

  public function guardarUsuario()
  {
    $conexion = new Conexion();
    $password = password_hash($this->password, PASSWORD_DEFAULT);

    $fecha_alta = $this->fecha_alta ?? date('Y-m-d H:i:s');

    $query = "INSERT INTO clinica.usuario (nombre_usuario, email, password, fecha_alta, persona_id_persona) VALUES ('$this->nombre_usuario', '$this->email', '$password', '$fecha_alta','$this->persona_id_persona')";
    return $conexion->insertar($query);
  }

  public function actualizarUsuario()
  {
    $conexion = new Conexion();
    $password = password_hash($this->password, PASSWORD_DEFAULT);
    $query = "UPDATE clinica.usuario SET nombre_usuario = '$this->nombre_usuario', email = '$this->email', password = '$password', persona_id_persona = '$this->persona_id_persona' WHERE id_usuario = '$this->id_usuario'";
    return $conexion->actualizar($query);
  }

  public function actualizarUltimaConexion($id_usuario)
  {
    $conexion = new Conexion();
    $query = "UPDATE clinica.usuario SET ultima_conexion = NOW() WHERE id_usuario = $id_usuario";
    echo $query; // Imprimir la consulta
    return $conexion->actualizar($query);
  }

  // Método para traer un usuario por ID
  public function traer_usuario_por_id()
  {
    $conexion = new Conexion();
    $query = "SELECT * FROM clinica.usuario WHERE id_usuario = '$this->id_usuario'";
    return $conexion->consultar($query);
  }

  // Método para actualizar solo el password
  public function actualizar_password()
  {
    $conexion = new Conexion();
    $query = "UPDATE clinica.usuario SET password = '$this->password' WHERE id_usuario = '$this->id_usuario'";
    return $conexion->actualizar($query);
  }


  public function eliminar()
  {
    $conexion = new Conexion();
    $query = "UPDATE clinica.usuario SET estado = 1 WHERE id_usuario = '$this->id_usuario'";
    return $conexion->actualizar($query);
  }

  public function validar_usuario()
  {
    $conexion = new Conexion();
    $query = "SELECT * FROM clinica.usuario u 
    INNER JOIN usuario_has_perfil up ON up.usuario_id_usuario = u.id_usuario
    WHERE nombre_usuario = '$this->nombre_usuario'";
    return $conexion->consultar($query);
  }

  public function all_usuarios()
  {
    $conexion = new Conexion();
    $query = "	SELECT * FROM usuario u INNER JOIN persona p  ON u.persona_id_persona = p.id_persona
    WHERE u.estado = 0";
    $datos = $conexion->consultar($query);
    return $datos;
  }

  //busca resultados generales mediante resultados en cualquiera de los 3 atributos
  public function buscar_cohincidencias()
  {
    $conexion = new Conexion();
    $query = "SELECT * FROM clinica.usuario WHERE nombre_usuario LIKE '$this->nombre_usuario' OR email LIKE '$this->email'";
    return $conexion->consultar($query);
  }

  /* busca si el email ya existe en la base de datos*/
  public function buscar_email()
  {
    $conexion = new Conexion();
    $query = "SELECT * FROM clinica.usuario WHERE email = '$this->email'";
    return $conexion->consultar($query);
  }

  /* busca si un usuario existe en el a base de datos mediante el nombre_usuario y devuelve un array si ya existe*/
  public function usuarioExiste()
  {
    $conexion = new Conexion();
    $query = "SELECT id_usuario FROM clinica.usuario WHERE nombre_usuario = '$this->nombre_usuario'";
    return $conexion->consultar($query);
  }

  

  /**
   * Get the value of id_usuario
   */
  public function getId_usuario()
  {
    return $this->id_usuario;
  }

  /**
   * Set the value of id_usuario
   *
   * @return  self
   */
  public function setId_usuario($id_usuario)
  {
    $this->id_usuario = $id_usuario;

    return $this;
  }

  /**
   * Get the value of nombre_usuario
   */
  public function getNombre_usuario()
  {
    return $this->nombre_usuario;
  }

  /**
   * Set the value of nombre_usuario
   *
   * @return  self
   */
  public function setNombre_usuario($nombre_usuario)
  {
    $this->nombre_usuario = $nombre_usuario;

    return $this;
  }

  /**
   * Get the value of email
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Set the value of email
   *
   * @return  self
   */
  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  /**
   * Get the value of password
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * Set the value of password
   *
   * @return  self
   */
  public function setPassword($password)
  {
    $this->password = $password;

    return $this;
  }

  /**
   * Get the value of persona_id_persona
   */
  public function getPersona_id_persona()
  {
    return $this->persona_id_persona;
  }

  /**
   * Set the value of persona_id_persona
   *
   * @return  self
   */
  public function setPersona_id_persona($persona_id_persona)
  {
    $this->persona_id_persona = $persona_id_persona;

    return $this;
  }

  /**
   * Get the value of fecha_alta
   */ 
  public function getFecha_alta()
  {
    return $this->fecha_alta;
  }

  /**
   * Set the value of fecha_alta
   *
   * @return  self
   */ 
  public function setFecha_alta($fecha_alta)
  {
    $this->fecha_alta = $fecha_alta;

    return $this;
  }
}
