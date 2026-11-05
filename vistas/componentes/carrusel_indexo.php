<div id="carouselHero"
    class="carousel slide carousel-fade hero-carousel"
    data-bs-ride="carousel"
    data-bs-interval="6000">

    <!-- Barra superior -->
    <div class="progress hero-progress">
        <div class="progress-bar" id="carouselProgress"></div>
    </div>

    <div class="carousel-inner">

        <!-- Slide 1 -->
        <div class="carousel-item active">
            <img src="assets/images/doctores_marketing_negocios-1024x683.jpg" class="hero-img" alt="">

            <div class="hero-overlay"></div>

            <div class="container hero-content">
                <div class="row align-items-center h-100">
                    <div class="col-lg-7">

                        <span class="hero-badge">
                            Siempre al servicio del paciente
                        </span>

                        <h1>
                            Cuidamos tu salud con
                            excelencia médica
                        </h1>

                        <p>
                            Profesionales especializados y tecnología
                            de última generación para brindarte
                            una atención segura y humana.
                        </p>

                        <a href="#" class="btn hero-btn">
                            Solicitar turno
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="assets/images/6511c213dadb6.jpg" class="hero-img" alt="">

            <div class="hero-overlay"></div>

            <div class="container hero-content">
                <div class="row align-items-center h-100">
                    <div class="col-lg-7">

                        <span class="hero-badge">
                            Profesionales certificados
                        </span>

                        <h1>
                            Especialistas preparados
                            para vos
                        </h1>

                        <p>
                            Atención médica integral con
                            estándares clínicos de máxima calidad.
                        </p>

                        <a href="#" class="btn hero-btn">
                            Conocer profesionales
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <img src="assets/images/tres-tipos-de-medicos.jpg" class="hero-img" alt="">

            <div class="hero-overlay"></div>

            <div class="container hero-content">
                <div class="row align-items-center h-100">
                    <div class="col-lg-7">

                        <span class="hero-badge">
                            Innovación y confianza
                        </span>

                        <h1>
                            Tecnología médica
                            para tu bienestar
                        </h1>

                        <p>
                            Gestión digital de turnos y
                            atención eficiente pensada
                            para cada paciente.
                        </p>

                        <a href="#" class="btn hero-btn">
                            Reservar ahora
                        </a>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Flechas -->
    <button class="carousel-control-prev" type="button"
        data-bs-target="#carouselHero"
        data-bs-slide="prev">

        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button"
        data-bs-target="#carouselHero"
        data-bs-slide="next">

        <span class="carousel-control-next-icon"></span>
    </button>

</div>


<style>
.hero-carousel {
    height: 92vh;
    min-height: 650px;
    overflow: hidden;
    position: relative;
}

.hero-img {
    width: 100%;
    height: 92vh;
    object-fit: cover;
    animation: zoomHero 8s ease-in-out infinite alternate;
}

@keyframes zoomHero {
    from { transform: scale(1); }
    to { transform: scale(1.08); }
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(
            90deg,
            rgba(8,36,46,.82) 0%,
            rgba(8,36,46,.55) 40%,
            rgba(8,36,46,.2) 100%
        );
}

.hero-content {
    position: absolute;
    inset: 0;
    z-index: 10;
    color: white;
}

.hero-badge {
    background: rgba(255,255,255,.12);
    backdrop-filter: blur(8px);
    padding: 10px 18px;
    border-radius: 30px;
    font-size: .9rem;
    letter-spacing: .8px;
}

.hero-content h1 {
    font-size: 4rem;
    font-weight: 800;
    line-height: 1.1;
    margin: 25px 0;
    max-width: 700px;
}

.hero-content p {
    font-size: 1.2rem;
    max-width: 600px;
    opacity: .92;
    margin-bottom: 35px;
}

.hero-btn {
    background: #007DC6;
    color: white;
    padding: 14px 34px;
    border-radius: 10px;
    font-weight: 600;
    transition: .35s;
}

.hero-btn:hover {
    background: #024296;
    transform: translateY(-3px);
    color: white;
}

.carousel-control-prev,
.carousel-control-next {
    width: 70px;
    opacity: .85;
}

.hero-progress {
    position: absolute;
    top: 0;
    z-index: 50;
    width: 100%;
    height: 4px;
    border-radius: 0;
    background: rgba(255,255,255,.15);
}

.hero-progress .progress-bar {
    width: 0%;
    background: #007DC6;
    transition: width linear;
}

@media (max-width: 992px) {
    .hero-content h1 {
        font-size: 2.6rem;
    }

    .hero-content p {
        font-size: 1rem;
    }
}
</style>


<script>
const interval = 6000;
const progressBar = document.getElementById('carouselProgress');
const carousel = document.getElementById('carouselHero');

function animateProgress() {
    progressBar.style.transition = 'none';
    progressBar.style.width = '0%';

    void progressBar.offsetWidth;

    progressBar.style.transition = `width ${interval}ms linear`;
    progressBar.style.width = '100%';
}

animateProgress();

carousel.addEventListener('slide.bs.carousel', animateProgress);
</script>