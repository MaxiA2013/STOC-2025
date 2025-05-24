<?php
require_once "../modulos/condicion.php";
$con = new Condicion();
$lista = $con->consultarVariasCondiciones();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo Condición</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="mt-4 p-5 bg-primary text-white rounded">
    <h1>Bienvenido al Modulo Condición</h1>
    <p>Gestioná las condiciones del sistema agregando, eliminando o modificando.</p>
</div>
<a href="../index.php">Regresar al Inicio</a> <!-- Link para la página -->

<div class="container-fluid">

    <div class="container text-center">
        <div class="row">
            <div class="col">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $condicion) { ?>
                            <tr>
                                <td><?php echo $condicion['id_condicion'] ?></td>
                                <td><?php echo $condicion['nombre_condicion'] ?></td>
                                <td><?php echo $condicion['detalle'] ?></td>
                                <td>
                                    <form class="needs-validation" action="../controladores/condicion_controlador.php" method="post">
                                        <input type="hidden" name="id_condicion" value="<?= $condicion['id_condicion'] ?>">
                                        <input type="hidden" name="accion" value="baja">
                                        <button type="submit" class="btn btn-danger">Borrar</button>
                                    </form>
                                </td>
                                <td>
                                    <form class="needs-validation" action="../controladores/condicion_controlador.php" method="post">
                                        <input type="hidden" name="id_condicion" value="<?= $condicion['id_condicion'] ?>">
                                        <input type="hidden" name="accion" value="actualizacion">
                                        <button type="submit" class="btn btn-warning">Modificar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="col">
                <form class="needs-validation" action="../controladores/condicion_controlador.php" method="post">
                    <div class="mb-3 mt-3">
                        <label for="nombre_condicion" class="form-label">Nombre de la Condición:</label>
                        <input type="text" class="form-control" id="condicion" placeholder="Ingrese condición" name="condicion" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_condicion" class="form-label">Descripción:</label>
                        <input type="text" class="form-control" id="descripcion_condicion" placeholder="Ingrese Descripción" name="descripcion_condicion" required>
                    </div>
                    <input type="hidden" name="accion" value="alta">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script>
    (() => {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
</body>

</html>
