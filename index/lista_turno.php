<?php
include 'conexion.php'; 

// --- LÓGICA PARA ACCIONES (ELIMINAR Y EDITAR) ---
if (isset($_GET['eliminar'])) {
    $curp_eliminar = $_GET['eliminar'];
    $sql_del = "DELETE FROM pacientes WHERE curp = '$curp_eliminar'";
    mysqli_query($conexion, $sql_del);
    header("Location: lista_turno.php"); // Recargar página
}

if (isset($_POST['editar_nombre'])) {
    $nuevo_nombre = $_POST['nuevo_nombre'];
    $curp_editar = $_POST['curp_editar'];
    $sql_upd = "UPDATE pacientes SET nombre_completo = '$nuevo_nombre' WHERE curp = '$curp_editar'";
    mysqli_query($conexion, $sql_upd);
    header("Location: lista_turno.php"); // Recargar página
}

// Consultar pacientes
// AGREGUE PRIORIDAD EN ESTE SELECT PARA QUE SEA DE LOS PRIMEROS EN SALIR:
$sql = "SELECT curp, nombre_completo, edad, especialidad, prioridad, estado FROM pacientes ORDER BY prioridad DESC, curp ASC";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>S.I.G.A.M. - Lista de Espera</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #e0fff0; font-family: 'Courier New', Courier, monospace; padding: 20px; }
        .container-lista { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .titulo-seccion { font-weight: bold; text-transform: uppercase; margin-bottom: 20px; text-align: center; border-bottom: 2px solid #8a91f3; padding-bottom: 10px; }
    </style>
</head>
<body>

<div class="container container-lista">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="titulo-seccion">LISTA DE ESPERA ACTUAL</h2>
        <a href="recepcion.php" style="color: #8a91f3; text-decoration: none; font-weight: bold;"> 
            <i class="fa-solid fa-arrow-left"></i> Volver al Registro</a>
    </div>

    <div class="table-responsive">
    <table class="table table-hover align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>Prioridad</th> <th>CURP</th>
                <th>Paciente</th>
                <th>Edad</th>
                <th>Especialidad</th>
                <th>Estado</th>    <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

            <?php 
            if (mysqli_num_rows($resultado) > 0) {
                while($row = mysqli_fetch_assoc($resultado)) { 
                    // Definimos el color según PRIORIDAD
                    $colorPrioridad = ($row['prioridad'] == 'Urgente') ? 'bg-danger' : 'bg-success';
            ?>
            <tr class="<?php echo ($row['prioridad'] == 'Urgente') ? 'table-danger' : ''; ?>">
                <td>
                    <span class="badge <?php echo $colorPrioridad; ?>">
                        <?php echo $row['prioridad']; ?>
                    </span>
                </td>
                <td class="fw-bold"><?php echo $row['curp']; ?></td>
                <td><?php echo $row['nombre_completo']; ?></td>
                <td><?php echo $row['edad']; ?></td>
                <td><?php echo $row['especialidad']; ?></td>
                <td>
                    <span class="badge bg-secondary"><?php echo $row['estado']; ?></span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" 
                            onclick="editarPaciente('<?php echo $row['curp']; ?>', '<?php echo $row['nombre_completo']; ?>')">
                        <i class="fa-solid fa-pencil"></i>
                    </button>

                    <a href="lista_turno.php?eliminar=<?php echo $row['curp']; ?>" 
                       class="btn btn-sm btn-outline-danger" 
                       onclick="return confirm('¿Seguro que desea eliminar este turno?')">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php 
                } 
                
            } else {
                echo "<tr><td colspan='7' class='text-muted'>No hay registros en la base de datos</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>



</div>

<form id="formEditar" method="POST" style="display:none;">
    <input type="hidden" name="curp_editar" id="curp_editar">
    <input type="hidden" name="nuevo_nombre" id="nuevo_nombre">
    <input type="hidden" name="editar_nombre" value="1">
</form>

<script>
function editarPaciente(curp, nombreActual) {
    let nuevoNombre = prompt("Editar Nombre del Paciente:", nombreActual);
    
    if (nuevoNombre !== null && nuevoNombre.trim() !== "") {
        document.getElementById('curp_editar').value = curp;
        document.getElementById('nuevo_nombre').value = nuevoNombre;
        document.getElementById('formEditar').submit();
    }
}
</script>

</body>
</html>