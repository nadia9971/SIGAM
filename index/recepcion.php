<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.I.G.A.M. - Recepción</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
    // ESTO VA ANTES DE </body> EN recepcion.php
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('imprimir')) {
        let nombre = urlParams.get('nombre');
        let esp = urlParams.get('esp');
        
        // Abrimos la ventanita (pop-up)
        let ventana = window.open('', '_blank', 'width=400,height=500');
        ventana.document.write(`
            <html>
            <head><title>Imprimir Ticket</title></head>
            <body style="font-family: monospace; text-align: center;">
                <h2>S.I.G.A.M.</h2>
                <hr>
                <p><strong>PACIENTE:</strong><br>${nombre}</p>
                <p><strong>ESPECIALIDAD:</strong><br>${esp}</p>
                <p>Fecha: ${new Date().toLocaleDateString()}</p>
                <script>
                    window.print(); // Abre el cuadro de imprimir
                    window.onafterprint = function() { window.close(); }; // Cierra la ventanita al terminar
                <\/script>
            </body>
            </html>
        `);
        ventana.document.close();
    }
</script>
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

        table {
    max-width: 100%;
    width: 100%;
    border-collapse: collapse;
    display: block;
    overflow-x: auto;
}

td, th {
    padding: 6px;
    white-space: nowrap;
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

        <form class="datos" action="guardar_paciente.php" method="POST">
    <div id="alertaIncompletos" class="alert alert-danger" style="display: none;">
        ⚠️ DATOS INCOMPLETOS
    </div>

    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre Completo" required>
    
    <div class="row">
        <div class="col-md-6">
            <input type="number" name="edad" id="edad" class="form-control" placeholder="Edad" required>
        </div>
        <div class="col-md-6">
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>
    </div>

    <input type="text" name="curp" id="curp" class="form-control" placeholder="CURP" required>

    <select name="sexo" id="sexo" class="form-select" required>
        <option value="">Seleccione Género</option>
        <option value="Mujer">Mujer</option>
        <option value="Hombre">Hombre</option>
    </select>

    <select name="especialidad" id="especialidad" class="form-select" required>
        <option value="">Seleccione Especialidad</option>
        <option value="Médico General">Médico General</option>
        <option value="Pediatría">Pediatría</option>
        <option value="Nutrición">Nutrición</option>
    </select>

    <button type="submit" class="btn btn-primary-custom mt-2">
        Generar y Guardar Turno
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

    const urlParams = new URLSearchParams(window.location.search);

    let turno = urlParams.get('turno');
    let nombre = urlParams.get('nombre');
    let esp = urlParams.get('esp');

    if(turno){
    imprimirTurno(turno, nombre, esp);

    // 🔥 GUARDAR EN LOCALSTORAGE
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];
    turnos.push(turno + " - " + nombre + " - " + esp);
    localStorage.setItem("turnos", JSON.stringify(turnos));
}
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
// Abrir ventana para imprimir
imprimirTurno(turno, nombre, esp);

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



function imprimirTurno(turno, nombre, esp, fecha){
    let ventana = window.open('', '_blank', 'width=400,height=600');

    ventana.document.write(`
        <html>
        <head>
            <title>Turno</title>
        </head>
        <body>
            <div class="ticket">
                <h2>S.I.G.A.M.</h2>
                <p><strong>Turno:</strong></p>
                <h1>${turno}</h1>
                <p><strong>Nombre:</strong> ${nombre}</p>
                <p><strong>Especialidad:</strong> ${esp}</p>
                <br>
            </div>

            <script>
                window.print();
            <\/script>
        </body>
        </html>
    `);

    ventana.document.close();
}
</script>

</body>
</html>