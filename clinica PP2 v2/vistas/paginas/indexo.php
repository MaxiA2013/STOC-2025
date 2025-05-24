<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<!-- pagina de inicio de la vista externa-->

<body>
    <header>
        <?php require_once '../componentes/nav_bar.php'; ?>
    </header>

    <main>
        <section> <!-- carrusel -->
           <?php require_once('../componentes/carrusel_indexo.php') ?>
        </section>


        <?php include('../componentes/separador.php') ?><!--divide entre section -->


        <section> <!-- seccion de busqueda rapida de turnos-->
            <?php require_once('../componentes/buscador_turnos.php'); ?> <!-- llama al buscador de turnos en compoenentes -->
        </section>


        <?php include('../componentes/separador.php') ?><!--divide entre section -->


        <section> <!-- seccion nosotros -->
            <?php require_once('../componentes/nosotros_indexo.php'); ?>
        </section>


        <?php include('../componentes/separador.php') ?><!--divide entre section -->


        <section>
            <?php require_once('../componentes/porque_escogernos.php'); ?>
        </section>

        <?php require_once('../componentes/separador.php') ?> <!--divide entre section -->
    </main>

    <footer>
        <?php include('../componentes/footer.php') ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>