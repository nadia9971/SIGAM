<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recogemos las variables
    $nombre         = $_POST['nombre'];
    $edad           = $_POST['edad'];
    $fecha_nac      = $_POST['fecha']; 
    $curp           = $_POST['curp'];
    $sexo           = $_POST['sexo'];
    $especialidad   = $_POST['especialidad']; 
    $prioridad      = $_POST['prioridad'];

    // 2. Primera consulta: Guardar el perfil del paciente
    $sql = "INSERT INTO pacientes (curp, nombre_completo, edad, fecha_nacimiento, sexo, especialidad, prioridad, estado) 
            VALUES ('$curp', '$nombre', '$edad', '$fecha_nac', '$sexo', '$especialidad', '$prioridad', 'Pendiente')";

    // 3. Segunda consulta: Crear el turno automáticamente
    // Nota: Como configuramos DEFAULT en la BD, la fecha y el estado se crean solos.
    $sql_turno = "INSERT INTO turnos (paciente_curp, especialidad, estado) 
                  VALUES ('$curp', '$especialidad', 'No Atendido')";

    if (mysqli_query($conexion, $sql) && mysqli_query($conexion, $sql_turno)) {
        // Todo salió bien, redirigimos
        header("Location: recepcion.php?imprimir=1&nombre=" . urlencode($nombre) . "&esp=" . urlencode($especialidad));
        exit();
    } else {
        echo "Error al registrar: " . mysqli_error($conexion);
    }
}
?>