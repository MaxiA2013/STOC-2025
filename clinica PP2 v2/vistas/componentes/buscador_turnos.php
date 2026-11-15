<section class="appointment-section container col-xxl-8 px-6 py-5">

    <div class="row align-items-center g-5">

        <!-- Texto -->
        <div class="col-lg-6">

            <span class="section-badge">
                Turnos Online
            </span>

            <h2 class="section-title">
                Agendá tu consulta médica
                en segundos
            </h2>

            <p class="section-text">
                Elegí especialidad, profesional y horario disponible
                en tiempo real. Una experiencia rápida, segura y
                diseñada para cuidar tu tiempo.
            </p>

            <div class="stats-grid">

                <div class="stat-box">
                    <h4>+80</h4>
                    <span>Especialistas</span>
                </div>

                <div class="stat-box">
                    <h4>+25k</h4>
                    <span>Pacientes</span>
                </div>

                <div class="stat-box">
                    <h4>24/7</h4>
                    <span>Disponibilidad</span>
                </div>

            </div>

        </div>


        <!-- Formulario -->
        <div class="col-lg-6">

            <div class="appointment-card">

                <h4 class="mb-4 fw-bold">
                    Buscar turno
                </h4>

                <form>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control custom-input" id="fecha">
                        <label for="fecha">Fecha</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="time" class="form-control custom-input" id="hora">
                        <label for="hora">Hora</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select custom-input" id="especialidad">
                            <option selected>Seleccionar</option>
                            <option>Cardiología</option>
                            <option>Pediatría</option>
                            <option>Clínica médica</option>
                            <option>Dermatología</option>
                        </select>
                        <label>Especialidad</label>
                    </div>

                    <div class="form-floating mb-4">
                        <select class="form-select custom-input" id="obra">
                            <option selected>Seleccionar</option>
                            <option>OSDE</option>
                            <option>Swiss Medical</option>
                            <option>Medifé</option>
                            <option>Particular</option>
                        </select>
                        <label>Obra Social</label>
                    </div>

                    <button class="btn appointment-btn w-100">
                        Buscar disponibilidad
                    </button>

                </form>

            </div>

        </div>

    </div>

</section>


<style>

.appointment-section{
    padding:80px 0;
}

.section-badge{
    background:#eff1f1;
    color:#024296;
    padding:10px 18px;
    border-radius:30px;
    font-weight:600;
    display:inline-block;
    margin-bottom:25px;
}

.section-title{
    font-size:3rem;
    font-weight:800;
    line-height:1.15;
    color:#000967;
    margin-bottom:25px;
}

.section-text{
    font-size:1.1rem;
    color:#6c757d;
    line-height:1.8;
    max-width:550px;
}

.stats-grid{
    display:flex;
    gap:20px;
    margin-top:40px;
    flex-wrap:wrap;
}

.stat-box{
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 10px 35px rgba(0,0,0,.07);
    min-width:140px;
    transition:.35s;
}

.stat-box:hover{
    transform:translateY(-8px);
}

.stat-box h4{
    color:#007dc6;
    font-size:2rem;
    font-weight:800;
    margin:0;
}

.stat-box span{
    color:#6c757d;
    font-size:.95rem;
}

.appointment-card{
    background:rgba(255,255,255,.92);
    backdrop-filter:blur(16px);
    padding:40px;
    border-radius:24px;
    box-shadow:0 20px 50px rgba(0,0,0,.08);
}

.custom-input{
    border-radius:14px;
    border:1px solid #e9ecef;
    min-height:60px;
}

.custom-input:focus{
    border-color:#00a79d;
    box-shadow:0 0 0 .2rem #02429615;
}

.appointment-btn{
    background:#007dc6;
    color:white;
    border:none;
    padding:16px;
    border-radius:14px;
    font-weight:700;
    transition:.35s;
}

.appointment-btn:hover{
    background:#024296;
    transform:translateY(-3px);
    color:white;
}

@media(max-width:992px){

    .section-title{
        font-size:2.2rem;
    }

    .appointment-card{
        padding:30px;
    }

}

</style>