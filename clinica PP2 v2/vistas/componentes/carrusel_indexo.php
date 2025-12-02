<div id="carouselExampleRide" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
  <div class="carousel-inner" style="height: 600px;">
    <div class="carousel-item active h-100">
        <div class="container position-absolute">
            <h6>Siempre al servicio del paciente</h6>
            <h1>Conocé nuestros profesionales</h1>
        </div>
      <img src="assets/images/doctores_marketing_negocios-1024x683.jpg" class="d-block w-100 h-100 object-fit-cover" alt="...">
    </div>
    <div class="carousel-item h-100">
      <img src="assets/images/6511c213dadb6.jpg" class="d-block w-100 h-100 object-fit-cover" alt="...">
    </div>
    <div class="carousel-item h-100">
      <img src="assets/images/tres-tipos-de-medicos.jpg" class="d-block w-100 h-100 object-fit-cover" alt="...">
    </div>
  </div>

  <button class="carousel-control-prev h-25 bg-dark position-absolute top-50 start-0 translate-middle-y" style="width: 50px; margin: 15px;" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>

  <button class="carousel-control-next h-25 bg-dark position-absolute top-50 end-0 translate-middle-y" style="width: 50px;  margin: 15px;" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>

  <div class="progress" role="progressbar" aria-label="Progreso del carrusel" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar bg-info" id="carouselProgress" style="width: 0%;"></div>
  </div>
</div>

<style>
  /* Transición suave de la barra de progreso */
  .progress-bar {
    transition: width linear;
  }
</style>

<script>
  const interval = 5000; // 5 segundos
  const progressBar = document.getElementById('carouselProgress');
  const carousel = document.getElementById('carouselExampleRide');

  function animateProgressBar() {
    // Reinicia inmediatamente
    progressBar.style.transition = 'none';
    progressBar.style.width = '0%';

    // Forzar reflujo para reiniciar animación
    void progressBar.offsetWidth;

    // Inicia animación
    progressBar.style.transition = `width ${interval}ms linear`;
    progressBar.style.width = '100%';
  }

  // Iniciar la barra la primera vez
  animateProgressBar();

  // Reiniciar barra cada vez que cambia el slide
  carousel.addEventListener('slide.bs.carousel', () => {
    animateProgressBar();
  });
</script>
