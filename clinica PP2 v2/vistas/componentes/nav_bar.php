
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
  <div class="col-md-3 mb-2 mb-md-0">
    <a href="index.php?page=indexo" class="d-inline-flex link-body-emphasis text-decoration-none">
      <img src="assets/images/logo/captura_de_pantalla_2.png" alt="Logo" style="width:250px; margin: 10px; ">
    </a>
  </div>

  <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
    <li><a href="index.php?page=indexo" class="nav-link px-2 link-secondary">Inicio</a></li>
    <li><a href="index.php?page=noticias" class="nav-link px-2">Noticias</a></li>
    <li><a href="index.php?page=nosotros" class="nav-link px-2">Nosotros</a></li>
    <li><a href="index.php?page=biblioteca" class="nav-link px-2">Biblioteca</a></li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Salud
      </a>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="index.php?page=doctores">Doctores</a></li>
        <li><a class="dropdown-item" href="index.php?page=turnos">Turnos</a></li>
      </ul>
    </li>
  </ul>

  <div class="col-md-3 text-end">
    <?php if (!isset($_SESSION['id_usuario'])):?>
    <button type="button" class="btn btn-outline-primary me-2">
      <li><a href="index.php?page=login" class="nav-link px-2 link-secondary">Ingresar</a></li>
    </button>
    <?php else: ($_SESSION['id_perfil'] == '1')?>
    <a class="navbar-brand" href="index.php?page=mi_perfil">
      <img src="assets/images/img_avatar1.png" alt="Avatar Logo" style="width:40px;" class="rounded-pill">
    </a>
    <div class="btn">
    <a href="vistas/paginas/salida.php" class="btn btn-danger mt-3">Cerrar Sesión</a>
    <?php endif; ?>
  </div>
  </div>
</header>

