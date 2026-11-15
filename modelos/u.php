Bien, tengo este sidebar que quiero arreglar unos detalles, primero que nada quiero un apartado gestion de usuarios que despliegue los items usuarios, doctor, paciente y administradores y otro apartado llamado gestion seguridad que despliegue los items perfiles, permisos, modulos, tablas. Este es mi archivo sidebar: <style>
  #toggleSidebar {
    position: relative;
    left: 150px;
    z-index: 2050;
    border-radius: 25%;
    border-style: none;
    width: 50px;
    height: 50px;
  }

  .hidden-sidebar {
    transform: translateX(-280px);
    transition: transform 0.3s ease-in-out;
  }

  .d-flex.flex-column {
    transition: transform 0.3s ease-in-out;
  }
</style>
<!-- despues arreglar las ubicaciones del css y js en carpetas diferentes-->

<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark position-absolute vh-100" style="width: 280px; z-index: 2050;">
  <a class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <img src="assets/images/logo/captura_de_pantalla_3.png" alt="Logo" style="width:40px; margin-right: 5px">
    <span class="fs-4">Menú</span>
    <button id="toggleSidebar" class="text-bg-dark">☰</button>
  </a>
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="index.php?page=mi_perfil" class="nav-link text-white" aria-current="page">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
          <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
          <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
        </svg>
        Mi Perfil
      </a>
    </li>

    <li class="nav-item">
      <a href="index.php?page=mis_datos" class="nav-link text-white" aria-current="page">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="23" fill="currentColor" class="bi bi-heart-pulse" viewBox="0 0 16 16">
          <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053.918 3.995.78 5.323 1.508 7H.43c-2.128-5.697 4.165-8.83 7.394-5.857q.09.083.176.171a3 3 0 0 1 .176-.17c3.23-2.974 9.522.159 7.394 5.856h-1.078c.728-1.677.59-3.005.108-3.947C13.486.878 10.4.28 8.717 2.01zM2.212 10h1.315C4.593 11.183 6.05 12.458 8 13.795c1.949-1.337 3.407-2.612 4.473-3.795h1.315c-1.265 1.566-3.14 3.25-5.788 5-2.648-1.75-4.523-3.434-5.788-5" />
          <path d="M10.464 3.314a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.5a.5.5 0 0 0 0 1H4a.5.5 0 0 0 .416-.223l1.473-2.209 1.647 4.118a.5.5 0 0 0 .945-.049l1.598-5.593 1.457 3.642A.5.5 0 0 0 12 9h3.5a.5.5 0 0 0 0-1h-3.162z" />
        </svg>
        Mis Datos
      </a>
    </li>

    <li>
      <a href="#" class="nav-link text-white">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor" class="bi bi-table" viewBox="0 0 16 16">
          <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 2h-4v3h4zm0 4h-4v3h4zm0 4h-4v3h3a1 1 0 0 0 1-1zm-5 3v-3H6v3zm-5 0v-3H1v2a1 1 0 0 0 1 1zm-4-4h4V8H1zm0-4h4V4H1zm5-3v3h4V4zm4 4H6v3h4z" />
        </svg>
        Turnos
      </a>
    </li>

    <li>
      <a href="#" class="nav-link text-white">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="30" fill="currentColor" class="bi bi-clipboard2-check" viewBox="0 0 16 16">
          <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z" />
          <path d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z" />
          <path d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
        </svg>
        Historial de Citas
      </a>
    </li>

    <?php if ($_SESSION['id_perfil'] == '1'): #tengo id = 2 como administrador  
    ?>
      <li>
        <a href="index.php?page=lista_doctor" class="nav-link text-white">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor" class="bi bi-hospital" viewBox="0 0 16 16">
            <path d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1zM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25zM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25zm-11-4a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5A.25.25 0 0 0 3 9.75v-.5A.25.25 0 0 0 2.75 9zm0 2a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25zM2 13.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25z" />
            <path d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1zm2 14h2v-3H7zm3 0h1V3H5v12h1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1zm0-14H6v1h4zm2 7v7h3V8zm-8 7V8H1v7z" />
          </svg>
          Doctores
        </a>
      </li>

      <li>
        <a href="index.php?page=lista_usuario" class="nav-link text-white">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
          </svg>
          Usuarios
        </a>
      </li>

      <li>
        <a href="index.php?page=lista_paciente" class="nav-link text-white">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
          </svg>
          Pacientes
        </a>
      </li>

      <li>
        <a href="index.php?page=perfiles" class="nav-link text-white">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
            <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
          </svg>
          Perfiles
        </a>
      </li>

      <li>
        <a href="#" class="nav-link text-white">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor" class="bi bi-shield-check" viewBox="0 0 16 16">
            <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56" />
            <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0" />
          </svg>
          Permisos
        </a>
      </li>

      <div class="dropdown">
        <li>
          <a href="index.php?page=modulos" class="nav-link text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor" class="bi bi-inboxes" viewBox="0 0 16 16">
              <path d="M4.98 1a.5.5 0 0 0-.39.188L1.54 5H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0A.5.5 0 0 1 10 5h4.46l-3.05-3.812A.5.5 0 0 0 11.02 1zm9.954 5H10.45a2.5 2.5 0 0 1-4.9 0H1.066l.32 2.562A.5.5 0 0 0 1.884 9h12.234a.5.5 0 0 0 .496-.438zM3.809.563A1.5 1.5 0 0 1 4.981 0h6.038a1.5 1.5 0 0 1 1.172.563l3.7 4.625a.5.5 0 0 1 .105.374l-.39 3.124A1.5 1.5 0 0 1 14.117 10H1.883A1.5 1.5 0 0 1 .394 8.686l-.39-3.124a.5.5 0 0 1 .106-.374zM.125 11.17A.5.5 0 0 1 .5 11H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0 .5.5 0 0 1 .5-.5h5.5a.5.5 0 0 1 .496.562l-.39 3.124A1.5 1.5 0 0 1 14.117 16H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .121-.393zm.941.83.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438l.32-2.562H10.45a2.5 2.5 0 0 1-4.9 0z" />
            </svg>
            Modulos
          </a>
        </li>
      </div>

      <div class="dropdown">
        <li>
          <a href="index.php?page=tablas" class="nav-link text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor" class="bi bi-table" viewBox="0 0 16 16">
              <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 2h-4v3h4zm0 4h-4v3h4zm0 4h-4v3h3a1 1 0 0 0 1-1zm-5 3v-3H6v3zm-5 0v-3H1v2a1 1 0 0 0 1 1zm-4-4h4V8H1zm0-4h4V4H1zm5-3v3h4V4zm4 4H6v3h4z" />
            </svg>
            Tablas
          </a>
        </li>
      </div>

    <?php endif; ?>
  </ul>
  <hr>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.d-flex.flex-column');
    const toggleBtn = document.getElementById('toggleSidebar');

    // Leer estado desde localStorage
    const sidebarOculto = localStorage.getItem('sidebarOculto') === 'true';
    if (sidebarOculto) {
      sidebar.classList.add('hidden-sidebar');
    }

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('hidden-sidebar');
      const oculto = sidebar.classList.contains('hidden-sidebar');
      localStorage.setItem('sidebarOculto', oculto);
    });
  });
</script>.



<?php
require_once "modelos/obra_social.php";
require_once "modelos/paciente_obra_social.php";
require_once "modelos/doctor_obra_social.php";
require_once "modelos/doctor_Dias.php";
require_once "modelos/franja_horaria.php";

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

// PREVENCIÓN DE ERROR
$diasAsignados = [];
if ($id_doctor !== null) {
    $diasAsignados = $dd->consultarDiasPorDoctor($id_doctor);
}

$diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

// obtener todas las franjas para el select
$franjaModel = new Franja();
$todasFranjas = $franjaModel->consultarVariasFranjas();
// construir mapa id_franja => etiqueta (para mostrar en lista)
$mapFranjas = [];
foreach ($todasFranjas as $f) {
    $mapFranjas[$f['id_franja']] = "{$f['tipo_franja']} ({$f['inicio_franja']} - {$f['fin_franja']})";
}
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
                    <?php if (!empty($diasConFranjas)): ?>
                        <ul>
                            <?php foreach ($diasConFranjas as $diaDesc => $franjaId): ?>
                                <li>
                                    <?= $diaDesc ?> -
                                    <?= isset($mapFranjas[$franjaId]) ? $mapFranjas[$franjaId] : 'Franja no asignada' ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p><em>No seleccionaste días laborales todavía.</em></p>
                    <?php endif; ?>

                    <form method="post" action="controladores/dias_laborales_controlador.php">
                        <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">

                        <label for="dias">Selecciona tus días laborales:</label>
                        <small class="text-muted d-block mb-2">Marcá los días en los que atendés y elegí la franja horaria por día.</small>

                        <?php foreach ($diasSemana as $dia): 
                            $checked = in_array($dia, $diasAsignados);
                            $selectedFranja = $diasConFranjas[$dia] ?? '';
                        ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="form-check me-3">
                                    <input class="form-check-input dia-checkbox" type="checkbox" name="dias[]" value="<?= $dia ?>" id="dia_<?= $dia ?>" <?= $checked ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="dia_<?= $dia ?>"><?= ucfirst($dia) ?></label>
                                </div>

                                <div>
                                    <select name="franjas[<?= $dia ?>]" id="franja_<?= $dia ?>" class="form-select form-select-sm" style="width: 280px;" <?= $checked ? '' : 'disabled' ?>>
                                        <option value="">-- Seleccioná franja --</option>
                                        <?php foreach ($todasFranjas as $fr): ?>
                                            <option value="<?= $fr['id_franja'] ?>" <?= ($selectedFranja == $fr['id_franja']) ? 'selected' : '' ?>>
                                                <?= $fr['tipo_franja'] ?> (<?= $fr['inicio_franja'] ?> - <?= $fr['fin_franja'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.dia-checkbox').forEach(function(chk) {
        chk.addEventListener('change', function() {
            var id = this.id.replace('dia_', '');
            var select = document.getElementById('franja_' + id);
            if (select) {
                select.disabled = !this.checked;
            }
        });
    });
});
</script>

</body>
</html>