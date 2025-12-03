<?php
require_once "modelos/doctor.php";

$doc = new Doctor();
$resultado = $doc->all_doctores();

// Convertir mysqli_result en array
$doctores = [];
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $doctores[] = $fila;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Doctores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fc;
      font-family: 'Segoe UI', sans-serif;
    }
    .doctor-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      transition: 0.3s ease;
    }
    .doctor-card:hover {
      transform: translateY(-3px);
    }
    .doctor-info {
      display: flex;
      align-items: center;
    }
    .doctor-img {
      width: 80px;
      height: 80px;
      border-radius: 10px;
      object-fit: cover;
      margin-right: 20px;
      background: #ddd;
    }
    .doctor-details h6 {
      font-weight: 700;
      margin-bottom: 5px;
    }
    .doctor-details p {
      margin: 0;
      font-size: 14px;
      color: #6c757d;
    }
    .doctor-price {
      color: #4e73df;
      font-weight: bold;
    }
    .icon-btn {
      background-color: #f8f9fc;
      border: none;
      border-radius: 50%;
      padding: 8px;
      color: #6c757d;
      cursor: pointer;
      font-size: 14px;
    }
    .icon-btn:hover {
      background-color: #e2e6ea;
    }
    .action-icons {
      display: flex;
      align-items: center;
      gap: 10px;
    }
  </style>
</head>
<body>

  <div class="container py-4">
    <h1 class="mb-4">Doctores</h1>

    <div class="row">
      <?php if (count($doctores) === 0): ?>
          <div class="alert alert-warning">No hay doctores registrados.</div>
      <?php endif; ?>

      <?php foreach ($doctores as $d): ?>
      <div class="col-md-6">
        <div class="doctor-card">

          <div class="doctor-info">

            <!-- Imagen genérica si no existe -->
            <img src="https://randomuser.me/api/portraits/men/<?php echo rand(1,70); ?>.jpg" 
                 class="doctor-img" alt="Doctor">

            <div class="doctor-details">
              <h6>
                Dr. <?= $d['nombre'] . " " . $d['apellido'] ?>
              </h6>

              <p>Matrícula: <?= $d['numero_matricula_profesional'] ?></p>
              <p>Usuario: <?= $d['nombre_usuario'] ?></p>
              <p>Precio Particular: 
                <span class="doctor-price">$<?= number_format($d['precio_consulta'], 0, ',', '.') ?></span>
              </p>
            </div>

          </div>

          <div class="action-icons">
            <button class="icon-btn" title="Opciones">
              <i class="fas fa-ellipsis-v"></i>
            </button>

            <a href="index.php?page=agenda_doctor&id=<?= $d['id_doctor'] ?>" class="icon-btn" title="Ver Agenda">
              <i class="fas fa-calendar-alt"></i>
            </a>
          </div>

        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>

</body>
</html>
