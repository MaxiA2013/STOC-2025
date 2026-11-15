<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .contenedor {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg,
                    #afcedf 0%,
                    #ffffff 100%);
        }

        /* Gradiente arriba */
        .contenedor::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            /* Ajustar altura según necesidad */
            background: linear-gradient(to bottom, rgb(255, 255, 255, 100), transparent);
        }

        section:nth-child(2){
            background: -webkit-repeating-radial-gradient(#afcedf);
        }
    </style>
</head>
<!-- pagina de inicio de la vista externa-->

<body>
    <section> <!-- carrusel -->
        <?php require_once('vistas/componentes/carrusel_indexo.php') ?>
    </section>

    <?php include('vistas/componentes/separador.php') ?><!--divide entre section -->

    <section class="contenedor"> <!-- seccion de busqueda rapida de turnos-->
        <?php require_once('vistas/componentes/buscador_turnos.php'); ?> <!-- llama al buscador de turnos en compoenentes -->
    </section>


    <?php include('vistas/componentes/separador.php') ?><!--divide entre section -->


    <section class="contenedor"> <!-- seccion nosotros -->
        <?php require_once('vistas/componentes/nosotros_indexo.php'); ?>
    </section>


    <?php include('vistas/componentes/separador.php') ?><!--divide entre section -->


    <section class="contenedor">
        <?php require_once('vistas/componentes/porque_escogernos.php'); ?>
    </section>

    <?php require_once('vistas/componentes/separador.php') ?> <!--divide entre section -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>