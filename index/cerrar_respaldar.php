<?php
include 'conexion.php';

// 1. Copiamos todos los datos de 'pacientes' a 'historial_pacientes'
$sql_respaldo = "INSERT INTO historial_pacientes SELECT * FROM pacientes";

if (mysqli_query($conexion, $sql_respaldo)) {
    // 2. Una vez respaldado
    $sql_limpiar = "DELETE FROM pacientes";
    mysqli_query($conexion, $sql_limpiar);

    // 3. Regresamos al inicio
    header("Location: index.php");
} else {
    echo "Error al respaldar los datos: " . mysqli_error($conexion);
}
?>