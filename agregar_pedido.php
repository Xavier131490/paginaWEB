<?php
include 'conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);
    $cliente = $conn->real_escape_string($_POST['cliente']);

    // Insertar en la tabla pedidos
    $sql = "INSERT INTO pedidos (id_producto, cantidad, cliente) VALUES ('$id_producto', '$cantidad', '$cliente')";

    if ($conn->query($sql) === TRUE) {
        echo "Pedido realizado con éxito.";
    } else {
        echo "Error al realizar el pedido: " . $conn->error;
    }

    $conn->close(); // Cerrar la conexión
}
?>
