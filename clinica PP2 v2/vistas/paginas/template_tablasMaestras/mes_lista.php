<div class="container mt-4">

    <h3>Gestión de Meses</h3>

    <form id="formMes" class="mb-3">
        <input type="hidden" id="id_mes">

        <div class="form-group">
            <label>Nombre del mes:</label>
            <input type="text" class="form-control" id="nombre_mes" placeholder="Ej: Enero">
        </div>

        <button class="btn btn-primary mt-2" type="submit">Guardar</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mes</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody id="tbodyMes"></tbody>
    </table>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    cargarMeses();

    document.getElementById("formMes").addEventListener("submit", function(e){
        e.preventDefault();
        guardarMes();
    });
});

function cargarMeses() {
    let data = new FormData();
    data.append("action", "listar");

    fetch("controladores/mes_controlador.php", {
        method: "POST",
        body: data
    })
    .then(res => res.json())
    .then(meses => {
        let tabla = "";

        meses.forEach(m => {
            tabla += `
                <tr>
                    <td>${m.id_mes}</td>
                    <td>${m.nombre_mes}</td>
                    <td>
                        <button onclick="editarMes(${m.id_mes}, '${m.nombre_mes}')" class="btn btn-warning btn-sm">Editar</button>
                        <button onclick="eliminarMes(${m.id_mes})" class="btn btn-danger btn-sm">Eliminar</button>
                    </td>
                </tr>
            `;
        });

        document.getElementById("tbodyMes").innerHTML = tabla;
    });
}

function guardarMes() {
    let nombre_mes = document.getElementById("nombre_mes").value;
    let id_mes = document.getElementById("id_mes").value;
    let action = id_mes === "" ? "insertar" : "actualizar";

    let data = new FormData();
    data.append("action", action);
    data.append("nombre_mes", nombre_mes);
    data.append("id_mes", id_mes);

    fetch("controladores/mes_controlador.php", {
        method: "POST",
        body: data
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.status === "ok") {
            document.getElementById("formMes").reset();
            cargarMeses();
        } else {
            alert(resp.message);
        }
    });
}

function eliminarMes(id) {
    if (!confirm("¿Eliminar mes?")) return;

    let data = new FormData();
    data.append("action", "eliminar");
    data.append("id_mes", id);

    fetch("controladores/mes_controlador.php", {
        method: "POST",
        body: data
    })
    .then(() => cargarMeses());
}

function editarMes(id, nombre) {
    document.getElementById("id_mes").value = id;
    document.getElementById("nombre_mes").value = nombre;
}

</script>
