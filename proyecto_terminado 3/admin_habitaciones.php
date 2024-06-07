<?php
session_start();

// Verificar si el usuario ha iniciado sesión como administrador
if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Datos de conexión a la base de datos
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "reservas_hotel";

// Crear conexión
$conexion = mysqli_connect($host, $usuario, $contrasena, $base_datos);

// Verificar la conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener la lista de habitaciones desde la base de datos
$query = "SELECT * FROM habitaciones";
$result = mysqli_query($conexion, $query);

// Verificar si se recibió una solicitud para editar una habitación específica
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['edit']) && isset($_GET['numero_habitacion'])) {
    $numero_habitacion = $_GET['numero_habitacion'];
    $query_habitacion = "SELECT * FROM habitaciones WHERE numero_habitacion = '$numero_habitacion'";
    $result_habitacion = mysqli_query($conexion, $query_habitacion);
    $habitacion = mysqli_fetch_assoc($result_habitacion);
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Habitaciones</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        .action-links {
            display: flex;
            justify-content: center;
        }

        .action-links a {
            margin-right: 10px;
        }

        .action-links a.delete {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Administración de Habitaciones</h2>
        <table>
            <tr>
                <th>Número de Habitación</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Tipo de Habitación</th>
                <th>Disponible</th>
                <th>Acciones</th>
            </tr>
            <?php
            // Mostrar las habitaciones en la tabla
            while ($fila = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $fila['numero_habitacion'] . "</td>";
                echo "<td>" . $fila['cantidad'] . "</td>";
                echo "<td>" . $fila['precio'] . "</td>";
                echo "<td>" . $fila['tipo_habitacion'] . "</td>";
                echo "<td>" . ($fila['disponible'] ? 'Sí' : 'No') . "</td>";
                echo "<td class='action-links'><a href='modificar_habitacion.php?numero_habitacion=" . $fila['numero_habitacion'] . "'>Editar</a>";
                echo "<a class='delete' href='eliminar_habitacion.php?numero_habitacion=" . $fila['numero_habitacion'] . "'>Eliminar</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
        <a href="index.php" class="btn btn-primary">Regresar al incio</a>
        <br>
        <a href="HabitacionNueva.php" class="btn btn-primary">Agregar una nueva Habitacion</a>
    </div>
</body>
</html>