<?php
function conectarBD() {
    $host = 'localhost';
    $usuario = 'root';
    $contrasena = '';
    $basedatos = 'nutrimath_db';

    $conn = new mysqli($host, $usuario, $contrasena, $basedatos);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    return $conn;
}
?>
