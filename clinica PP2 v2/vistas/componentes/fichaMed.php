<?php
require_once "modelos/ficha_medica.php";

$id_cita = intval($_GET['id_cita'] ?? 0);
$fichaModel = new FichaMedica();
$ficha = $fichaModel->obtenerPorCita($id_cita);
?>

<div class="container mt-4">
    <h3>Ficha médica - Cita #<?= htmlspecialchars($id_cita) ?></h3>

    <form id="formFichaMedica">
        <input type="hidden" name="accion" value="guardar">
        <input type="hidden" name="cita_id_cita" value="<?= $id_cita ?>">

        <div class="mb-3">
            <label>Altura</label>
            <input type="text" name="altura" class="form-control"
                   value="<?= htmlspecialchars($ficha['altura'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Peso</label>
            <input type="text" name="peso" class="form-control"
                   value="<?= htmlspecialchars($ficha['peso'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label>Medicación actual</label>
            <textarea name="medicacion_actual" class="form-control"><?= htmlspecialchars($ficha['medicacion_actual'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control"><?= htmlspecialchars($ficha['observaciones'] ?? '') ?></textarea>
        </div>

        <button class="btn btn-success">Guardar</button>
    </form>
</div>

<script>
document.getElementById('formFichaMedica').addEventListener('submit', function(e){
    e.preventDefault();
    const form = this;

    fetch('controladores/ficha_medica/ficha_medica_controlador.php', {
        method: 'POST',
        body: new FormData(form)
    })
    .then(r => r.json())
    .then(data => {
        if(data.success){
            alert('Ficha médica guardada correctamente');
        } else {
            alert('Error: ' + (data.error || 'No se pudo guardar'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error de conexión');
    });
});
</script>
