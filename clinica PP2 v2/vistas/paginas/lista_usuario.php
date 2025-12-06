<?php
require_once "modelos/usuarios.php";
$con = new Usuario("", "", "", "", "");
$lista_usuarios = $con->all_usuarios();

$is_admin = isset($_SESSION['nombre_perfil']) && $_SESSION['nombre_perfil'] === 'administrador';
$page = $_GET['page'] ?? '';
?>
<h2>Lista de Usuarios</h2>
<div class="row">
    <!-- ================= FORMULARIO SIMPLE (page=lista_usuario && admin) ===================== -->
    <?php if ($page === "lista_usuario" && $is_admin): ?>
        <div class="form-container mt-4">
            <h2>Registrar Nuevo Usuario</h2>

            <form action="controladores/login.controlador.php" method="POST">
                <input type="hidden" name="action" value="registro_simple">

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Apellido</label>
                    <input type="text" class="form-control" name="apellido" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" class="form-control" name="fecha_nacimiento" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Sexo</label>
                    <select class="form-select" name="sexo" required>
                        <option value="1">Masculino</option>
                        <option value="2">Femenino</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Nombre de Usuario</label>
                    <input type="text" class="form-control" name="nombre_usuario" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Contraseña</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <div class="col-12 col-md-6 col-lg-20">
                    <label>Perfil</label>
                    <select class="form-select" id="perfil" name="perfil_id_perfil" onchange="toggleDoctorFields()">
                        <option value="1">Administrador</option>
                        <option value="2">Doctor</option>
                        <option value="3" selected>Paciente</option>
                    </select>
                </div>

                <!-- Campos extra si el perfil es Doctor -->
                <div id="doctorFields" style="display:none;">
                    <div class="col-12 col-md-6 col-lg-20">
                        <label>Número de Matrícula Profesional</label>
                        <input type="text" class="form-control" name="numero_matricula_profesional">
                    </div>

                    <div class="col-12 col-md-6 col-lg-20">
                        <label>Precio Consulta</label>
                        <input type="number" class="form-control" name="precio_consulta" step="0.01">
                    </div>
                </div>

                <button class="btn btn-primary w-100" type="submit">Registrar</button>
            </form>
        </div>

        <script>
            function toggleDoctorFields() {
                const perfil = document.getElementById("perfil").value;
                document.getElementById("doctorFields").style.display = (perfil == "2") ? "block" : "none";
            }
            window.onload = toggleDoctorFields;
        </script>
    <?php endif; ?>

    <div class="col">
        <table class="table table-striped" id="tabla_usuarios">
            <thead>
                <tr>
                    <th>id</th>
                    <th>username</th>
                    <th>email</th>
                    <th>nombre</th>
                    <th>apellido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($lista_usuarios as $row) {
                ?>
                    <tr>
                        <td><?php echo $row['id_usuario'] ?></td>
                        <td><?php echo $row['nombre_usuario'] ?></td>
                        <td><?php echo $row['email'] ?></td>
                        <td><?php echo $row['nombre'] ?></td>
                        <td><?php echo $row['apellido'] ?></td>
                        <td>
                            <div>
                                <form action="controladores/usuarios/usuarios_controlador.php" method="post">
                                    <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario'] ?>">
                                    <input type="hidden" name="action" value="eliminacion">
                                    <button type="submit"><i class="fa-solid fa-delete-left"></i></button>
                                </form>
                            </div>


                            <div>
                                <form action="controladores/usuarios/usuarios_controlador.php" method="post">
                                    <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario'] ?>">
                                    <input type="hidden" name="action" value="actualizacion">
                                    <button type="submit"><i class="fa-solid fa-pen-nib"></i></button>
                                </form>
                            </div>


                        </td>
                    <?php } ?>
                    </tr>
            </tbody>
        </table>
    </div>

    <script src="assets/js/validaciones/usuarios.js"></script>
    <script type="module">
        //Inicializar datatable
        $(document).ready(function() {
            $('#tabla_usuarios').DataTable();
        });
    </script>