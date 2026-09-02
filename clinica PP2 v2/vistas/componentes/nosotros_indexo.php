<section class="about-section">

    <div class="container">

        <div class="row align-items-center g-5">

            <!-- Imagen -->
            <div class="col-lg-6">

                <div class="about-image-wrapper">

                    <img src="assets/images/doctores_marketing_negocios-1024x683.jpg"
                        class="about-image"
                        alt="Equipo médico">

                    <div class="about-floating-card">

                        <h3>+15 años</h3>
                        <p>Comprometidos con tu salud</p>

                    </div>

                </div>

            </div>


            <!-- Contenido -->
            <div class="col-lg-6">

                <span class="about-badge">
                    Sobre nosotros
                </span>

                <h2 class="about-title">
                    Medicina moderna con
                    atención humana
                </h2>

                <p class="about-text">
                    Somos una institución médica orientada a brindar
                    atención integral con excelencia profesional,
                    innovación tecnológica y un trato cercano que
                    pone al paciente en el centro de cada decisión.
                </p>

                <p class="about-text">
                    Nuestro sistema digital permite una experiencia
                    médica ágil, segura y accesible para gestionar
                    turnos, consultas y seguimiento clínico de forma
                    inteligente.
                </p>

                <div class="about-features">

                    <div class="about-item">
                        ✓ Profesionales certificados
                    </div>

                    <div class="about-item">
                        ✓ Tecnología clínica avanzada
                    </div>

                    <div class="about-item">
                        ✓ Atención personalizada
                    </div>

                    <div class="about-item">
                        ✓ Gestión médica digital
                    </div>

                </div>

                <a href="#" class="about-btn">
                    Conocé más
                </a>

            </div>

        </div>

    </div>

</section>


<style>

.about-section{
    padding:110px 0;
    background:#ffffff;
}

.about-image-wrapper{
    position:relative;
}

.about-image{
    width:100%;
    border-radius:28px;
    object-fit:cover;
    box-shadow:0 20px 55px rgba(0,0,0,.08);
}

.about-floating-card{
    position:absolute;
    bottom:35px;
    right:-30px;
    background:#007dc6;
    color:white;
    padding:30px;
    border-radius:22px;
    box-shadow:0 20px 45px rgba(0,167,157,.28);
}

.about-floating-card h3{
    font-size:2rem;
    font-weight:800;
    margin:0;
}

.about-floating-card p{
    margin:0;
    opacity:.95;
}

.about-badge{
    background:#afcedf;
    color:#024296;
    padding:10px 18px;
    border-radius:30px;
    font-weight:600;
    display:inline-block;
    margin-bottom:20px;
}

.about-title{
    font-size:3rem;
    font-weight:800;
    color:#000967;
    line-height:1.2;
    margin-bottom:25px;
}

.about-text{
    color:#6c757d;
    line-height:1.9;
    font-size:1.05rem;
    margin-bottom:18px;
}

.about-features{
    margin:35px 0;
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:18px;
}

.about-item{
    background:#afcedf;
    padding:18px;
    border-radius:14px;
    font-weight:600;
    color:#10323b;
    transition:.35s;
}

.about-item:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 30px rgba(0,0,0,.06);
}

.about-btn{
    display:inline-block;
    background:#007dc6;
    color:white;
    padding:16px 34px;
    border-radius:14px;
    text-decoration:none;
    font-weight:700;
    transition:.35s;
}

.about-btn:hover{
    background:#024296;
    color:white;
    transform:translateY(-3px);
}

@media(max-width:992px){

    .about-title{
        font-size:2.2rem;
    }

    .about-floating-card{
        right:10px;
        bottom:10px;
        padding:20px;
    }

    .about-features{
        grid-template-columns:1fr;
    }

}

</style>