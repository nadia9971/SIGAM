<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.I.G.A.M. - Recepción</title>
    
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
        .recepcion-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 700px;
            padding: 30px;
        }
        header h2 {
            text-align: center;
            border-bottom: 2px solid #8a91f3;
            padding-bottom: 10px;
            margin-bottom: 25px;
            color: #333;
            font-weight: bold;
        }
        .reg h3 {
            font-size: 1.2rem;
            color: #000000;
            margin-bottom: 20px;
        }
        .form-control, .form-select {
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .btn-primary-custom {
            background-color: #8a91f3;
            border: none;
            width: 100%;
            font-weight: bold;
            padding: 10px;
            transition: 0.3s;
            color: white;
        }
        .btn-primary-custom:hover {
            background-color: #767cd8;
        }
        .logout-link {
            color: #fe0505;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .logout-link:hover {
            text-decoration: underline;
        }
        .ver-lista-link {
            color: #8a91f3;
            text-decoration: none;
            font-weight: bold;
            font-size: 1rem;
            display: block;
            margin-bottom: 15px;
        }
        .ver-lista-link:hover {
            text-decoration: underline;
        }
        #mensajeTurno h3 {
            margin-top: 20px;
            font-size: 1.3rem;
            padding: 10px;
            text-align: center;
            color: green;
            border: none;
        }
    </style>
</head>

<body>

<div class="recepcion-container">
    <header>
        <h2>GENERAR TURNOS</h2>
    </header>

    <div class="reg">
        <h3 class='text fw-bold'>Registrar Paciente</h3>

        <form class="datos">
            <div id="alertaIncompletos" class="alert alert-danger" style="display: none;">
                ⚠️ DATOS INCOMPLETOS
            </div>

            <input type="text" id="nombre" class="form-control" placeholder="Nombre Completo">
            
            <div class="row">
                <div class="col-md-6">
                    <input type="number" id="edad" class="form-control" placeholder="Edad">
                </div>
                <div class="col-md-6">
                    <input type="date" id="fecha" class="form-control">
                </div>
            </div>

            <input type="text" id="curp" class="form-control" placeholder="CURP">

            <select id="sexo" class="form-select">
                <option value="">Seleccione Género</option>
                <option value="Mujer">Mujer</option>
                <option value="Hombre">Hombre</option>
            </select>

            <select id="especialidad" class="form-select">
                <option value="">Seleccione Especialidad</option>
                <option>Médico General</option>
                <option>Pediatría</option>
                <option>Nutrición</option>
            </select>

            <button type="button" onclick="generarTurno()" class="btn btn-primary-custom mt-2">
                Generar Turno
            </button>

            <div id="mensajeTurno"></div>
        </form>
    </div>

    <hr>
<div class="text-center mt-4">
     <a href="lista_turno.php" class="ver-lista-link">
     <i class="fa-solid "></i> GESTIONAR LISTA DE ESPERA </a>

    <a href="#" onclick="cerrarSesion()" class="logout-link"> Cerrar Sesión </a>
</div>

<script>
let contador = 1;

function cerrarSesion(){
    // Guardar datos antes de limpiar
    let turnos = localStorage.getItem("turnos");
    let historial = localStorage.getItem("historial");

    localStorage.clear();

    if(turnos){
        localStorage.setItem("turnos", turnos);
    }

    if(historial){
        localStorage.setItem("historial", historial);
    }

    contador = 1;
    window.location.href = "index.php";
}

window.onload = function(){
    cargarLista();
};

function generarTurno(){
    let nombre = document.getElementById("nombre").value;
    let edad = document.getElementById("edad").value;
    let sexo = document.getElementById("sexo").value;
    let curp = document.getElementById("curp").value;
    let esp = document.getElementById("especialidad").value;
    let fech = document.getElementById("fecha").value;

    let alerta = document.getElementById("alertaIncompletos");
    let msg = document.getElementById("mensajeTurno");

    if(nombre=="" || edad=="" || sexo=="" || curp=="" || esp=="" || fech==""){
        alerta.style.display="block";
        msg.innerHTML="";
        return;
    }

    alerta.style.display="none";

    let turno = "P-" + String(contador).padStart(2,'0');
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];
    turnos.push(turno + " - " + nombre + " - " + esp);
    localStorage.setItem("turnos", JSON.stringify(turnos));

    msg.innerHTML = "<h3 class='fw-bold'>SU TURNO ES: "+turno+"</h3>";

    contador++;
    cargarLista();
}

function cargarLista(){
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];

    if(turnos.length > 0){
        let ultimo = turnos[turnos.length - 1].split(" - ")[0];
        let num = parseInt(ultimo.split("-")[1]);
        contador = num + 1;
    }
}
</script>

</body>
</html>