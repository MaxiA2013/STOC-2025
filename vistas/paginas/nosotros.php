<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

    <!-- HERO -->
    <section class="about-hero">

        <div class="hero-overlay"></div>

        <div class="container position-relative z-2">

            <div class="row justify-content-center text-center">

                <div class="col-lg-8">

                    <span class="hero-badge">
                        Nuestra institución
                    </span>

                    <h1 class="hero-title">
                        Comprometidos con una
                        medicina más humana
                    </h1>

                    <p class="hero-subtitle">
                        Más que un centro médico, somos una comunidad
                        profesional dedicada al bienestar integral
                        de cada paciente.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- ESTADISTICAS -->
    <section class="stats-section">

        <div class="container">

            <div class="stats-wrapper">

                <div class="stat-item">
                    <h3>+15</h3>
                    <p>Años de experiencia</p>
                </div>

                <div class="stat-item">
                    <h3>+80</h3>
                    <p>Profesionales</p>
                </div>

                <div class="stat-item">
                    <h3>+25k</h3>
                    <p>Pacientes atendidos</p>
                </div>

                <div class="stat-item">
                    <h3>24/7</h3>
                    <p>Gestión online</p>
                </div>

            </div>

        </div>

    </section>


    <?php include 'vistas/componentes/separador.php'; ?>


    <!-- MISION -->
    <section class="container py-5">
        <?php require_once 'vistas/componentes/mision.php'; ?>
    </section>


    <!-- VALORES Y VISION -->
    <section class="container py-5">

        <div class="row g-4">

            <div class="col-lg-6">
                <div class="info-card h-100">
                    <?php require_once 'vistas/componentes/valores.php'; ?>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="info-card h-100">
                    <?php require_once 'vistas/componentes/vision.php'; ?>
                </div>
            </div>

        </div>

    </section>


    <?php include 'vistas/componentes/separador.php'; ?>


    <!-- CTA -->
    <section class="cta-section">

        <div class="container text-center">

            <h2>
                Innovación médica pensada para vos
            </h2>

            <p>
                Nuestro compromiso es brindar atención moderna,
                segura y accesible a través de tecnología y
                excelencia profesional.
            </p>

            <a href="#" class="cta-btn">
                Solicitar turno
            </a>

        </div>

    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>



<style>

.about-hero{
    position:relative;
    min-height:65vh;
    display:flex;
    align-items:center;
    justify-content:center;
    background-image:url('assets/images/doctores_marketing_negocios-1024x683.jpg');
    background-size:cover;
    background-position:center;
    overflow:hidden;
}

.hero-overlay{
    position:absolute;
    inset:0;
    background:linear-gradient(
        90deg,
        rgba(8,36,46,.88) 0%,
        rgba(8,36,46,.60) 50%,
        rgba(8,36,46,.35) 100%
    );
}

.hero-badge{
    background:rgba(255,255,255,.12);
    backdrop-filter:blur(8px);
    color:white;
    padding:10px 18px;
    border-radius:30px;
    font-weight:600;
    display:inline-block;
    margin-bottom:25px;
}

.hero-title{
    color:white;
    font-size:4rem;
    font-weight:800;
    line-height:1.1;
    margin-bottom:25px;
}

.hero-subtitle{
    color:rgba(255,255,255,.88);
    font-size:1.2rem;
    line-height:1.9;
}

/* ================= STATS ================= */

.stats-section{
    margin-top:-70px;
    position:relative;
    z-index:20;
}

.stats-wrapper{
    background:white;
    border-radius:28px;
    padding:40px;
    box-shadow:0 20px 55px rgba(0,0,0,.08);
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:30px;
}

.stat-item{
    text-align:center;
}

.stat-item h3{
    color:#00a79d;
    font-size:2.7rem;
    font-weight:800;
    margin-bottom:10px;
}

.stat-item p{
    color:#6c757d;
    margin:0;
}

/* ================= CARDS ================= */

.info-card{
    background:white;
    border-radius:24px;
    padding:40px;
    box-shadow:0 15px 45px rgba(0,0,0,.06);
    transition:.35s;
}

.info-card:hover{
    transform:translateY(-8px);
    box-shadow:0 25px 55px rgba(0,0,0,.09);
}

/* ================= CTA ================= */

.cta-section{
    padding:100px 0;
    background:linear-gradient(
        135deg,
        #0f2f38 0%,
        #144754 100%
    );
    color:white;
}

.cta-section h2{
    font-size:3rem;
    font-weight:800;
    margin-bottom:25px;
}

.cta-section p{
    max-width:750px;
    margin:auto;
    opacity:.9;
    line-height:1.9;
    font-size:1.1rem;
    margin-bottom:40px;
}

.cta-btn{
    display:inline-block;
    background:#00a79d;
    color:white;
    padding:16px 36px;
    border-radius:14px;
    text-decoration:none;
    font-weight:700;
    transition:.35s;
}

.cta-btn:hover{
    background:#00877f;
    color:white;
    transform:translateY(-4px);
}

/* ================= RESPONSIVE ================= */

@media(max-width:992px){

    .hero-title{
        font-size:2.5rem;
    }

    .stats-wrapper{
        grid-template-columns:repeat(2,1fr);
    }

    .cta-section h2{
        font-size:2.2rem;
    }

}

@media(max-width:576px){

    .stats-wrapper{
        grid-template-columns:1fr;
    }

}

</style>