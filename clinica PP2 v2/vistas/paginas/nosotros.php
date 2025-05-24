<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<style>
    .nosotros {
        background-color: aqua;
        height: 250px;
        padding: 20px;
        text-align: center;
    }
</style>

<body>
    <header>
        <?php include '../componentes/nav_bar.php'; ?>
    </header>
    <main>

        <section>
            <div class="nosotros">
                <h1>Nosotros</h1>
                <p>Conocé un poco más de nosotros, nuestra historia y nuestras proyecciones</p>
            </div>
        </section>

        <?php include '../componentes/separador.php'; ?> <!--separador -->

        <section>
            <?php require_once '../componentes/mision.php'; ?> <!-- seccion de mision-->
            <div class="row mb-2">
                <div class="col-md-6">
                    <?php require_once '../componentes/valores.php'; ?> <!-- seccion de valores-->
                </div>
                <div class="col-md-6">
                    <?php require_once '../componentes/vision.php'; ?> <!-- seccion de vision-->
                </div>
            </div>
        </section>

        <?php include '../componentes/separador.php'; ?> <!--separador -->

    </main>
    <footer>
        <?php include '../componentes/footer.php'; ?> <!-- seccion footer-->
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>