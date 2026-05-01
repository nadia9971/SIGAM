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
    // Detectar si venimos de guardar_paciente.php para imprimir
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('imprimir')) {
        let nombre = urlParams.get('nombre');
        let esp = urlParams.get('esp');
        // Si no pasas el turno por URL, podemos intentar recuperarlo o dejar el espacio
        let turnoImprimir = urlParams.get('turno') || "---"; 
        let fechaActual = new Date().toLocaleDateString('es-MX');

        let ventana = window.open("", "_blank", "width=400,height=500");
        
       ventana.document.write(`
    <html>
    <head>
        <style>
            body { 
                font-family: 'Courier New', Courier, monospace; 
                text-align: center; 
                padding: 10px;
                margin: 0;
            }
            .header { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
            .turno-grande { 
                font-size: 80px; 
                font-weight: bold; 
                margin: 10px 0; 
                border-top: 3px solid black; 
                border-bottom: 3px solid black;
                display: inline-block;
                padding: 0 20px;
            }
            .datos { 
                font-size: 20px; 
                margin: 8px 0; 
                text-transform: uppercase; 
            }
            .label { font-weight: bold; }
            .fecha { font-size: 16px; margin-top: 20px; font-style: italic; }
        </style>
    </head>
    <body>
        <div class="header">TURNO</div>
        <div class="turno-grande">${turno}</div>
        
        <div class="datos">
            <span class="label">NOMBRE:</span> ${nombre}
        </div>
        
        <div class="datos">
            <span class="label">ESPECIALIDAD:</span> ${esp}
        </div>
        
        <div class="fecha">
            <span class="label">FECHA:</span> ${fechaActual}
        </div>

        <script>
            // Un pequeño retraso para que el estilo cargue bien antes de imprimir
            setTimeout(function() {
                window.print();
                window.close();
            }, 500);
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
            color: #fe0505 !important;
            text-decoration: none ;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .logout-link:hover {
            color: #0526fe !important;
            text-decoration: underline ;
        }
        .ver-lista-link {
            color: #0010f7;
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


#mensajeTurno h3 {
    margin-top: 20px;
    font-size: 1.5rem; /* Lo hice un poco más grande para que se vea bien */
    padding: 10px;
    text-align: center;
    color: #28a745 !important; /* Verde fuerte */
    font-weight: bold;
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
        <option value="Otro">Otro</option>
    </select>

    <select name="especialidad" id="especialidad" class="form-select" required>
        <option value="">Seleccione Especialidad</option>
        <option value="Médico General">Médico General</option>
        <option value="Pediatría">Pediatría</option>
        <option value="Nutrición">Nutrición</option>
    </select>

    
    <select name="prioridad" id="prioridad" class="form-select" required>/* AQUI SE PONE LA PRIORIDAD  */
    <option value="Normal">Prioridad: Normal</option>
    <option value="Urgente">Prioridad: Urgente (Atención Inmediata)</option>
</select>


    <button type="button" class="btn btn-primary-custom mt-2" onclick="generarTurno()">
    Generar Turno</button>

    <div id="mensajeTurno"></div>
</form>


    </div>



  <hr>
    <div class="text-center">
        
        <a href="lista_turno.php" class="ver-lista-link mb-3">
            <i class="fa-solid"></i> LISTA DE ESPERA
        </a>

        <ul id="listaTurnos" class="list-unstyled"></ul>

        <div style="text-align: center; margin-top: 20px;">
                <a href="#" onclick="cerrarSesion()" class= "logout-link">Cerrar Sesión</a>
            </div>
        </div>
    </div>




<script>
function obtenerContador() {
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];

    if (turnos.length === 0) return 1;

    let ultimo = turnos[turnos.length - 1];
    let numero = parseInt(ultimo.split("-")[1]);

    return numero + 1;
}


function cerrarSesion(){
    if(confirm("¿Finalizar jornada? Los datos de hoy se enviarán al historial del Administrador.")){
        // Limpiamos los turnos del navegador (LocalStorage)
        localStorage.removeItem("turnos");
        localStorage.removeItem("contadorTurno");

        window.location.href = "cerrar_respaldar.php";
    }
}
        
   

function generarTurno(){
    let nombre = document.getElementById("nombre").value;
    let edad = document.getElementById("edad").value;
    let sexo = document.getElementById("sexo").value;
    let curp = document.getElementById("curp").value;
    let esp = document.getElementById("especialidad").value;
    let fech = document.getElementById("fecha").value;
    //AÑADIMOS PRIORIDAD
let prioridad = document.getElementById("prioridad").value;


    let alerta = document.getElementById("alertaIncompletos");
    let msg = document.getElementById("mensajeTurno");

    if(nombre=="" || edad=="" || sexo=="" || curp=="" || esp=="" || fech==""){
        alerta.style.display="block";
        msg.innerHTML="";
        setTimeout(() => alerta.style.display="none", 4000);
        return;
    }

    alerta.style.display="none";

    // Generar Turno
    let contador = obtenerContador();
let turno = "P-" + String(contador).padStart(2,'0');
    let fechaTicket = new Date().toLocaleDateString('es-MX');

    // Mostrar mensaje en pantalla
    msg.innerHTML = `<h3 class='fw-bold' style='color: #28a745;'>SU TURNO ES: ${turno}</h3>`;

    // Abrir Ventana YA INCLUYE LA PRIORIDAD
    let ventana = window.open("", "", "width=450,height=500");
    ventana.document.write(`
    
<div class="fila" style="color: ${prioridad === 'Urgente' ? 'red' : 'black'}"></div>
    <span class="label">PRIORIDAD:</span> ${prioridad}
        <html>
        <head>
            <style>
                body { font-family: 'Courier New', Courier, monospace; text-align: center; padding: 20px; }
                .titulo { font-size: 22px; font-weight: bold; margin-bottom: 10px; }
                .turno-grande { font-size: 80px; font-weight: bold; margin: 15px 0; border-top: 
                3px solid #000; border-bottom: 3px solid #000; display: inline-block; padding: 0 15px; }
               
                .label { font-weight: bold; }
                .fecha { font-size: 14px; padding-top: 10px; }
            </style>
        </head>
        <body>
            <div class="titulo">S.I.G.A.M.</div>
            <div style="font-size: 16px;"><span class="label">TURNO</div>
            <div class="turno-grande">${turno}</div>
            
            <div class="fila"><span class="label">NOMBRE:</span> ${nombre}</div>
            <div class="fila"><span class="label">ESPECIALIDAD:</span> ${esp}</div>

            <script>
                setTimeout(() => {
                    window.print();
                    window.close();
                }, 500);
            <\/script>
        </body>
        </html>
    `);
    ventana.document.close();

    // Guardar en LocalStorage para la lista
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];
    turnos.push(turno + " - " + nombre + " - " + esp);
    localStorage.setItem("turnos", JSON.stringify(turnos));

    

    //Envia a PHP para guardar en base de datos
    setTimeout(() => {
        document.querySelector(".datos").submit();
    }, 2000);
}


function cargarLista(){
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];
    let mensajeDiv = document.getElementById("mensajeTurno")
    turnos.forEach(t => {
        let partes = t.split(" - ");
        let turno = partes[0];
        let nombre = partes[1];
        let esp = partes[2];

        let item = document.createElement("li");

        item.innerHTML =
            "<span class='textoTurno'><strong>"+turno+
            "</strong> - "+nombre+" - "+esp+"</span> "+
            "<button onclick='modificarTurno(this)'>Modificar</button> "+
            "<button onclick='eliminarTurno(this)'>Eliminar</button>";

        listaHTML.appendChild(item);
    });

    // actualizar contador automáticamente para evitar duplicados al recargar
    if(turnos.length > 0){
        let ultimo = turnos[turnos.length - 1].split(" - ")[0];
        let num = parseInt(ultimo.split("-")[1]);
        contador = num + 1;
    }

}

</script>

</body>
</html>