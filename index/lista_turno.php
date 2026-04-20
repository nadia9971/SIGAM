<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>S.I.G.A.M. - Gestión de Turnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(150deg, #BBFCC6, #BBFCF9); min-height: 100vh; padding: 40px; font-family: 'Courier New', Courier, monospace; }
        .container-gestion { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .table { margin-top: 20px; }
        .btn-regresar { color: #8a91f3; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container container-gestion">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <h2 class="fw-bold">LISTA DE ESPERA ACTUAL</h2>
        <a href="recepcion.php" class="btn-regresar"><i class="fa-solid fa-arrow-left"></i> Volver a Registro</a>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Turno</th>
                <th>Paciente</th>
                <th>Especialidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaTurnos"></tbody>
    </table>
</div>

<script>
function cargarTurnos(){
    let tabla = document.getElementById("tablaTurnos");
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];

    tabla.innerHTML = "";

    if(turnos.length === 0){
        tabla.innerHTML = `
            <tr>
                <td colspan="4" class="text-center">No hay turnos registrados</td>
            </tr>
        `;
        return;
    }

    turnos.forEach((t, index) => {
        let partes = t.split(" - ");
        let turno = partes[0] || "";
        let nombre = partes[1] || "";
        let esp = partes[2] || "";

        tabla.innerHTML += `
            <tr>
                <td><strong>${turno}</strong></td>
                <td>${nombre}</td>
                <td>${esp}</td>
                <td>
                    <button class="btn btn-sm btn-secondary me-1" onclick="modificar(${index})">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                   
                    <button class="btn btn-sm btn-danger" onclick="eliminar(${index})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}

function eliminar(index){
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];
    turnos.splice(index,1);
    localStorage.setItem("turnos", JSON.stringify(turnos));
    cargarTurnos();
}

function modificar(index){
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];

    let partes = turnos[index].split(" - ");
    let turno = partes[0];
    let esp = partes[2];

    let nuevoNombre = prompt("Nuevo nombre:", partes[1]);

    if(nuevoNombre){
        turnos[index] = turno + " - " + nuevoNombre + " - " + esp;
        localStorage.setItem("turnos", JSON.stringify(turnos));
        cargarTurnos();
    }
}

// 🔥 NUEVA FUNCIÓN (NO rompe nada)
function atender(index){
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];
    let historial = JSON.parse(localStorage.getItem("historial")) || [];

    let registro = turnos[index] + " - ATENDIDO";

    historial.push(registro);

    localStorage.setItem("historial", JSON.stringify(historial));

    turnos.splice(index,1);
    localStorage.setItem("turnos", JSON.stringify(turnos));

    cargarTurnos();
}

window.onload = cargarTurnos;
</script>

</body>
</html>