<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recogemos las variables correctamente
    $nombre       = $_POST['nombre'];
    $edad         = $_POST['edad'];
    $fecha_nac    = $_POST['fecha']; 
    $curp         = $_POST['curp'];
    $sexo         = $_POST['sexo'];
    $especialidad = $_POST['especialidad']; // Esta era la variable que faltaba
    $prioridad    = $_POST['prioridad'];

    // 2. INSERT corregido: incluimos todos los campos y usamos las variables correctas
    $sql = "INSERT INTO pacientes (curp, nombre_completo, edad, fecha_nacimiento, sexo, especialidad, prioridad, estado) 
            VALUES ('$curp', '$nombre', '$edad', '$fecha_nac', '$sexo', '$especialidad', '$prioridad', 'Pendiente')";

    if (mysqli_query($conexion, $sql)) {
        // Redirección exitosa enviando los datos para la impresión
        header("Location: recepcion.php?imprimir=1&nombre=" . urlencode($nombre) . "&esp=" . urlencode($especialidad));
        exit();
    } else {
        echo "Error en la base de datos: " . mysqli_error($conexion);
    }
}
?>