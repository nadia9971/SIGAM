<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $edad   = $_POST['edad'];
    $fecha  = $_POST['fecha']; // Esta se guardará en fecha_nacimiento
    $curp   = $_POST['curp'];
    $sexo   = $_POST['sexo'];
    $esp    = $_POST['especialidad'];

    
$prioridad = $_POST['prioridad']; //mandada a llamar

// INSERT CON PRIORIDAD 
$sql = "INSERT INTO pacientes (curp, nombre_completo, edad, especialidad, prioridad, estado) 
        VALUES ('$curp', '$nombre', '$edad', '$especialidad', '$prioridad', 'Pendiente')";

   
if (mysqli_query($conexion, $sql)) {
    header("Location: recepcion.php?imprimir=1&nombre=" . urlencode($nombre) . "&esp=" . urlencode($esp));
    exit();
}
    } else {
        echo "Error: " . mysqli_error($conexion);
    }

?>