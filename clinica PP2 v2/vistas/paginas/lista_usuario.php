<?php 
require_once "modelos/usuarios.php";
$con = new Usuario("","","","","");
$lista_usuarios = $con->all_usuarios();
?>
<h2>Lista de Usuarios</h2>
<div class="row">
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