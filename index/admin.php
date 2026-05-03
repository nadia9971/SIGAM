<?php
include 'conexion.php';
// Consultamos la tabla 'turnos' y ordenamos por 'id' - FUNCIONALIDAD INTACTA
$sql = "SELECT * FROM turnos ORDER BY id DESC"; 
$resultado_historial = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.I.G.A.M. - Panel Administrador</title>

    <!-- ESTILO REPLICADO DE LA FOTO -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(150deg, #BBFCC6, #BBFCF9);
            min-height: 100vh;
            font-family: 'Courier New', Courier, monospace;
        }
        .admin-container {
            max-width: 800px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
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
        #resultado, #seccionTablaHistorial {
            background-color: #f8f9fa;
            border-left: 5px solid #8a91f3;
            padding: 15px;
            border-radius: 5px;
            min-height: 100px;
        }
        .logout-link {
            color: #fe0505;
            text-decoration: none;
            font-weight: bold;
        }
        .logout-link:hover {
            text-decoration: underline;
        }
        /* Estilo para la tabla de MySQL */
        .table-historial {
            font-size: 0.9rem;
            background-color: white;
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
            
            <!-- SECCIÓN 1: HISTORIAL DESDE BASE DE DATOS -->
            <div class="mb-4 text-center border-bottom pb-3">
                <h5 class="mb-3 fw-bold">Historial de turnos Atendidos (DB)</h5>
                <button class="btn btn-custom px-4" onclick="mostrarSeccionHistorial()">
                    Ver Historial
                </button>

                <!-- Tabla de MySQL que se muestra/oculta -->
                <div id="seccionTablaHistorial" class="mt-3 shadow-sm" style="display:none;">
                    <h6 class="fw-bold mb-3">REGISTROS OFICIALES (MySQL)</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-historial text-center">
                            <thead style="background-color: #8a91f3; color: white;">
                                <tr>
                                    <th>Turno</th>
                                    <th>Paciente</th>
                                    <th>Especialidad</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($resultado_historial && mysqli_num_rows($resultado_historial) > 0) {
                                    while($row = mysqli_fetch_assoc($resultado_historial)) { 
                                        echo "<tr>";
                                        echo "<td>" . $row['codigo_turno'] . "</td>"; 
                                        echo "<td>" . $row['paciente_curp'] . "</td>";
                                        echo "<td>" . $row['especialidad'] . "</td>";
                                        echo "<td>" . $row['fecha_cita'] . "</td>"; 
                                        
                                        $estado = $row['estado'];
                                        $color = ($estado == 'atendido') ? 'green' : 'orange';
                                        echo "<td><b style='color: $color;'>" . ucfirst($estado) . "</b></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No hay registros en la base de datos</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: CONSULTAR TURNO ESPECÍFICO -->
            <div class="mb-4 border-bottom pb-4">
                <h5 class="mb-3 text-center fw-bold">Consultar turno específico</h5>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="dato" class="form-control" placeholder="Nombre del paciente o número de turno">
                    <button class="btn btn-custom" onclick="consultarTurno()">Consultar</button>
                </div>
            </div>

            <!-- SECCIÓN 3: REPORTES -->
            <div class="mb-4 text-center">
                <h5 class="mb-3 fw-bold">Reportes</h5>
                <button class="btn btn-success px-4" onclick="generarReporte()">Generar Reporte General</button>
            </div>

            <!-- PANEL DE RESULTADOS (DISEÑO FOTO) -->
            <div class="mt-4">
                <h6 class="text-uppercase fw-bold text-muted small">Panel de Información:</h6>
                <div id="resultado" class="shadow-sm">SIN INFORMACIÓN</div>
            </div>
        </div>

        <div class="card-footer bg-white text-center py-3 border-0">
            <a href="index.php" class="logout-link">Cerrar Sesión</a>
        </div>
    </div>
</div>

<script>
// FUNCIONALIDAD MANTENIDA AL 100%
function mostrarSeccionHistorial() {
    let seccion = document.getElementById("seccionTablaHistorial");
    if (seccion.style.display === "none") {
        seccion.style.display = "block";
        seccion.scrollIntoView({ behavior: 'smooth' });
    } else {
        seccion.style.display = "none";
    }
}

function consultarTurno(){
    let dato = document.getElementById("dato").value;
    if(!dato) { alert("Ingresa un dato para buscar"); return; }
    
    let historial = JSON.parse(localStorage.getItem("historial")) || [];
    let texto = "<strong>RESULTADO DE BÚSQUEDA</strong> <br><br>";
    let encontrado = false;

    historial.forEach(t => {
        if(t.toLowerCase().includes(dato.toLowerCase())){
            texto += "<div class='p-1 border-bottom'>" + t + "</div>";
            encontrado = true;
        }
    });

    if(!encontrado) texto = "<span class='text-danger fw-bold'>NO SE ENCONTRÓ NINGUNA COINCIDENCIA</span>";
    document.getElementById("resultado").innerHTML = texto;
}

function generarReporte(){
    let historial = JSON.parse(localStorage.getItem("historial")) || [];
    let total = historial.length;
    let atendidos = 0, pediatria = 0, general = 0, nutricion = 0;

    historial.forEach(t => {
        if(t.includes("ATENDIDO")) atendidos++;
        if(t.includes("Pediatría")) pediatria++;
        if(t.includes("Médico General")) general++;
        if(t.includes("Nutrición")) nutricion++;
    });

    document.getElementById("resultado").innerHTML =
        "<h5 class='text-primary fw-bold'>REPORTE</h5><hr>" +
        "<strong>TOTAL REGISTROS:</strong> " + total + "<br>" +
        "<strong>ATENDIDOS TOTAL:</strong> " + atendidos + "<br><br>" +
        "<h6 class='fw-bold text-success'>ESPECIALIDAD:</h6>"+
        "<strong>• PEDIATRÍA: </strong>" + pediatria + "<br>" +
        "<strong>• MÉDICO GENERAL: </strong>" + general + "<br>" +
        "<strong>• NUTRICIÓN: </strong>" + nutricion;
}

function verificarAlertas(){
    let turnos = JSON.parse(localStorage.getItem("turnos")) || [];
    if(turnos.length > 5) alert("⚠️ ATENCIÓN: Hay más de 5 pacientes en espera.");
}

window.onload = verificarAlertas;
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>