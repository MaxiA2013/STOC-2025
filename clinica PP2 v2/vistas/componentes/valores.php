<div class="modern-info-card">

    <div class="modern-icon">
        💙
    </div>

    <span class="modern-badge">
        Nuestros valores
    </span>

    <h3 class="modern-title">
        Principios que guían
        nuestra atención
    </h3>

    <p class="modern-text">
        Creemos en una medicina basada en la empatía,
        el respeto y el compromiso profesional,
        priorizando siempre el bienestar integral
        de cada paciente.
    </p>

    <div class="modern-list">

        <div class="modern-item">
            ✓ Compromiso humano
        </div>

        <div class="modern-item">
            ✓ Ética profesional
        </div>

        <div class="modern-item">
            ✓ Transparencia
        </div>

        <div class="modern-item">
            ✓ Atención personalizada
        </div>

    </div>

</div>



<style>

.modern-info-card{
    background:white;
    border-radius:28px;
    padding:45px 35px;
    height:100%;
    box-shadow:0 15px 45px rgba(0,0,0,.06);
    transition:.4s;
    position:relative;
    overflow:hidden;
}

.modern-info-card::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:5px;
    background:#00a79d;
    transform:scaleX(0);
    transition:.4s;
}

.modern-info-card:hover::before{
    transform:scaleX(1);
}

.modern-info-card:hover{
    transform:translateY(-10px);
    box-shadow:0 25px 60px rgba(0,0,0,.08);
}

.modern-icon{
    width:80px;
    height:80px;
    border-radius:22px;
    background:#e8fbfa;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:2.2rem;
    margin-bottom:25px;
}

.modern-badge{
    background:#f3fffe;
    color:#00a79d;
    padding:8px 16px;
    border-radius:30px;
    font-weight:600;
    display:inline-block;
    margin-bottom:20px;
}

.modern-title{
    font-size:2rem;
    font-weight:800;
    color:#10323b;
    line-height:1.2;
    margin-bottom:22px;
}

.modern-text{
    color:#6c757d;
    line-height:1.9;
    margin-bottom:30px;
}

.modern-list{
    display:flex;
    flex-direction:column;
    gap:15px;
}

.modern-item{
    background:#f8fcfc;
    padding:16px;
    border-radius:14px;
    color:#10323b;
    font-weight:600;
    transition:.35s;
}

.modern-item:hover{
    transform:translateX(6px);
    box-shadow:0 10px 25px rgba(0,0,0,.05);
}

@media(max-width:992px){

    .modern-title{
        font-size:1.8rem;
    }

}

</style>