<?php
require_once "config.php";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los valores del formulario
    $numero_habitacion = $_POST["numero_habitacion"];
    $cantidad = $_POST["cantidad"];
    $precio = $_POST["precio"];
    $tipo_habitacion = $_POST["tipo_habitacion"];
    $disponible = isset($_POST["disponible"]) ? 1 : 0; // Convertir a 1 si está marcado, 0 si no

    // Preparar la consulta SQL para actualizar los datos de la habitación
    $sql = "UPDATE habitaciones SET cantidad = ?, precio = ?, tipo_habitacion = ?, disponible = ? WHERE numero_habitacion = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Vincular variables a la declaración preparada como parámetros
        $stmt->bind_param("iissi", $param_cantidad, $param_precio, $param_tipo_habitacion, $param_disponible, $param_numero_habitacion);

        // Establecer los parámetros
        $param_cantidad = $cantidad;
        $param_precio = $precio;
        $param_tipo_habitacion = $tipo_habitacion;
        $param_disponible = $disponible;
        $param_numero_habitacion = $numero_habitacion;

        // Intentar ejecutar la declaración preparada
        if ($stmt->execute()) {
            // Redireccionar a la página de administración después de guardar los cambios
            header("location: admin_habitaciones.php");
            exit();
        } else {
            echo "Error al ejecutar la consulta.";
        }

        // Cerrar la declaración
        $stmt->close();
    }

    // Cerrar la conexión
    $mysqli->close();
} else {
    // Recuperar el número de habitación de la URL
    if (isset($_GET["numero_habitacion"]) && !empty(trim($_GET["numero_habitacion"]))) {
        // Preparar la consulta SQL para seleccionar la habitación
        $sql = "SELECT * FROM habitaciones WHERE numero_habitacion = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Vincular variables a la declaración preparada como parámetros
            $stmt->bind_param("i", $param_numero_habitacion);

            // Establecer los parámetros
            $param_numero_habitacion = trim($_GET["numero_habitacion"]);

            // Ejecutar la declaración
            if ($stmt->execute()) {
                // Almacenar el resultado
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    // Obtener la fila de resultado como un array asociativo
                    $fila_habitacion = $result->fetch_assoc();
                } else {
                    // No se encontró ninguna habitación con el número proporcionado
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Error al ejecutar la consulta.";
            }

            // Cerrar la declaración
            $stmt->close();
        }
    } else {
        // Si no se proporciona un número de habitación en la URL, redireccionar a la página de error
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Habitación</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
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
            max-width: 400px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="checkbox"] {
            margin-top: 5px;
        }

        button[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modificar Habitación</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="numero_habitacion">Número de Habitación:</label>
                <input type="text" id="numero_habitacion" name="numero_habitacion" value="<?php echo $fila_habitacion["numero_habitacion"]; ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" value="<?php echo $fila_habitacion["cantidad"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" value="<?php echo $fila_habitacion["precio"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo_habitacion">Tipo de Habitación:</label>
                <input type="text" id="tipo_habitacion" name="tipo_habitacion" value="<?php echo $fila_habitacion["tipo_habitacion"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="disponible">Disponible:</label>
                <input type="checkbox" id="disponible" name="disponible" <?php if ($fila_habitacion["disponible"] == 1) { echo "checked"; } ?>>
            </div>
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>