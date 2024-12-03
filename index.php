<?php
session_start();
include 'conexion.php';

$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// Verificar si el usuario está logueado
$usuario_logueado = isset($_SESSION['usuario_id']) ? true : false;

if (!empty($busqueda)) {
    $sql = "SELECT * FROM productos WHERE nombre LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%'";
} else {
    $sql = "SELECT * FROM productos";
}

$result = $conn->query($sql);

// Obtener las categorías
$sql_categorias = "SELECT * FROM categorias";
$result_categorias = $conn->query($sql_categorias);

// Obtener los productos para el formulario de pedidos
$sql_productos = "SELECT id, nombre FROM productos";
$result_productos = $conn->query($sql_productos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liverpulga</title>
    <style>
        /* Reset de estilos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        header {
            background: #2980b9;
            color: white;
            text-align: center;
            padding: 30px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 3rem;
            letter-spacing: 2px;
        }

        main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            color: #2c3e50;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        form input,
        form select,
        form textarea,
        form button {
            padding: 10px;
            margin: 10px auto;
            display: block;
            width: 80%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #2980b9;
        }

        .productos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .producto {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }

        .producto img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        footer {
            background: #2c3e50;
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-top: 50px;
        }

        footer p {
            font-size: 1rem;
        }
    </style>
</head>
<body>

    <header>
        <h1>Liverpulga</h1>
    </header>

    <main>
        <!-- Mostrar estado de sesión -->
        <div style="text-align: center; margin-bottom: 20px;">
            <?php if ($usuario_logueado): ?>
                <p>Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?> | <a href="logout.php">Cerrar sesión</a></p>
            <?php else: ?>
                <p><a href="login.php">Iniciar sesión</a> o <a href="registro.php">Registrarse</a></p>
            <?php endif; ?>
        </div>

        <form method="GET" action="index.php">
            <input type="text" name="busqueda" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit">Buscar</button>
        </form>

        <h2>Explora nuestros productos</h2>
        <section class="productos">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="producto">
                        <img src="<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>">
                        <h3><?php echo $row['nombre']; ?></h3>
                        <p><?php echo $row['descripcion']; ?></p>
                        <p class="precio">$<?php echo $row['precio']; ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #e74c3c;">No se encontraron productos para "<?php echo htmlspecialchars($busqueda); ?>"</p>
            <?php endif; ?>
        </section>

        <!-- Formulario para agregar pedidos -->
        <div class="agregar-form">
            <h2>Agregar Pedido</h2>
            <form action="agregar_pedido.php" method="POST">
                <select name="id_producto" required>
                    <option value="">Selecciona un producto</option>
                    <?php while ($producto = $result_productos->fetch_assoc()): ?>
                        <option value="<?php echo $producto['id']; ?>"><?php echo $producto['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="number" name="cantidad" placeholder="Cantidad" min="1" required>
                <input type="text" name="cliente" placeholder="Nombre del cliente" required>
                <button type="submit">Realizar Pedido</button>
            </form>
        </div>

        <!-- Formulario para agregar productos -->
        <div class="agregar-form">
            <h2>Agregar Producto</h2>
            <form action="agregar_producto.php" method="POST">
                <input type="text" name="nombre" placeholder="Nombre del producto" required>
                <textarea name="descripcion" placeholder="Descripción del producto" rows="3" required></textarea>
                <input type="number" step="0.01" name="precio" placeholder="Precio del producto" required>
                <input type="text" name="imagen" placeholder="URL de la imagen del producto" required>
                <button type="submit">Agregar Producto</button>
            </form>
        </div>

        <!-- Formulario para agregar categorías -->
        <div class="agregar-form">
            <h2>Agregar Categoría</h2>
            <form action="agregar_categoria.php" method="POST">
                <input type="text" name="nombre" placeholder="Nombre de la categoría" required>
                <textarea name="descripcion" placeholder="Descripción de la categoría" rows="3" required></textarea>
                <button type="submit">Agregar Categoría</button>
            </form>
        </div>

        <h2>Categorías Disponibles</h2>
        <section class="categorias">
            <?php if ($result_categorias->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $result_categorias->fetch_assoc()): ?>
                        <li><strong><?php echo $row['nombre']; ?>:</strong> <?php echo $row['descripcion']; ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No hay categorías disponibles.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Tienda de Ropa | Todos los derechos reservados</p>
    </footer>

</body>
</html>
