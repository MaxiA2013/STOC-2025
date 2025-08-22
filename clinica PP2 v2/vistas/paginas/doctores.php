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
    <h1>Doctores</h1>
    <div class="row">

      <!-- Doctor Card 1 -->
      <div class="col-md-6">
        <div class="doctor-card">
          <div class="doctor-info">
            <img src="https://randomuser.me/api/portraits/men/11.jpg" class="doctor-img" alt="Doctor">
            <div class="doctor-details">
              <h6>Dr. Lorenzo Winter </h6>
              <p>cardiología</p>
              <p>Disponibilidad: Lunes, Martes, jueves</p>
              <p>Precio Particular: <span class="doctor-price">$20.000</span></p>
            </div>
          </div>
          <div class="action-icons">
            <button class="icon-btn"><i class="fas fa-ellipsis-v"></i></button>
            <button class="icon-btn"><i class="fas fa-calendar-alt"></i></button>
          </div>
        </div>
      </div>

      <!-- Doctor Card 2 -->
      <div class="col-md-6">
        <div class="doctor-card">
          <div class="doctor-info">
            <img src="https://randomuser.me/api/portraits/women/12.jpg" class="doctor-img" alt="Doctor">
            <div class="doctor-details">
              <h6>Dr. Mónica Recalde</h6>
              <p>Oftalmología</p>
              <p>Disponibilidad: miercoles, viernes</p>
              <p>Precio Particular: <span class="doctor-price">$30.000</span></p>
            </div>
          </div>
          <div class="action-icons">
            <button class="icon-btn"><i class="fas fa-ellipsis-v"></i></button>
            <button class="icon-btn"><i class="fas fa-calendar-alt"></i></button>
          </div>
        </div>
      </div>

      <!-- Doctor Card 3 -->
      <div class="col-md-6">
        <div class="doctor-card">
          <div class="doctor-info">
            <img src="https://randomuser.me/api/portraits/women/14.jpg" class="doctor-img" alt="Doctor">
            <div class="doctor-details">
              <h6>Dr. Rosario Montero</h6>
              <p>Pediatría</p>
              <p>Disponibilidad: lunes, martes, miercoles, jueves, viernes</p>
              <p>Precio Particular: <span class="doctor-price">$15.000</span></p>
            </div>
          </div>
          <div class="action-icons">
            <button class="icon-btn"><i class="fas fa-ellipsis-v"></i></button>
            <button class="icon-btn"><i class="fas fa-calendar-alt"></i></button>
          </div>
        </div>
      </div>

      <!-- Doctor Card 4 -->
      <div class="col-md-6">
        <div class="doctor-card">
          <div class="doctor-info">
            <img src="https://randomuser.me/api/portraits/men/18.jpg" class="doctor-img" alt="Doctor">
            <div class="doctor-details">
              <h6>Dr. Jorge Escalabrini</h6>
              <p>Radiología</p>
              <p>Disponibilidad: lunes, martes, miercoles, viernes</p>
              <p>Precio Particular: <span class="doctor-price">$25.000</span></p>
            </div>
          </div>
          <div class="action-icons">
            <button class="icon-btn"><i class="fas fa-ellipsis-v"></i></button>
            <button class="icon-btn"><i class="fas fa-calendar-alt"></i></button>
          </div>
        </div>
      </div>

      <!-- Doctor Card 5 -->
      <div class="col-md-6">
        <div class="doctor-card">
          <div class="doctor-info">
            <img src="https://randomuser.me/api/portraits/women/25.jpg" class="doctor-img" alt="Doctor">
            <div class="doctor-details">
              <h6>Dr. Fabian Menendez</h6>
              <p>Pssiquiatríat</p>
              <p>Disponibilidad: martes, jueves</p>
              <p>Precio Particular: <span class="doctor-price">$50.000</span></p>
            </div>
          </div>
          <div class="action-icons">
            <button class="icon-btn"><i class="fas fa-ellipsis-v"></i></button>
            <button class="icon-btn"><i class="fas fa-calendar-alt"></i></button>
          </div>
        </div>
      </div>

      <!-- Doctor Card 6 -->
      <div class="col-md-6">
        <div class="doctor-card">
          <div class="doctor-info">
            <img src="https://randomuser.me/api/portraits/men/35.jpg" class="doctor-img" alt="Doctor">
            <div class="doctor-details">
              <h6>Dr. Marcos Moriñigo</h6>
              <p>Nutrición</p>
              <p>Disponibilidad: lunes, miercoles, viernes</p>
              <p>Precio Particular: <span class="doctor-price">$20.000</span></p>
            </div>
          </div>
          <div class="action-icons">
            <button class="icon-btn"><i class="fas fa-ellipsis-v"></i></button>
            <button class="icon-btn"><i class="fas fa-calendar-alt"></i></button>
          </div>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
