<?php
require_once "../modulos/estados.php";
$con = new Estado();
$lista = $con->consultarVariosEstados();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo Estados</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="mt-4 p-5 bg-primary text-white rounded">
        <h1>Bienvenido al Modulo Estados</h1>
        <p>Gestioná los Estados del sistema agregando, eliminando o modificando.</p>
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
                            <?php foreach ($lista as $estado) { ?>
                                <tr>
                                    <td><?php echo $estado['id_estados'] ?></td>
                                    <td><?php echo $estado['tipo_estado'] ?></td>
                                    <td><?php echo $estado['descripcion'] ?></td>
                                    <td>
                                        <form class="needs-validation" action="../controladores/estado_controlador.php" method="post">
                                            <input type="hidden" name="id_estados" value="<?= $estado['id_estados'] ?>">
                                            <input type="hidden" name="accion" value="baja">
                                            <button type="submit" class="btn btn-danger">Borrar</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="needs-validation" action="../controladores/estado_controlador.php" method="post">
                                            <input type="hidden" name="id_estados" value="<?= $estado['id_estados'] ?>">
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
                    <form class="needs-validation" action="../controladores/estado_controlador.php" method="post">
                        <div class="mb-3 mt-3">
                            <label for="estado" class="form-label">Nombre de Estado:</label>
                            <input type="text" class="form-control" id="estado" placeholder="Ingrese estado" name="estado" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion_estado" class="form-label">Descripción:</label>
                            <input type="text" class="form-control" id="descripcion_estado" placeholder="Ingrese Descripción" name="descripcion_estado" required>
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
