<?php
// Simulación de datos obtenidos desde la base de datos, se debe cambiar
$doctor = [
    "nombre" => "Silvia Beatriz Mazzaglia",
    "especialidad" => "Dermatólogo",
    "ubicacion" => "Formosa- Brandsen 1890",
    "direccion" => "Av. Siempre Viva 123",
    "subespecialidad" => "Dermatología pediátrica"
];
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
    <div class="row">
        <!-- Columna izquierda -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex align-items-center">
                    <img src="https://via.placeholder.com/100x100" alt="Doctor" class="rounded-circle me-3">
                    <div>
                        <h5 class="card-title mb-0"><?= $doctor['nombre'] ?></h5>
                        <p class="text-muted mb-1"><?= $doctor['especialidad'] ?></p>
                        <p class="text-muted"><i class="bi bi-geo-alt"></i> <?= $doctor['ubicacion'] ?><?= $doctor['direccion'] ?></a></p>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                </div>
            </div>

            <!-- Tabs -->
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

            <!-- Contenido de Tabs -->
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
                    <ul>
                        <li>OSDE</li>
                        <li>Avalian</li>
                        <li>OSPAC</li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="user" role="tabpanel">
                    <!-- Datos del usuario que se puedan cambiar -->
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

<!-- Bootstrap y JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
