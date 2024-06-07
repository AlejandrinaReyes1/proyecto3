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

// Variables para almacenar mensajes
$success_message = $error_message = '';

// Verificar si se envió el formulario para agregar una nueva habitación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_habitacion = $_POST["numero_habitacion"];
    $cantidad = $_POST["cantidad"];
    $precio = $_POST["precio"];
    $tipo_habitacion = $_POST["tipo_habitacion"];
    $disponible = isset($_POST["disponible"]) ? 1 : 0;

    // Insertar nueva habitación en la base de datos
    $sql = "INSERT INTO habitaciones (numero_habitacion, cantidad, precio, tipo_habitacion, disponible) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sidsi", $numero_habitacion, $cantidad, $precio, $tipo_habitacion, $disponible);

    if ($stmt->execute()) {
        $success_message = "Habitación agregada exitosamente.";
    } else {
        $error_message = "Error al agregar la habitación. Por favor, intenta nuevamente.";
    }

    $stmt->close();
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nueva Habitación</title>
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

        form {
            width: 100%;
            margin-top: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
        }

        input[type="checkbox"] {
            margin: 5px 10px 20px 0;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        .success {
            color: green;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Agregar Nueva Habitación</h2>
        <?php if (!empty($success_message)) { ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php } ?>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="numero_habitacion">Número de Habitación:</label>
            <input type="text" id="numero_habitacion" name="numero_habitacion" required>

            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" required>

            <label for="precio">Precio:</label>
            <input type="number" step="0.01" id="precio" name="precio" required>

            <label for="tipo_habitacion">Tipo de Habitación:</label>
            <select id="tipo_habitacion" name="tipo_habitacion" required>
                <option value="individual">Individual</option>
                <option value="doble">Doble</option>
                <option value="suite">Suite</option>
            </select>

            <label for="disponible">Disponible:</label>
            <input type="checkbox" id="disponible" name="disponible">

            <input type="submit" value="Agregar Habitación">
        </form>
        <a href="admin_habitaciones.php">Volver a la administración de habitaciones</a>
    </div>
</body>
</html>
