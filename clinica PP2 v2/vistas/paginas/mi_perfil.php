<?php
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];
$email = $_SESSION['email'];
$perfil_id = $_SESSION['id_perfil']; // Este dato se usará con JS

require_once 'modelos/doctor.php';
$docs = new Doctor();
$doctores_no_disponibles = $docs->all_doctores();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="assets/css/adminStyle.css">
    <style>
        header,
        h1,
        h4 {
            color: #000967;
        }

        .card-custom {
            background-color: #8999AE;
            border: none;
            border-radius: 10px;
            color: white;
        }

        .section-title {
            color: #024296;
        }

        .badge-active {
            background-color: #007DC6;
            color: white;
            padding: 0.3em 0.6em;
            border-radius: 0.25rem;
        }

        .badge-pending {
            background-color: #024296;
            color: white;
            padding: 0.3em 0.6em;
            border-radius: 0.25rem;
        }

        #admin-container,
        #paciente-container,
        #doctor-container {
            display: none;
        }
    </style>
</head>

<body>

    <main class="py-5 container">
        <!--------------------------------- CONTENEDOR DE PACIENTE ---------------------------------->
        <!--------------------------------- CONTENEDOR DE PACIENTE ---------------------------------->
        <!--------------------------------- CONTENEDOR DE PACIENTE ---------------------------------->
        <!--------------------------------- CONTENEDOR DE PACIENTE ---------------------------------->
        <div id="paciente-container">
            <?php include 'vistas/componentes/miperfil_paciente.php'?>
        </div>

        <!--------------------------------- CONTENEDOR DE DOCTOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE DOCTOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE DOCTOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE DOCTOR ---------------------------------->

        <div id="doctor-container">
            <?php include 'vistas/componentes/miperfil_doctor.php'?>
            </div>

        <!--------------------------------- CONTENEDOR DE ADMINISTRADOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE ADMINSITRADOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE ADMINISTRADOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE ADMINISTRADOR ---------------------------------->
        <!--------------------------------- CONTENEDOR DE ADMINISTRADOR ---------------------------------->


        <div id="admin-container">
            <?php include 'vistas/componentes/miperfil_admin.php'?>
        </div>

    </main>

    <!-- Esta línea muestra la ruta completa del archivo actual (útil para saber en qué carpeta estoy) echo __FILE__; -->
    <hr>
    <?php if ($perfil_id == '3'): ?>
        <h3>Cambiar Contraseña</h3>
        <form action="controladores/contrasena.controlador.php" method="POST">
            <input type="hidden" name="action" value="cambiar_password">
            <div class="mb-3">
                <label for="actual" class="form-label">Contraseña Actual:</label>
                <input type="password" name="actual" id="actual" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nueva" class="form-label">Nueva Contraseña:</label>
                <input type="password" name="nueva" id="nueva" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirmar" class="form-label">Confirmar Nueva Contraseña:</label>
                <input type="password" name="confirmar" id="confirmar" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
        </form>
    <?php else: ($perfil_id == '1' || '2') ?>
        <h3>Resetear mi contraseña</h3>
        <form action="controladores/contrasena.controlador.php" method="POST">
            <input type="hidden" name="action" value="resetear_password">
            <button type="submit" class="btn btn-warning" onclick="return confirm('¿Estás seguro que quieres resetear tu contraseña?')">
                Resetear a contraseña por defecto
            </button>
        </form>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const perfilId = <?= json_encode($perfil_id); ?>;
            const doctorContainer = document.getElementById('doctor-container');
            const pacienteContainer = document.getElementById('paciente-container');
            const adminContainer = document.getElementById('admin-container');

            if (perfilId == 3) {
                pacienteContainer.style.display = 'block';
            } else if (perfilId == 1) {
                adminContainer.style.display = 'block';
            } else if (perfilId == 2) {
                doctorContainer.style.display = 'block';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth'
            });
            calendar.render();
        });
    </script>
    <script>
        function actualizarHora() {
            const ahora = new Date();

            // Formato HH:MM:SS
            const hora = ahora.getHours().toString().padStart(2, '0');
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0');

            document.getElementById("hora").textContent = `${hora}:${minutos}:${segundos}`;
        }

        // Actualiza al cargar
        actualizarHora();

        // Actualiza cada 1 segundo
        setInterval(actualizarHora, 1000);
    </script>


    <!-- Script de gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script de alternar entre gráficos -->
    <script>
    document.getElementById('btnUsuarios').addEventListener('click', () => {
    document.getElementById('graficoUsuariosContainer').style.display = 'block';
    document.getElementById('graficoGeneroContainer').style.display = 'none';
    });

    document.getElementById('btnGenero').addEventListener('click', () => {
    document.getElementById('graficoUsuariosContainer').style.display = 'none';
    document.getElementById('graficoGeneroContainer').style.display = 'block';
    });

    document.getElementById('btnObras').addEventListener('click', () => {
    document.getElementById('graficoUsuariosContainer').style.display = 'none';
    document.getElementById('graficoGeneroContainer').style.display = 'none';
    document.getElementById('graficoObrasContainer').style.display = 'block';
    });
    </script>

    <script>
        const ctxUsuarios = document.getElementById('graficoUsuarios');

        const chartUsuarios = new Chart(ctxUsuarios, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
            label: 'Registros de Usuarios',
            data: [],
            borderColor: '#42a5f5',
            backgroundColor: 'rgba(66,165,245,0.15)',
            tension: 0.3
            }]
        },
        options: {
            plugins: { legend: { display: true } },
            scales: { y: { beginAtZero: true } }
        }
        });

        async function cargarUsuarios(periodo = 'mensual') {
        try {
            const res = await fetch('controladores/reporte_grafico_usuarios.php?periodo=' + periodo);
            const json = await res.json();
            chartUsuarios.data.labels = json.labels;
            chartUsuarios.data.datasets[0].data = json.data;
            chartUsuarios.update();
        } catch (e) {
            console.error('Error cargando datos de usuarios:', e);
        }
        }

        // Selector de período
        document.getElementById('periodoUsuarios').addEventListener('change', e => {
        cargarUsuarios(e.target.value);
        });

        // Carga inicial
        cargarUsuarios('mensual');
    </script>

    <script>
    const ctxGenero = document.getElementById('graficoGenero');

    const chartGenero = new Chart(ctxGenero, {
    type: 'pie',
    data: {
        labels: [],
        datasets: [{
        label: 'Pacientes por Género',
        data: [],
        backgroundColor: ['#42a5f5', '#ef5350', '#66bb6a'] // Colores para cada género
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom' } }
    }
    });

    // 👉 función para agrupar y sumar por género
    function agruparPorGenero(json) {
    const agrupado = {};
    json.labels.forEach((label, i) => {
        agrupado[label] = (agrupado[label] || 0) + json.data[i];
    });
    return {
        labels: Object.keys(agrupado),
        data: Object.values(agrupado)
    };
    }

    async function cargarGenero(periodo = 'mensual') {
    try {
        const res = await fetch('controladores/reporte_grafico_pacientes.php?periodo=' + periodo);
        const json = await res.json();

        // 🔹 Agrupamos antes de graficar
        const agrupado = agruparPorGenero(json);

        chartGenero.data.labels = agrupado.labels;
        chartGenero.data.datasets[0].data = agrupado.data;
        chartGenero.update();
    } catch (e) {
        console.error('Error cargando datos de género:', e);
    }
    }

    // Selector de período
    document.getElementById('periodoGenero').addEventListener('change', e => {
    cargarGenero(e.target.value);
    });

    // Carga inicial
    cargarGenero('mensual');
    </script>

    <script>
    const ctxObras = document.getElementById('graficoObras');

    const chartObras = new Chart(ctxObras, {
        type: 'pie', //  ahora es torta
        data: {
            labels: [],
            datasets: [{
                label: 'Uso de Obras Sociales',
                data: [],
                backgroundColor: [
                    '#42a5f5', '#ef5350', '#66bb6a', '#ffa726',
                    '#ab47bc', '#26c6da', '#8d6e63', '#29b6f6'
                ] //  varios colores para cada obra social
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } }
        }
    });

    async function cargarObras(periodo = 'mensual') {
        try {
            const res = await fetch('controladores/reporte_grafico_obras_sociales.php?periodo=' + periodo);
            const json = await res.json();
            chartObras.data.labels = json.labels;
            chartObras.data.datasets[0].data = json.data;
            chartObras.update();
        } catch (e) {
            console.error('Error cargando datos de obras sociales:', e);
        }
    }

    // Selector de período
    document.getElementById('periodoObras').addEventListener('change', e => {
        cargarObras(e.target.value);
    });

    // Carga inicial
    cargarObras('mensual');
    </script>


    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
</body>

</html>