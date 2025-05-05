<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function conectarBD() {
    $host = 'nutrimath.fun'; 
    $usuario = 'u415548879_nutrimath';
    $contrasena = 'NutriMath_08devLady';
    $basedatos = 'u415548879_nutrimath_db';

    $conexion = new mysqli($host, $usuario, $contrasena, $basedatos);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    return $conexion;
}
?>
