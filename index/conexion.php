<?php
$host = "127.0.0.1";
$port = 3306; 
$user = "root";
$pass = "root123"; 
$db   = "bd_citas";

$conexion = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conexion) {
    $conexion = mysqli_connect($host, $user, $pass, $db);
}

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");
?>