<section class="mission-section">

    <div class="row align-items-center g-5">

        <!-- Imagen -->
        <div class="col-lg-6">

            <div class="mission-image-wrapper">

                <img src="../../assets/images/6511c213dadb6.jpg"
                    class="mission-image"
                    alt="Misión institucional">

                <div class="mission-floating-card">

                    <h4>Excelencia médica</h4>
                    <p>Compromiso humano y profesional</p>

                </div>

            </div>

        </div>


        <!-- Contenido -->
        <div class="col-lg-6">

            <span class="mission-badge">
                Nuestra misión
            </span>

            <h2 class="mission-title">
                Brindar atención médica
                moderna y accesible
            </h2>

            <p class="mission-text">
                Trabajamos para ofrecer una experiencia médica
                integral basada en la excelencia profesional,
                la innovación tecnológica y el acompañamiento
                humano en cada etapa de atención.
            </p>

            <p class="mission-text">
                Nuestro compromiso es garantizar servicios
                seguros, eficientes y orientados al bienestar
                de cada paciente, promoviendo una medicina
                cercana y de calidad.
            </p>

            <div class="mission-features">

                <div class="mission-item">
                    ✓ Atención personalizada
                </div>

                <div class="mission-item">
                    ✓ Tecnología médica avanzada
                </div>

                <div class="mission-item">
                    ✓ Gestión digital eficiente
                </div>

                <div class="mission-item">
                    ✓ Profesionales especializados
                </div>

            </div>

        </div>

    </div>

</section>



<style>

.mission-section{
    padding:40px 0;
}

.mission-image-wrapper{
    position:relative;
}

.mission-image{
    width:100%;
    height:100%;
    object-fit:cover;
    border-radius:28px;
    box-shadow:0 20px 55px rgba(0,0,0,.08);
}

.mission-floating-card{
    position:absolute;
    bottom:30px;
    left:30px;
    background:rgba(255,255,255,.95);
    backdrop-filter:blur(10px);
    padding:22px 28px;
    border-radius:18px;
    box-shadow:0 15px 40px rgba(0,0,0,.08);
}

.mission-floating-card h4{
    margin:0;
    color:#10323b;
    font-weight:700;
}

.mission-floating-card p{
    margin:5px 0 0;
    color:#6c757d;
    font-size:.95rem;
}

.mission-badge{
    background:#e8fbfa;
    color:#00a79d;
    padding:10px 18px;
    border-radius:30px;
    font-weight:600;
    display:inline-block;
    margin-bottom:20px;
}

.mission-title{
    font-size:3rem;
    font-weight:800;
    line-height:1.2;
    color:#10323b;
    margin-bottom:25px;
}

.mission-text{
    color:#6c757d;
    font-size:1.05rem;
    line-height:1.9;
    margin-bottom:18px;
}

.mission-features{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:18px;
    margin-top:35px;
}

.mission-item{
    background:#f8fcfc;
    padding:18px;
    border-radius:14px;
    font-weight:600;
    color:#10323b;
    transition:.35s;
}

.mission-item:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 30px rgba(0,0,0,.06);
}

@media(max-width:992px){

    .mission-title{
        font-size:2.2rem;
    }

    .mission-features{
        grid-template-columns:1fr;
    }

    .mission-floating-card{
        left:15px;
        bottom:15px;
        padding:18px 22px;
    }

}

</style>