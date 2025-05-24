<?php 
require_once "modelos/usuarios.php";
$con = new Usuario("","","","","");
$lista_usuarios = $con->all_usuarios();
?>
<h2>Lista de Usuarios</h2>
<div class="row">
    <div class="col">
    <form method="POST" action="controladores/login.controlador.php">
            <input type="hidden" name="action" value="registro" /> 
            <div class="mb-3 mt-3">
                <label for="nombre_usuario" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" placeholder="Ingrese el Nombre " name="nombre">
            </div>
            <div class="mb-3 mt-3">
                <label for="nombre_usuario" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" placeholder="Ingrese el Apellido" name="apellido">
            </div>
            <div class="mb-3 mt-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" placeholder="Ingrese la Fecha de Nacimiento" name="fecha_nacimiento">
              </div>
            <div class="mb-3 mt-3">
                <label for="nombre_usuario" class="form-label">Sexo</label>
                <select class="form-select" id="sexo" name="sexo">
                    <option value="1">Masculino</option>
                    <option value="2">Femenino</option>
                    <option value="3">Otro</option>
                </select>
            </div>
            <div class="mb-3 mt-3">
                <label for="nombre_usuario" class="form-label">Nombre Usuario</label>
                <input type="text" onfocusout="validate_nombre_usuario(event)" class="form-control" id="nombre_usuario" placeholder="Ingrese el Nombre del Usuario" name="nombre_usuario">
            </div>
            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" onfocusout="validate_email()" class="form-control" id="email" placeholder="Ingresar Correo Electrónico" name="email">
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" placeholder="Ingresar Contraseña" name="password">
            </div>
            <div class="form-check mb-3">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="remember"> Recordar Contraseña
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
        
    </div>
    <div class="col">
        <form class="row g-3">
          <div class="col-auto">
            <label for="inputPassword2" class="visually-hidden">Buscar</label>
            <input type="text" class="form-control" id="inputPassword2" placeholder="Ingrese un texto">
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Buscar</button>
          </div>
        </form>
        
        <table class="table table-striped">
            <thead> 
                <tr>
                    <th>id</th>
                    <th>username</th>
                    <th>email</th>
                    <th>nombre</th>
                    <th>apellido</th>
                    
                    <th></th>
                </tr>
            </thead>    
            <tbody>
               <?php 
               foreach($lista_usuarios as $row){
                ?>
                <tr>
                    <td><?php echo $row['id_usuario']?></td>
                    <td><?php echo $row['nombre_usuario']?></td>
                    <td><?php echo $row['email']?></td>
                    <td><?php echo $row['nombre']?></td>    
                    <td><?php echo $row['apellido']?></td>
                    <td>
                        <form action="controladores/usuarios/usuarios_controlador.php" method="post">
                            <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']?>">
                            <input type="hidden" name="action" value="eliminacion">
                            <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                        </form>
                    </td>
                    <td>
                    <form action="controladores/usuarios/usuarios_controlador.php" method="post">
                            <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']?>">
                            <input type="hidden" name="action" value="actualizacion">
                            <button type="submit"><i class="fa-solid fa-pen-nib"></i></button>
                        </form>
        
                    </td>
                <?php } ?>
                </tr>
            </tbody>
        </table>
        
        <nav aria-label="...">
          <ul class="pagination">
            <li class="page-item">
              <a class="page-link">Anterior</a>
            </li>
        
            <li class="page-item">
              <a class="page-link" href="#">Siguiente</a>
            </li>
          </ul>
        </nav>
    </div>
    
    <script src="assets/js/validaciones/usuarios.js"></script>