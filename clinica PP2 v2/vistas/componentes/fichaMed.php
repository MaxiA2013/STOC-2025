estado<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .form-glass {
        background: rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(12px);
        padding: 25px;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        border-left: 5px solid #0d6efd;
        padding-left: 10px;
        margin-bottom: 20px;
        color: #003366;
    }

    .form-label {
        font-weight: 600;
        color: #003366;
    }

    .btn-custom {
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 10px;
    }
</style>

<div class="container my-5">
    <div class="form-glass">

        <h2 class="text-center mb-4">Ficha Médica de Paciente</h2>

        <form action="#" method="post">

            <!-- DATOS PERSONALES -->
            <div class="section-title">Datos Personales</div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" name="nombre">
                </div>

                <div class="col-md-3">
                    <label class="form-label">DNI</label>
                    <input type="text" class="form-control" name="dni">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" name="nacimiento">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono">
                </div>

                <div class="col-md-8">
                    <label class="form-label">Domicilio</label>
                    <input type="text" class="form-control" name="domicilio">
                </div>
            </div>

            <!-- ANTECEDENTES MÉDICOS -->
            <div class="section-title">Antecedentes Médicos</div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Enfermedades Previas</label>
                    <textarea class="form-control" rows="3" name="enfermedades_previas"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Alergias</label>
                    <textarea class="form-control" rows="3" name="alergias"></textarea>
                </div>
            </div>

            <!-- INFORMACIÓN CLÍNICA -->
            <div class="section-title">Información Clínica Actual</div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Altura (cm)</label>
                    <input type="number" class="form-control" name="altura">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Peso (kg)</label>
                    <input type="number" class="form-control" name="peso">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Grupo Sanguíneo</label>
                    <select class="form-select" name="grupo">
                        <option value="">Seleccione...</option>
                        <option>A+</option><option>A-</option>
                        <option>B+</option><option>B-</option>
                        <option>AB+</option><option>AB-</option>
                        <option>O+</option><option>O-</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Medicación Actual</label>
                    <textarea class="form-control" rows="3" name="medicacion"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Motivo de la Consulta</label>
                    <textarea class="form-control" rows="3" name="motivo"></textarea>
                </div>
            </div>

            <!-- OBSERVACIONES -->
            <div class="section-title">Observaciones del Profesional</div>

            <div class="mb-4">
                <textarea class="form-control" rows="4" name="observaciones"></textarea>
            </div>

            <!-- BOTÓN -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-custom">
                    Guardar Ficha Médica
                </button>
            </div>

        </form>
    </div>
</div>
