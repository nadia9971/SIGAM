<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $edad   = $_POST['edad'];
    $fecha  = $_POST['fecha']; // Esta se guardará en fecha_nacimiento
    $curp   = $_POST['curp'];
    $sexo   = $_POST['sexo'];
    $esp    = $_POST['especialidad'];

    // INSERT con los nombres exactos de tu imagen
    $sql = "INSERT INTO pacientes (curp, nombre_completo, edad, sexo, fecha_nacimiento, especialidad) 
            VALUES ('$curp', '$nombre', '$edad', '$sexo', '$fecha', '$esp')";

    // ... después de que mysqli_query sea exitoso:
if (mysqli_query($conexion, $sql)) {
    // Redirige a recepcion.php pasando los datos para que el JS los imprima
    header("Location: recepcion.php?imprimir=1&nombre=" . urlencode($nombre) . "&esp=" . urlencode($esp));
    exit();
}
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>