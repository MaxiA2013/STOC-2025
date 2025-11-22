<?php  
include_once "modelos/sintomas.php";

$sintoma = new Sintomas();
$lista_sintomas = $sintoma->consultarVariosSintomas();
?>
<h2 class="text-center">Gestión de Síntomas</h2>

<button class="btn btn-primary mb-3" onclick="abrirModal()">Nuevo síntoma</button>

<table class="table table-bordered" id="tablaSintomas">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre del síntoma</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="bodyTabla">
        <?php
        require_once "modelos/sintomas.php";
        $s = new Sintomas();
        $lista = $s->consultarVariosSintomas();
        while ($fila = $lista->fetch_assoc()):
        ?>
        <tr id="fila<?= $fila["id_sintomas"] ?>">
            <td><?= $fila["id_sintomas"] ?></td>
            <td><?= $fila["nombre_sintomas"] ?></td>
            <td><?= $fila["descripcion"] ?></td>
            <td>
                <button class="btn btn-warning btn-sm"
                        onclick="editar(<?= $fila['id_sintomas'] ?>,'<?= $fila['nombre_sintomas'] ?>','<?= $fila['descripcion'] ?>')">
                    Editar
                </button>
                <button class="btn btn-danger btn-sm" onclick="eliminar(<?= $fila['id_sintomas'] ?>)">Eliminar</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Modal -->
<div class="modal" id="modalForm" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <input type="hidden" id="id_sintomas">

            <label>Nombre del síntoma</label>
            <input type="text" id="nombre_sintomas" class="form-control">

            <label>Descripción</label>
            <textarea id="descripcion" class="form-control"></textarea>

            <button class="btn btn-success mt-3" onclick="guardar()">Guardar</button>
            <button class="btn btn-secondary mt-3" onclick="cerrarModal()">Cancelar</button>
        </div>
    </div>
</div>

<script>
// abrir modal vacío
function abrirModal() {
    document.getElementById("id_sintomas").value = "";
    document.getElementById("nombre_sintomas").value = "";
    document.getElementById("descripcion").value = "";
    document.getElementById("modalForm").style.display = "block";
}

function cerrarModal() {
    document.getElementById("modalForm").style.display = "none";
}

function editar(id, nombre, descripcion) {
    document.getElementById("id_sintomas").value = id;
    document.getElementById("nombre_sintomas").value = nombre;
    document.getElementById("descripcion").value = descripcion;
    document.getElementById("modalForm").style.display = "block";
}

function guardar() {

    let id = document.getElementById("id_sintomas").value;
    let nombre = document.getElementById("nombre_sintomas").value;
    let descripcion = document.getElementById("descripcion").value;

    let form = new FormData();
    form.append("nombre_sintomas", nombre);
    form.append("descripcion", descripcion);

    if (id === "") {
        form.append("action", "insertar");
    } else {
        form.append("action", "actualizar");
        form.append("id_sintomas", id);
    }

    fetch("controladores/sintomas_controlador.php", {
        method: "POST",
        body: form
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === "ok") location.reload();
        else alert(data.message);
    });
}

function eliminar(id) {

    if (!confirm("¿Eliminar síntoma?")) return;

    let form = new FormData();
    form.append("action", "eliminar");
    form.append("id_sintomas", id);

    fetch("controladores/sintomas_controlador.php", {
        method: "POST",
        body: form
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === "ok") {
            document.getElementById("fila" + id).remove();
        }
    });
}
</script>