<?php
include 'conexion.php';

// Detectamos si el JavaScript nos envió un CURP por POST
if (isset($_POST['curp'])) {
    $curp = mysqli_real_escape_string($conexion, $_POST['curp']);

    // IMPORTANTE: Usamos el nombre de columna 'paciente_curp' que vimos en tu tabla
    $sql = "UPDATE turnos SET estado = 'Atendido' WHERE paciente_curp = '$curp'";
    
    if (mysqli_query($conexion, $sql)) {
        if (mysqli_affected_rows($conexion) > 0) {
            echo "EXITO: Actualizado en BD";
        } else {
            // Si sale esto, el CURP que mandó el JS no existe en MySQL
            echo "ERROR: El CURP '$curp' no existe en la tabla turnos";
        }
    } else {
        echo "ERROR SQL: " . mysqli_error($conexion);
    }
    exit(); // Detenemos aquí para que no imprima el resto del HTML
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>S.I.G.A.M. - Médico</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

 <style>
body {
background: linear-gradient(150deg, #BBFCC6, #BBFCF9);
 min-height: 100vh;
 display: flex;
 align-items: center;
 justify-content: center;
 font-family: 'Courier New', Courier, monospace;
 padding: 20px;
 }
  .medico-container {
 background: white;
 border-radius: 15px;
 box-shadow: 0 10px 25px rgba(0,0,0,0.1);
 width: 100%;
max-width: 500px;
 padding: 30px;
 text-align: center;
 }
 h1 {
border-bottom: 2px solid #8a91f3;
 padding-bottom: 15px;
 margin-bottom: 30px;
color: #000000;
 font-weight: bold;
 font-size: 1.8rem;
 }
.paciente-box {
 background-color: #f8f9fa;
 border-radius: 12px;
 padding: 25px;
 margin-bottom: 25px;
  border: 1px solid #e9ecef;
 }
 #turnoActual {
 color: #8a91f3;
 font-size: 2.5rem;
 font-weight: bold;
 margin: 10px 0;
 }
 .label-paciente {
 color: #000000;
text-transform: uppercase;
font-size: 0.9rem;
letter-spacing: 1px;
}
#nombreActual {
 font-size: 1.2rem;
 color: #212529;
 font-weight: 600;
 }
 .btn-call {
background-color: #8a91f3;
 border: none;
 color: white;
padding: 12px;
font-weight: bold;
 width: 100%;
 margin-bottom: 10px;
 }
 .btn-call:hover {
 background-color: #767cd8;
}
 .btn-finish {
background-color: #28a745;
 border: none;
 color: white;
 padding: 12px;
 font-weight: bold;
width: 100%;
}
 .btn-finish:hover {
 background-color: #218838;
 }
 .logout-link {
 display: inline-block;
 margin-top: 25px;
 color: #fe0505;
 text-decoration: none;
 font-weight: bold;
 }
.logout-link:hover {
 text-decoration: underline;
}
</style>
</head>

<body>

 <div class="medico-container shadow">
<h1><i class="fa-solid"></i>Atención Médica</h1>
<div class="paciente-box shadow-sm">
<p class="label-paciente fw-bold">Paciente en Turno</p>
 <h2 id="turnoActual">SIN TURNO</h2>
<hr>
 <p class="mb-0"><strong><i class="fa-solid"></i>Nombre:</strong></p>
<span id="nombreActual">---</span>
</div>

 <div class="botones">
<button class="btn btn-call" onclick="llamarTurno()">
Llamar Siguiente
</button>
 <button class="btn btn-finish" onclick="finalizarTurno()">
Finalizar Consulta
</button>
 </div>

 <a href="index.php" class="logout-link">
Cerrar Sesión
</a>
</div>

<script>
let turnoActual = null;

//automático al iniciar la página
window.onload = function() {

    let actualRaw = localStorage.getItem("turnoActual");
    let actual = actualRaw ? JSON.parse(actualRaw) : null;

    if (actual) {
        turnoActual = actual;

        document.getElementById("turnoActual").innerText = actual.turno;
        document.getElementById("nombreActual").innerText =
            actual.nombre + " (" + actual.prioridad + ")";
    } else {
        document.getElementById("turnoActual").innerText = "SIN TURNO";
        document.getElementById("nombreActual").innerText = "---";
    }
};



// LLAMAR TURNO
function llamarTurno() {
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];

    if (turnos.length === 0) {
        alert("NO HAY TURNOS");
        return;
    }

    // ORDENAR POR PRIORIDAD
    turnos.sort((a, b) => {
        if (a.prioridad === "Urgente" && b.prioridad === "Normal") return -1;
        if (a.prioridad === "Normal" && b.prioridad === "Urgente") return 1;
        return 0;
    });

    turnoActual = turnos.shift();

    //GUARDAR
    localStorage.setItem("turnoActual", JSON.stringify(turnoActual));
    localStorage.setItem("turnos", JSON.stringify(turnos));

    document.getElementById("turnoActual").innerText = turnoActual.turno;
    document.getElementById("nombreActual").innerText =
        turnoActual.nombre + " (" + turnoActual.prioridad + ")";
}

   

// FINALIZAR TURNO
function finalizarTurno() {
    if (turnoActual == null) {
        alert("SIN TURNOS EN ESPERA");
        return;
    }

    // CAMBIO AQUÍ: En lugar de "actualizar_estado.php", pon "medico.php"
    fetch("medico.php", { 
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "curp=" + encodeURIComponent(turnoActual.curp) 
    })
    .then(res => res.text())
    .then(data => {
        console.log("Respuesta:", data);
        alert("Turno marcado como ATENDIDO en el sistema");
        
        // Limpiar la pantalla
        localStorage.removeItem("turnoActual");
        document.getElementById("turnoActual").innerText = "SIN TURNO";
        document.getElementById("nombreActual").innerText = "---";
        turnoActual = null;
    })
    .catch(err => console.error("Error:", err));
}

   

</script>

</body>
</html>