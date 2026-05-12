<?php
include 'conexion.php';

$busqueda_mysql = "";
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda_mysql = mysqli_real_escape_string($conexion, $_GET['buscar']);
    
    // Solo cambiamos el SELECT para que traiga los datos de la tabla TURNOS
    $sql = "SELECT t.paciente_curp AS curp, p.nombre_completo, t.especialidad, t.fecha_cita AS fecha_registro, t.estado 
            FROM turnos t
            INNER JOIN pacientes p ON t.paciente_curp = p.curp
            WHERE p.nombre_completo LIKE '%$busqueda_mysql%' 
            OR t.paciente_curp LIKE '%$busqueda_mysql%'
            ORDER BY t.fecha_cita DESC";
} else {
    // Aquí también, para que el historial general use la tabla TURNOS
    $sql = "SELECT t.paciente_curp AS curp, p.nombre_completo, t.especialidad, t.fecha_cita AS fecha_registro, t.estado 
            FROM turnos t
            INNER JOIN historial_pacientes p ON t.paciente_curp = p.curp
            ORDER BY t.fecha_cita DESC";
}
$resultado_historial = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.I.G.A.M. - Panel Administrador</title>

    <!-- estilo de el recuadro  -->
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
            
            <div class="mb-4 text-center border-bottom pb-3">
                <h5 class="mb-3 fw-bold">Historial de turnos </h5>
                <button class="btn btn-custom px-4" onclick="mostrarSeccionHistorial()">
                    Ver Historial
                </button>

                <div id="seccionTablaHistorial" class="mt-3 shadow-sm" style="display:none;">
                    <h6 class="fw-bold mb-3">REGISTROS </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-historial text-center">
                            <thead style="background-color: #8a91f3; color: white;">
                                <tr>
                                    <th>CURP</th>
                                    <th>Paciente</th>
                                    <th>Especialidad</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>

<tbody>
    
<tbody>
<tbody>
<?php 
if ($resultado_historial && mysqli_num_rows($resultado_historial) > 0) {
    while($row = mysqli_fetch_assoc($resultado_historial)) { 
        
        // --- LA SOLUCIÓN ESTÁ AQUÍ ---
        // trim() quita espacios ocultos y strtolower() pasa "Atendido" a "atendido"
        $estado_db = strtolower(trim($row['estado']));
        
        // Ahora comparamos siempre contra minúsculas, así no hay error
        if ($estado_db == 'atendido') {
            $texto_estado = "Atendido";
            $color = "green";
            // Si el estado es atendido, intentamos mostrar la fecha real
            $fecha_mostrar = ($row['fecha_registro'] != "0000-00-00 00:00:00") ? date("d/m/Y", strtotime($row['fecha_registro'])) : "---";
        } else {
            $texto_estado = "No Atendido";
            $color = "red";
            $fecha_mostrar = "---";
        }

        echo "<tr>";
        echo "<td>" . $row['curp'] . "</td>"; 
        echo "<td>" . $row['nombre_completo'] . "</td>";
        echo "<td>" . $row['especialidad'] . "</td>";
        echo "<td>" . $fecha_mostrar . "</td>"; 
        echo "<td><b class='estado-col' style='color: $color;'>" . $texto_estado . "</b></td>";
        echo "</tr>";
    }
}
?>
</tbody>
  
</tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: CONSULTAR TURNO ESPECÍFICO -->
<div class="mb-4 border-bottom pb-4">
    <h5 class="mb-3 text-center fw-bold">Consultar turno específico</h5>
    <form action="admin.php" method="GET">
        <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" name="buscar" class="form-control" placeholder="Nombre del paciente o número de turno" value="<?php echo $busqueda_mysql; ?>">
            <button class="btn btn-custom" type="submit">Consultar</button>
        </div>
    </form>
</div>

            <!-- reportes  -->
            <div class="mb-4 text-center">
                <h5 class="mb-3 fw-bold">Reportes</h5>
                <button class="btn btn-success px-4" onclick="generarReporte()">Reporte General</button>
            </div>

<!-- panel de el resultado  -->
<div class="mt-4">
    <h6 class="text-uppercase fw-bold text-muted small">Panel de Información:</h6>
    <div id="resultado" class="shadow-sm">
        
        <?php 
        if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
            if (mysqli_num_rows($resultado_historial) > 0) {
                // Regresamos el puntero al inicio para leer los datos encontrados
                mysqli_data_seek($resultado_historial, 0); 
                
                echo "<div class='p-2'>";
                echo "<span class='text-success fw-bold d-block mb-2'>✅ COINCIDENCIA(S) ENCONTRADA(S):</span>";
                
                while($datos = mysqli_fetch_assoc($resultado_historial)) {
                    echo "<div class='mb-3 p-3 border-start border-4 border-primary bg-white shadow-sm' style='border-radius: 8px;'>";
                    echo "<p class='mb-1'><strong>Paciente:</strong> " . $datos['nombre_completo'] . "</p>";
                    echo "<p class='mb-1'><strong>CURP:</strong> <span class='badge bg-primary' style='font-size: 1rem;'>"
                     . $datos['curp'] . "</span></p>";
                    echo "<p class='mb-0 text-muted'><strong>Especialidad:</strong> " . $datos['especialidad'] . "</p>";
                    echo "</div>";
                }
                echo "</div>";

                // Esto abre la tabla de arriba automáticamente
                echo "<script>document.addEventListener('DOMContentLoaded', mostrarSeccionHistorial);</script>";
            } else {
                echo "<div class='p-3 text-danger fw-bold'>❌ NO SE ENCONTRÓ NINGUNA COINCIDENCIA</div>";
            }
        } else {
            echo "<div class='p-3 text-muted'>SIN INFORMACIÓN</div>";
        }
        ?>
        <!-- === FIN DEL BLOQUE === -->

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
    mostrarSeccionHistorial(); 
    let filas = document.querySelectorAll("#seccionTablaHistorial tbody tr");
    
    let total = 0;
    let atendidos = 0;
    let pediatria = 0;
    let general = 0;
    let nutricion = 0;

    // Si la tabla dice "No hay registros", detenemos el proceso
    if(filas.length === 1 && filas[0].innerText.includes("No hay registros")) {
        alert("No hay datos para generar el reporte");
        return;
    }

    filas.forEach(fila => {
        let celdas = fila.getElementsByTagName("td");
        if(celdas.length > 0) {
            total++;
            
            // Leemos la especialidad (celda 2) y el estado (celda 4)
            let especialidad = celdas[2].innerText.trim();
            
            // TRIM quita espacios y comparamos que sea EXACTAMENTE "atendido"
            let estado = celdas[4].innerText.trim().toLowerCase();

            // AQUÍ ESTÁ EL CAMBIO: Usamos === para que "No Atendido" no cuente como "Atendido"
            if (estado === "atendido") {
                atendidos++;
            }

if (especialidad === "Pediatría") pediatria++;
if (especialidad === "Médico General") general++;
if (especialidad === "Nutrición") nutricion++;
        }
    });

    // Inyectamos el resultado en el Panel de Información
    document.getElementById("resultado").innerHTML =
        "<h5 class='text-primary fw-bold'>REPORTE GENERADO DESDE BD</h5><hr>" +
        "<strong>TOTAL REGISTROS:</strong> " + total + " pacientes<br>" +
        "<strong>ATENDIDOS TOTAL:</strong> " + atendidos + " pacientes<br><br>" +
        "<h6 class='fw-bold text-success'>POR ESPECIALIDAD:</h6>"+
        "<strong>• PEDIATRÍA: </strong>" + pediatria + " pacientes<br>" +
        "<strong>• MÉDICO GENERAL: </strong>" + general + " pacientes<br>" +
        "<strong>• NUTRICIÓN: </strong>" + nutricion + " pacientes";
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