<?php
require_once "modelos/obra_social.php";
require_once "modelos/paciente_obra_social.php";
require_once "modelos/doctor_obra_social.php";
require_once "modelos/Doctor_Dias.php";

$doctor = [
    "nombre" => "Silvia Beatriz Mazzaglia",
    "especialidad" => "Dermatólogo",
    "ubicacion" => "Formosa- Brandsen 1890",
    "direccion" => "Av. Siempre Viva 123",
    "subespecialidad" => "Dermatología pediátrica"
];

$obraSocial = new Obra_Social();
$todasObras = $obraSocial->consultarVariasObrasSociales();

$obrasDelUsuario = [];
if ($_SESSION['nombre_perfil'] === "paciente") {
    $po = new Paciente_Obra_Social();
    $obrasDelUsuario = $po->consultarPorPaciente($_SESSION['id_usuario']);
} elseif ($_SESSION['nombre_perfil'] === "doctor") {
    $do = new Doctor_Obra_Social();
    $id_doctor = $do->obtenerIdDoctorPorUsuario($_SESSION['id_usuario']);
    $obrasDelUsuario = $do->consultarPorDoctor($id_doctor);
}

$dd = new Doctor_Dias();
$id_doctor = $dd->obtenerIdDoctorPorUsuario($_SESSION['id_usuario']);
$diasAsignados = $dd->consultarDiasPorDoctor($id_doctor);
$diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <?php if (isset($_GET['success']) && $_GET['success'] === 'obras_actualizadas'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ Obras sociales actualizadas correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php elseif (isset($_GET['success']) && $_GET['success'] === 'dias_actualizados'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ Días laborales actualizados correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex align-items-center">
                    <img src="https://via.placeholder.com/100x100" alt="Doctor" class="rounded-circle me-3">
                    <div>
                        <h5 class="card-title mb-0"><?= $doctor['nombre'] ?></h5>
                        <p class="text-muted mb-1"><?= $doctor['especialidad'] ?></p>
                        <p class="text-muted"><i class="bi bi-geo-alt"></i> <?= $doctor['ubicacion'] ?><?= $doctor['direccion'] ?></p>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs" id="doctorTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="exp-tab" data-bs-toggle="tab" data-bs-target="#exp" type="button" role="tab">Experiencia</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cons-tab" data-bs-toggle="tab" data-bs-target="#cons" type="button" role="tab">Consultorios</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="serv-tab" data-bs-toggle="tab" data-bs-target="#serv" type="button" role="tab">Servicios y precios</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab">Usuario</button>
                </li>
            </ul>

            <div class="tab-content border p-3 bg-white shadow-sm" id="doctorTabsContent">
                <div class="tab-pane fade show active" id="exp" role="tabpanel">
                    <h6>Especialista en:</h6>
                    <ul>
                        <li><?= $doctor['subespecialidad'] ?></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="cons" role="tabpanel">
                    <p>Consultorio privado - <?= $doctor['ubicacion'] ?></p>
                </div>
                <div class="tab-pane fade" id="serv" role="tabpanel">
                    <h4>Trabaja:</h4>
                    <p>Consulta particular: $5000</p>
                    <p>Consulta con Obra Social</p>

                    <h4>Obras Sociales:</h4>
                    <?php if (!empty($obrasDelUsuario)): ?>
                        <ul>
                            <?php foreach ($obrasDelUsuario as $obra): ?>
                                <li><?php echo $obra['nombre_obra_social'] . " - " . $obra['detalle']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p><em>No tenés obras sociales asignadas.</em></p>
                    <?php endif; ?>

                    <form method="post" action="controladores/obra_social_usuario_controlador.php">
                        <input type="hidden" name="perfil" value="<?php echo $_SESSION['nombre_perfil']; ?>">
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

                        <label for="obras">Selecciona tus Obras Sociales:</label>
                        <small class="text-muted d-block mb-2">Marcá las obras sociales que querés asociar.</small>

                        <div class="mb-3">
                            <?php foreach ($todasObras as $obra): ?>
                                <?php
                                    $checked = false;
                                    foreach ($obrasDelUsuario as $asignada) {
                                        if ($asignada['id_obra_social'] == $obra['id_obra_social']) {
                                            $checked = true;
                                            break;
                                        }
                                    }
                                ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="obras[]" value="<?= $obra['id_obra_social'] ?>" id="obra<?= $obra['id_obra_social'] ?>" <?= $checked ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="obra<?= $obra['id_obra_social'] ?>">
                                        <?= $obra['nombre_obra_social'] ?> - <?= $obra['detalle'] ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="submit" class="btn btn-success">Guardar Obras Sociales</button>
                    </form>

                    <hr class="my-4">

                <h4>Días Laborales:</h4>

                    <h5>Días laborales asignados:</h5>
                    <?php if (!empty($diasAsignados)): ?>
                        <ul>
                            <?php foreach ($diasAsignados as $dia): ?>
                                <li><?= $dia ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p><em>No seleccionaste días laborales todavía.</em></p>
                    <?php endif; ?>

                    <form method="post" action="controladores/dias_laborales_controlador.php">
                        <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">

                        <label for="dias">Selecciona tus días laborales:</label>
                        <small class="text-muted d-block mb-2">Marcá los días en los que atendés.</small>

                        <?php foreach ($diasSemana as $dia): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias[]" value="<?= $dia ?>" id="dia_<?= $dia ?>" <?= in_array($dia, $diasAsignados) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="dia_<?= $dia ?>"><?= $dia ?></label>
                            </div>
                        <?php endforeach; ?>

                        <button type="submit" class="btn btn-primary mt-3">Guardar Días Laborales</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="user" role="tabpanel">
                    <h5>Información</h5>
                    <ul class="list-unstyled">
                        <li class="mt-2"><strong>Teléfono:</strong><br> +1 54546 45648</li>
                        <li class="mt-2"><strong>Email:</strong><br> john@example.com</li>
                        <li class="mt-2"><strong>Contraseña:</strong><br> ******</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Reservas -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">Agendas</div>
                <div class="card-body">
                    <div class="d-flex justify-content-around mb-3">
                        <div><strong>Hoy</strong><br><small>20 Sep</small></div>
                        <div><strong>Mañana</strong><br><small>21 Sep</small></div>
                        <div><strong>Lun</strong><br><small>22 Sep</small></div>
                        <div><strong>Mar</strong><br><small>23 Sep</small></div>
                    </div>
                    <p class="text-muted small">Este especialista aún no ofrece el calendario online de turnos en esta dirección</p>
                    <a href="#" class="btn btn-outline-primary w-100">Solicitar calendario</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>