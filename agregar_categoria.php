<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    $sql = "INSERT INTO categorias (nombre, descripcion) VALUES ('$nombre', '$descripcion')";

    if ($conn->query($sql) === TRUE) {
        echo "Categoría agregada correctamente.";
    } else {
        echo "Error al agregar la categoría: " . $conn->error;
    }

    // Redirigir de vuelta a index.php
    header("Location: index.php");
    exit;
}
?>
