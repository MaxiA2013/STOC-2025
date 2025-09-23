<!-- Incluye Bootstrap y Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
  /* Glass effect */
  .card-glass {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease-in-out;
  }

  /* Hover animado */
  .card-glass:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
  }

  /* Gradientes personalizados */
  .gradient-sintomas {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
  }
  .gradient-medicamentos {
    background: linear-gradient(135deg, #1f1794ff, #6380e0ff);
    color: white;
  }
  .gradient-usuarios {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    color: white;
  }

  /* Iconos grandes */
  .card-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
  }
</style>

<div class="container my-5">
  <div class="row justify-content-center g-4">

    <?php
    // Carpeta donde están las plantillas
    $templateDir = __DIR__ . "/template_tablasMaestras/";

    // Colores cíclicos
    $gradientes = ["gradient-sintomas", "gradient-medicamentos", "gradient-usuarios"];
    $iconos = ["bi-clipboard2-pulse", "bi-capsule-pill", "bi-people"];

    // Obtener archivos PHP
    $archivos = glob($templateDir . "*.php");
    $i = 0;

    foreach ($archivos as $archivo) {
        $nombreArchivo = basename($archivo); // ejemplo: lista_sintomas.php
        $nombreLimpio = ucfirst(str_replace("_", " ", pathinfo($nombreArchivo, PATHINFO_FILENAME)));

        // Asignar gradiente e icono de forma cíclica
        $gradiente = $gradientes[$i % count($gradientes)];
        $icono = $iconos[$i % count($iconos)];

        echo '
        <div class="col-12 col-sm-6 col-md-4">
          <div class="card card-glass ' . $gradiente . ' text-center h-100">
            <div class="card-body">
              <i class="bi ' . $icono . ' card-icon"></i>
              <h5 class="card-title fw-bold">' . $nombreLimpio . '</h5>
              <p class="card-text">Acceso rápido al módulo ' . strtolower($nombreLimpio) . '.</p>
              <a href="index.php?page=' . pathinfo($nombreArchivo, PATHINFO_FILENAME) . '" class="btn btn-light fw-bold w-100">Acceder</a>
            </div>
          </div>
        </div>
        ';
        $i++;
    }
    ?>

  </div>
</div>
