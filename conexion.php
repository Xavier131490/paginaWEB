<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "tienda_ropa";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database,3308);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
