<!DOCTYPE html>
<html lang="es">
    <audio id="sonidoUrgente" src="https://www.soundjay.com/button/beep-07.wav"></audio>
<head>
    <meta charset="UTF-8">
    <title>S.I.G.A.M. - Pantalla de Turnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(150deg, #BBFCC6, #BBFCF9) !important;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Courier New', Courier, monospace;
        }
        .tv-container {
            background: white !important;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 95%;
            height: 85vh; /* Ocupa la altura de la pantalla */
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        header {
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #8a91f3;
        }
        .main-content {
            display: flex;
            flex: 1; /* Ocupa el resto del espacio */
        }
        /*Izq: Próximos Turnos */
        .sidebar-espera {
            width: 30%;
            background: #f8f9fa;
            border-right: 2px solid #eee;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        /*Der: Turno Actual */
        .current-turn-area {
            width: 70%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        #turnoPrincipal {
            font-size: 15rem; /* Tamaño grande*/
            font-weight: bold;
            line-height: 1;
            margin: 20px 0;
        }
        .label-grande {
            font-size: 2.5rem;
            color: #090909;
            font-weight: bold;
            text-transform: uppercase;
        }
        #listaEspera {
            list-style: none;
            padding: 0;
        }
        #listaEspera li {
            background: #8a91f3;
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-size: 2.2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }




        @keyframes parpadeo {//URGENTES
    0% { opacity: 1; }
    50% { opacity: 0.3; }
    100% { opacity: 1; }
}

.urgente {
    color: #dc3545 !important;
    animation: parpadeo 1s infinite;
}

.normal {
    color: #28a745; /* verde */
}

.mensaje-urgente {
    color: #dc3545;
    font-weight: bold;
    font-size: 2rem;
}

    </style>
</head>
<body>

    <div class="tv-container">
        <header>
            <h2 class="fw-bold m-0"><i class="fa-solid"></i> PANTALLA DE TURNOS</h2>
        </header>

        <div class="main-content">
            <div class="sidebar-espera">
                <h3 class="text-center fw-bold mb-4"
                 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px;">EN ESPERA</h3>
                <ul id="listaEspera">
                    </ul>
            </div>

            <div class="current-turn-area">
                <span class="label-grande ">TURNO</span>
                <div id="turnoPrincipal">---</div>
                <div id="mensajePaciente" style="font-size: 2rem; color: #888;"></div>
            </div>
        </div>
    </div>

    <script>

function actualizarPantalla() {
    const turnosRaw = localStorage.getItem("turnos");
    let turnos = turnosRaw ? JSON.parse(turnosRaw) : [];

    const principal = document.getElementById("turnoPrincipal");
    const listaEspera = document.getElementById("listaEspera");

    // OBTENER TURNO ACTUAL
    let actualRaw = localStorage.getItem("turnoActual");
    let actual = actualRaw ? JSON.parse(actualRaw) : null;

    // ORDENAR LISTA DE ESPERA
    turnos.sort((a, b) => {
        if (a.prioridad === "Urgente" && b.prioridad === "Normal") return -1;
        if (a.prioridad === "Normal" && b.prioridad === "Urgente") return 1;
        return 0;
    });

    // MOSTRAR TURNO ACTUAL
    if (actual) {

    principal.innerHTML = actual.turno;

    // SI ES URGENTE
    if (actual.prioridad === "Urgente") {

    principal.classList.remove("normal");
principal.classList.add("urgente");

        

        // MOSTRAR CONSULTORIO + ALERTA
        mostrarConsultorio(actual, true);

    } else {

        principal.classList.remove("urgente");
        principal.classList.add("normal");
        window.sonando = false; // reset para próximos urgentes

        mostrarConsultorio(actual, false);
    }

} else {

    //  adaptado a URGENTE 
    principal.innerHTML = "---";
    principal.style.color = "";
    principal.classList.remove("urgente");
principal.classList.remove("normal");
    document.getElementById("mensajePaciente").innerHTML = "";
}



    //LISTA DE ESPERA
    listaEspera.innerHTML = "";

    let proximos = turnos.slice(0, 5); //AQUÍ CAMBIA

    if (proximos.length > 0) {
        proximos.forEach(t => {
            let li = document.createElement("li");

            li.textContent = t.turno;

            if (t.prioridad === "Urgente") {
                li.style.backgroundColor = "#dc3545";
            }

            listaEspera.appendChild(li);
        });
    } else {
        listaEspera.innerHTML = "<p class='text-center text-muted'>No hay más turnos en espera</p>";
    }
}


         

        setInterval(actualizarPantalla, 2000);
        window.onload = actualizarPantalla;


        // NUEVA FUNCIÓN URGEENTE
function mostrarConsultorio(turnoObj, esUrgente = false){
    let consultorio = "Sin asignar";

    if(turnoObj.especialidad === "Pediatría"){
        consultorio = 1;
    } else if(turnoObj.especialidad === "Médico General"){
        consultorio = 2;
    } else if(turnoObj.especialidad === "Nutrición"){
        consultorio = 3;
    }

    let numero = turnoObj.turno.match(/\d+/);
    numero = numero ? numero[0] : "00";

    let turnoFormateado = "P-" + String(numero).padStart(2, '0');

    document.getElementById("turnoPrincipal").innerHTML = turnoFormateado;

    if(esUrgente){
        document.getElementById("mensajePaciente").innerHTML =
            "🚨 PACIENTE URGENTE 🚨<br>" +
"<span class='mensaje-urgente'>Diríjase inmediatamente al consultorio " + consultorio + "</span>";
    } else {
        document.getElementById("mensajePaciente").innerHTML = 
            "Diríjase al consultorio " + consultorio +
            "<br><span style='font-size:1.5rem; color:#dc3545; font-weight:bold;'>Por favor, espere su llamado</span>";
    }
}
    </script>
</body>
</html>