<? require_once('../../assets/css/buscador_turnos.css') ?> 

<style>
    .buscador_turnos{
        background-color: #000967;
        margin: 0 auto;
        padding: 3px;
    }

    .container.my-5{
        margin: 0 auto;
        padding: 20px;
        border-radius: 3px;
        background-color: #024296;
    }

    .conti{
        margin: 0 auto;
        padding: 20px;
        border-radius: 10px;
        background-color: #AFCEDF;
    }

    .container.text-center{
        background-color: #AFCEDF;
        margin: 0 auto;
        padding: 15px;
    }

    form{
        margin: 0 auto;
        padding: 10px;
    }

    /* Centra el botón */
    .button-container {
        display: flex;
        justify-content: center;
        margin-top: 15px;
    }

    /* Estilo uniforme para los campos de entrada */
    .form-group {
        margin-bottom: 15px;
    }

    input[type="text"] {
        width: 100%;
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
<div class="buscador_turnos">
    <div class="container my-5">
        <div class="conti">
            <h1 class="text-body-emphasis">Encontrá tu turno ideal</h1>
            <p class="lead">
                Rellená los campos y encontrá un turno disponible
            </p>
            <form>
                <div class="container text-center">
                    <div class="row">
                        <!-- Especialidad -->
                        <div class="col-md form-group">
                            <label for="especialidad">Especialidad:</label>
                            <input type="text" id="especialidad" name="especialidad">
                        </div>
                        <!-- Fecha -->
                        <div class="col-md form-group">
                            <label for="fecha">Fecha:</label>
                            <input type="text" id="fecha" name="fecha">
                        </div>
                        <!-- Hora -->
                        <div class="col-md form-group">
                            <label for="hora">Hora:</label>
                            <input type="text" id="hora" name="hora">
                        </div>
                        <!-- Obra Social -->
                        <div class="col-md form-group">
                            <label for="obra-social">Obra Social:</label>
                            <input type="text" id="obra-social" name="obra-social">
                        </div>
                    </div>
                </div>
                <!-- Centrado del botón -->
                <div class="button-container">
                    <button type="button" class="btn btn-secondary btn-lg">Buscar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
