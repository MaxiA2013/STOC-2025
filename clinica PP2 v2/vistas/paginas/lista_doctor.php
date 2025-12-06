    <?php
    require_once __DIR__ . "/../../modelos/conexion.php";
    require_once __DIR__ . "/../../modelos/doctor.php";

    $doctor = new Doctor();
    $doctores = $doctor->all_doctores();

    // Obtener usuarios disponibles para asignar doctor (sin doctor aún) y traer sus perfiles
    $users = new Conexion();
    //$usuariosDisponibles = $users->consultar("SELECT * FROM doctor;");

    $resUsuariosModal = $users->consultar("SELECT u.id_usuario, p.nombre, p.apellido FROM usuario u JOIN persona p ON u.persona_id_persona = p.id_persona");

    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Lista de Doctores</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/select2.min.css">
        <link rel="stylesheet" href="assets/css/modal_registro.css">
        <style>
            .form-box {
        border: 1px solid #e3e3e3;
        padding: 18px;
        border-radius: 8px;
    }
            .select2-container--default .select2-selection--single {
                height: 42px;
                padding: 6px 10px;
            }
        </style>
    </head>

    <body class="bg-light">
        <div class="container mt-5">
            <h2 class="mb-4">Registrar Doctor</h2>
            <p>Registra a un usuario ya existente como doctor, o crea uno nuevo desde el modal.</p>

            <form id="formDoctor" class="form-box" action="controladores/doctor_controlador.php" method="POST">
                <input type="hidden" name="action" value="guardar_doctor">

                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="numero_matricula_profesional">Número Matrícula</label>
                        <input type="text" id="numero_matricula_profesional" name="numero_matricula_profesional" class="form-control" required>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="precio_consulta">Precio de consulta</label>
                        <input type="number" id="precio_consulta" name="precio_consulta" step="0.01" class="form-control" required>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="usuario_id_usuario">Usuario</label>
                        <select id="usuario_id_usuario" name="usuario_id_usuario" class="form-control" required>
                            <option value="">Seleccione un usuario</option>
                            <option value="new_user">¿Usuario no registrado?</option>

                            <?php
                            if ($usuariosDisponibles && $usuarios->num_rows > 0) :
                                while ($u = $usuarios->fetch_assoc()) :
                                    $texto = $u['nombre'] . ' ' . $u['apellido'] . ' (' . $u['nombre_usuario'] . ')';
                                    $perfiles = trim($u['perfiles']);
                                    if (!empty($perfiles)) $texto .= ' - ' . $perfiles;
                            ?>
                                    <option value="<?= $u['id_usuario'] ?>"><?= htmlentities($texto) ?></option>
                            <?php
                                endwhile;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Doctor</button>
            </form>

            <hr class="my-5">
            <h3>Doctores Registrados</h3>
            <div class="col mb-3">
                <a class="btn btn-success" href="controladores/generar_excel.php" role="button">Excel</a>
                <button type="button" class="btn btn-danger">PDF</button>
            </div>

            <table class="table table-bordered table-hover mt-3" id="tabla_doctor">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Matrícula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Usuario</th>
                        <th>Precio de consulta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($doctores)) : ?>
                        <?php foreach ($doctores as $fila) : ?>
                            <tr>
                                <td><?= $fila['id_doctor'] ?></td>
                                <td><?= $fila['numero_matricula_profesional'] ?></td>
                                <td><?= $fila['nombre'] ?></td>
                                <td><?= $fila['apellido'] ?></td>
                                <td><?= $fila['nombre_usuario'] ?></td>
                                <td>$ <?= $fila['precio_consulta'] ?></td>
                                <td class="d-flex gap-2">
                                    <form action="controladores/doctor_controlador.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="eliminar_doctor">
                                        <input type="hidden" name="id_doctor" value="<?= $fila['id_doctor'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>

                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $fila['id_doctor'] ?>">Editar</button>

                                    <!-- Modal editar (igual que antes) -->
                                    <div class="modal fade" id="modalEditar<?= $fila['id_doctor'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $fila['id_doctor'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="controladores/doctor_controlador.php" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel<?= $fila['id_doctor'] ?>">Editar Doctor</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="action" value="actualizar_doctor">
                                                        <input type="hidden" name="id_doctor" value="<?= $fila['id_doctor'] ?>">

                                                        <div class="mb-3">
                                                            <label for="numero_matricula_profesional<?= $fila['id_doctor'] ?>" class="form-label">Número Matrícula</label>
                                                            <input type="text" class="form-control" id="numero_matricula_profesional<?= $fila['id_doctor'] ?>" name="numero_matricula_profesional" value="<?= $fila['numero_matricula_profesional'] ?>" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="precio_consulta<?= $fila['id_doctor'] ?>" class="form-label">Precio de consulta</label>
                                                            <input type="number" step="0.01" class="form-control" id="precio_consulta<?= $fila['id_doctor'] ?>" name="precio_consulta" value="<?= $fila['precio_consulta'] ?>" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="usuario_id_usuario<?= $fila['id_doctor'] ?>" class="form-label">Usuario</label>
                                                            <select class="form-control" id="usuario_id_usuario<?= $fila['id_doctor'] ?>" name="usuario_id_usuario" required>
                                                                <?php
                                                                while ($um = $resUsuariosModal->fetch_assoc()):
                                                                ?>
                                                                    <option value="<?= $um['id_usuario'] ?>" <?= $um['id_usuario'] == $fila['usuario_id_usuario'] ? 'selected' : '' ?>>
                                                                        <?= $um['nombre'] . ' ' . $um['apellido'] ?>
                                                                    </option>
                                                                <?php endwhile; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-success">Guardar cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin modal editar -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay doctores registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- jQuery (requerido por Select2) -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- Bootstrap Bundle -->
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <!-- Select2 -->
        <script src="assets/js/select2.min.js"></script>
        <!-- SweetAlert -->
        <script src="assets/js/sweetalert2@11.js"></script>

        <script>
            //Inicializar datatable
            $(document).ready(function() {
                $('#tabla_doctor').DataTable();
            });

            (function() {
                // Inicializar Select2
                $('#usuario_id_usuario').select2({
                    placeholder: "Buscar usuario por nombre o perfil...",
                    width: '100%',
                    allowClear: true
                });

                // Abrir modal para nuevo usuario
                $('#usuario_id_usuario').on('change', function() {
                    var val = $(this).val();
                    if (val === 'new_user') {
                        var myModal = new bootstrap.Modal(document.getElementById('modalNewUser'));
                        myModal.show();
                        $(this).val(null).trigger('change');
                    }
                });

                // Interceptar envío del formulario de registro cuando el modal está abierto
                // y ejecutar la creación persona->usuario->doctor vía AJAX.
                $('#modalNewUser').on('shown.bs.modal', function() {
                    // Capturamos el submit del formulario incluido (registro.php)
                    var $registroForm = $('#registroForm');

                    // Evitar que se agregue múltiples handlers
                    $registroForm.off('submit.registrarDoctorModal');

                    $registroForm.on('submit.registrarDoctorModal', function(e) {
                        //e.preventDefault();

                        // recoger datos del modal (form de registro)
                        // dentro del handler submit.registrarDoctorModal (en lista_doctor.php)
                        var formData = new FormData(this);

                        // agregar acción para el controlador AJAX específico:
                        formData.set('action', 'registrarCompleto'); // asegura que exista action correcto

                        // marcar que viene desde lista_doctor si lo necesitás
                        formData.append('from_lista_doctor', '1');

                        // agregar datos del formulario principal (matrícula + precio)
                        formData.append('numero_matricula_profesional', $('#numero_matricula_profesional').val());
                        formData.append('precio_consulta', $('#precio_consulta').val());


                        // Llamar al endpoint AJAX que crea persona->usuario(perfil=2)->doctor
                        fetch('controladores/doctor_ajax_controlador.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(r => r.json())
                            .then(resp => {
                                if (resp.status === 'ok') {
                                    // cerrar modal, notificar y refrescar lista
                                    Swal.fire({
                                        title: 'Guardado',
                                        text: 'Usuario doctor creado correctamente.',
                                        icon: 'success',
                                        timer: 1400,
                                        showConfirmButton: false
                                    });

                                    // cerrar modal
                                    var myModalEl = document.getElementById('modalNewUser');
                                    var modal = bootstrap.Modal.getInstance(myModalEl);
                                    modal.hide();

                                    // recargar la página para que aparezca el doctor en la tabla
                                    setTimeout(function() {
                                        location.reload();
                                    }, 600);
                                } else {
                                    Swal.fire('Error', resp.message || 'Error en servidor', 'error');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire('Error', 'Error de red', 'error');
                            });

                    }); // end submit handler
                }); // end on shown.bs.modal


            })();
        </script>

        <!-- Modal para "Usuario no registrado?" (incluye tu registro.php) -->
        <div class="modal fade" id="modalNewUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar nuevo usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" id="modalNewUserBody">
                        <form id="" class="needs-validation" novalidate action="controladores/doctor_ajax_controlador.php" method="POST">
                            <input type="hidden" name="action" value="registrarCompleto" />

                            <h2>Registro de Nuevos Usuarios</h2>

                            <!-- Barra de Progreso -->
                            <div class="progreso-container">
                                <div class="progreso-item active" id="step1">1</div>
                                <div class="progreso-item" id="step2">2</div>
                            </div>

                            <!-- === PASO 1 === -->
                            <div class="pagina active" id="pagina1">
                                <h5 class="mb-3">Datos de Persona</h5>

                                <div class="mb-3">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    <div class="error-message" id="error-nombre"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="apellido">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                                    <div class="error-message" id="error-apellido"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                                    <div class="error-message" id="error-fecha_nacimiento"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="sexo">Sexo</label>
                                    <select class="form-select" id="sexo" name="sexo" required>
                                        <option value="">Seleccione...</option>
                                        <option value="1">Masculino</option>
                                        <option value="2">Femenino</option>
                                    </select>
                                    <div class="error-message" id="error-sexo"></div>
                                </div>

                                <button type="button" class="btn btn-primary w-100" onclick="validarPaso(1)">Siguiente</button>
                            </div>

                            <!-- === PASO 2 === -->
                            <div class="pagina" id="pagina2">
                                <h5 class="mb-3">Datos de Usuario</h5>

                                <div class="mb-3">
                                    <label for="nombre_usuario">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                                    <div class="error-message" id="error-nombre_usuario"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="error-message" id="error-email"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                    <div class="error-message" id="error-password"></div>
                                </div>

                                <input type="hidden" name="perfil_id_perfil" value="2">

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" onclick="mostrarPaso(1)">Anterior</button>
                                    <button type="submit" class="btn btn-success">Registrarse</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/js/validaciones/form_multipasos.js"></script>
    </body>

    </html>