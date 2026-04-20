<?php
// Mantengo tu sección de validación de sesión intacta por si decides activarla
// session_start();
// if(!isset($_SESSION['admin'])){
//     header("Location: login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.I.G.A.M. - Panel Administrador</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(150deg, #BBFCC6, #BBFCF9);
            min-height: 100vh;
            font-family: 'Courier New', Courier, monospace;
        } /*pagina general*/

        .admin-container {
            max-width: 800px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }/*degradado*/
        .card-header {
            background-color: #8a91f3;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            text-align: center;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #8a91f3;
            color: white;
            font-weight: bold;
           
        }
        .btn-custom:hover {
            background-color: #767cd8;
            color: white;
            
        }
        #resultado {
            background-color: #f8f9fa;
            border-left: 5px solid #8a91f3;
            padding: 15px;
            border-radius: 5px;
            min-height: 100px;
            white-space: pre-wrap; /*saltos de línea */
        }
        .logout-link {
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

<div class="container admin-container">
    <div class="card">
        <div class="card-header py-3">
            <h2 class="mb-0">PANEL ADMINISTRADOR</h2>
        </div>
        
        <div class="card-body p-4">
            
            <div class="mb-4 text-center border-bottom pb-3">
                <h5 class="mb-3 fw-bold">Ver historial de turnos</h5>
                <button class="btn btn-custom px-4" onclick="verHistorial()">
                    <i class="fa-solid "></i>Ver Historial
                </button>
            </div>

            <div class="mb-4 border-bottom pb-4">
                <h5 class="mb-3 text-center fw-bold">Consultar turno específico</h5>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="dato" class="form-control" placeholder="Nombre del paciente o número de turno">
                    <button class="btn btn-custom" onclick="consultarTurno()">Consultar</button>
                </div>
            </div>

            <div class="mb-4 text-center">
                <h5 class="mb-3 fw-bold">Reportes</h5>
                <button class="btn btn-success px-4" onclick="generarReporte()">Generar Reporte General
                </button>
            </div>

            <div class="mt-4">
                <h6 class="text-uppercase fw-bold text-muted small">Panel de Información:</h6>
                <div id="resultado" class="shadow-sm">SIN INFORMACIÓN</div>
            </div>
        </div>

        <div class="card-footer bg-white text-center py-3 border-0">
            <a href="index.php" class="logout-link">Cerrar Sesión
            </a>
        </div>
    </div>
</div>

<script>

// VE HISTORIAL REAL
function verHistorial(){
    let historial = JSON.parse(localStorage.getItem("historial")) || [];

    if(historial.length == 0){
        document.getElementById("resultado").innerHTML = "<span class='text-danger fw-bold'>NO HAY REGISTROS</span>";
        return;
    }

    let texto = "<strong>HISTORIAL DE ATENCIÓN</strong> <br><br>";

    historial.forEach(t => {
        texto += "• " + t + "<br>";
    });

    document.getElementById("resultado").innerHTML = texto;
}


// CONSULTA HISTORIAL
function consultarTurno(){
    let dato = document.getElementById("dato").value;
    if(!dato) { alert("Ingresa un dato para buscar"); return; }
    
    let historial = JSON.parse(localStorage.getItem("historial")) || [];

    let texto = "<strong>RESULTADO DE BÚSQUEDA</strong> <br><br>";
    let encontrado = false;

    historial.forEach(t => {
        if(t.toLowerCase().includes(dato.toLowerCase())){ // Mejora: búsqueda insensible a mayúsculas
            texto += "<div class='p-1 border-bottom'>" + t + "</div>";
            encontrado = true;
        }
    });

    if(!encontrado){
        texto = "<span class='text-danger fw-bold'>NO SE ENCONTRÓ NINGUNA COINCIDENCIA</span>";
    }

    document.getElementById("resultado").innerHTML = texto;
}


// REPORTE 
function generarReporte(){
    let historial = JSON.parse(localStorage.getItem("HISTORIAL")) || [];

    let total = historial.length;
    let atendidos = 0;

    let pediatria = 0;
    let general = 0;
    let nutricion = 0;

    historial.forEach(t => {
        if(t.includes("PACIENTES ATENDIDOS") || t.includes("ATENDIDO")){
            atendidos++;
        }

        if(t.includes("Pediatría")){
            pediatria++;
        }

        if(t.includes("Médico General")){
            general++;
        }

        if(t.includes("Nutrición")){
            nutricion++;
        }
    });

    document.getElementById("resultado").innerHTML =
    "<h5 class='text-primary fw-bold'>REPORTE</h5>" +
    "<hr>" +
    "<strong>TOTAL REGISTROS:</strong> " + total + "<br>" +
    "<strong>ATENDIDOS TOTAL:</strong> " + atendidos + "<br><br>" +

    "<h6 class='fw-bold text-success'>ESPECIALIDAD:</h6>"+
    "<strong>• PEDIATRÍA: </strong>" + pediatria + " pacientes<br>" +
    "<strong>• MÉDICO GENERAL: </strong>" + general + " pacientes<br>" +
    "<strong>• NUTRICIÓN: </strong>" + nutricion + " pacientes";
}


function verificarAlertas(){
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];

    if(turnos.length > 5){
        alert("⚠️ ATENCIÓN: Hay más de 5 pacientes en espera.");
    }
}

window.onload = function(){
    verificarAlertas();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html> 