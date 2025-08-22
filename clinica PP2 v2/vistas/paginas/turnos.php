<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Turnos</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f8f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Columna de filtros */
    .sidebar {
      width: 250px;
      background: #ffffff;
      padding: 20px;
      border-right: 1px solid #ddd;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
      height: 100vh;
      position: sticky;
      top: 0;
    }

    .sidebar h2 {
      font-size: 18px;
      margin-bottom: 20px;
      color: #333;
    }

    .filter-group {
      margin-bottom: 25px;
    }

    .filter-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      font-size: 14px;
      color: #444;
    }

    .filter-group input,
    .filter-group select {
      width: 100%;
      padding: 8px;
      font-size: 14px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    /* Contenido principal */
    .main-content {
      flex-grow: 1;
      padding: 40px;
    }

    .container {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      justify-content: flex-start;
    }

    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.05);
      width: 300px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      padding: 20px 20px 10px 20px;
    }

    .date {
      text-align: left;
    }

    .day {
      font-size: 32px;
      font-weight: 700;
      line-height: 1;
    }

    .month {
      font-size: 16px;
      font-weight: 600;
      margin-top: 2px;
    }

    .tools {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .tools i {
      font-size: 14px;
      padding: 6px;
      border-radius: 4px;
      background-color: #f1f1f1;
      color: #444;
      cursor: pointer;
    }

    .card-body {
      padding: 0 20px 20px;
    }

    .title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
      line-height: 1.2;
    }

    .description {
      font-size: 14px;
      color: #666;
      margin-bottom: 20px;
    }

    .info {
      display: flex;
      align-items: center;
      font-size: 14px;
      margin-bottom: 5px;
    }

    .info i {
      margin-right: 8px;
      font-size: 14px;
    }

    .footer-line {
      height: 5px;
      border-radius: 0 0 10px 10px;
    }

    .purple .day,
    .purple .month,
    .purple .title,
    .purple .info {
      color: rgb(95, 163, 240);
    }

    .purple .footer-line {
      background-color:rgb(95, 163, 240);
    }

    .red .day,
    .red .month,
    .red .title,
    .red .info {
      color: rgb(44, 92, 248);
    }

    .red .footer-line {
      background-color:rgb(44, 92, 248);
    }

    .green .day,
    .green .month,
    .green .title,
    .green .info {
      color:rgb(3, 14, 179);
    }

    .green .footer-line {
      background-color:rgb(3, 14, 179);
    }
  </style>
</head>
<body>

  <!-- Columna lateral de filtros -->
  <div class="sidebar">
    <h2>Filtros</h2>
    
    <div class="filter-group">
      <label for="fecha">Fecha</label>
      <input type="date" id="fecha" name="fecha">
    </div>

    <div class="filter-group">
      <label for="especialidad">Especialidad</label>
      <select id="especialidad" name="especialidad">
        <option value="">Todas</option>
        <option>Cardiología</option>
        <option>Pediatría</option>
        <option>Dermatología</option>
        <option>Neurología</option>
      </select>
    </div>

    <div class="filter-group">
      <label for="obraSocial">Obra Social</label>
      <select id="obraSocial" name="obraSocial">
        <option value="">Todas</option>
        <option>OSDE</option>
        <option>Swiss Medical</option>
        <option>Galeno</option>
        <option>Medicus</option>
      </select>
    </div>

    <div class="filter-group">
      <label for="medico">Médico</label>
      <select id="medico" name="medico">
        <option value="">Todos</option>
        <option>Dr. Gómez</option>
        <option>Dra. Pérez</option>
        <option>Dr. Rodríguez</option>
      </select>
    </div>
  </div>

  <!-- Contenido principal -->
  <div class="main-content">
    <div class="container">

      <!-- Tarjetas aquí (idénticas a las anteriores) -->
      <div class="card purple">
        <div class="card-header">
          <div class="date">
            <div class="day">18</div>
            <div class="month">Dec</div>
          </div>
        </div>
        <div class="card-body">
          <div class="title">Dr.Gómez</div>
          <div class="description">Especialista en Cardiología</div>
          <div class="info"><i class="far fa-clock"></i>08 Pm - 09 Pm</div>
          <div class="info"><i class="fas fa-map-marker-alt"></i>Juan Jose Silva, 1890</div>
          <div class="info"><i class="fas fa-arrow-right"></i> <a href="index.php?page=info_turnos" >Ver más</a> </div>
        </div>
        <div class="footer-line"></div>
      </div>

      <div class="card red">
        <div class="card-header">
          <div class="date">
            <div class="day">25</div>
            <div class="month">Dec</div>
          </div> <!--
          <div class="tools">
            <i class="fas fa-pen"></i>
            <i class="fas fa-trash"></i>
          </div> -->
        </div>
        <div class="card-body">
          <div class="title">Dra. Pérez</div>
          <div class="description">Especialista en Dermatología</div>
          <div class="info"><i class="far fa-clock"></i>09:45 Pm - 10 Pm</div>
          <div class="info"><i class="fas fa-map-marker-alt"></i>1 Circle Street Leominster, Ma 01453</div>
          <div class="info"><i class="fas fa-arrow-right"></i>Ver más</div>
        </div>
        <div class="footer-line"></div>
      </div>

      <div class="card green">
        <div class="card-header">
          <div class="date">
            <div class="day">29</div>
            <div class="month">Dec</div>
          </div> <!--
          <div class="tools">
            <i class="fas fa-pen"></i>
            <i class="fas fa-trash"></i>
          </div> -->
        </div>
        <div class="card-body">
          <div class="title">Dr. Rodríguez</div>
          <div class="description">Especialista en Neurología</div>
          <div class="info"><i class="far fa-clock"></i>10 Pm - 10:30 Pm</div>
          <div class="info"><i class="fas fa-map-marker-alt"></i>1 Circle Street Leominster, Ma 01453</div>
          <div class="info"><i class="fas fa-arrow-right"></i>Ver más</div>
        </div>
        <div class="footer-line"></div>
      </div>

    </div>
  </div>
</body>
</html>
