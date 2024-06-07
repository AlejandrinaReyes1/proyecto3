<?php
// Iniciamos la sesión al principio del archivo
session_start();

// Verificamos si existen los datos en la sesión
if (isset($_SESSION['nombre_cliente'], $_SESSION['fecha_llegada'], $_SESSION['fecha_salida'], $_SESSION['adultos'], $_SESSION['ninos'], $_SESSION['plan'], $_SESSION['numero_habitacion'])) {
    // Datos de conexión a la base de datos
    $host = "localhost";
    $usuario = "root"; // Usuario predeterminado de XAMPP
    $contrasena = ""; // Contraseña predeterminada de XAMPP
    $base_datos = "reservas_hotel"; // Nombre de la base de datos que creaste

    // Crear conexión
    $conexion = mysqli_connect($host, $usuario, $contrasena, $base_datos);

    // Verificar la conexión
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Recuperar los datos de la sesión
    $nombre = $_SESSION['nombre_cliente'];
    $fecha_llegada = $_SESSION['fecha_llegada'];
    $fecha_salida = $_SESSION['fecha_salida'];
    $adultos = $_SESSION['adultos'];
    $ninos = $_SESSION['ninos'];
    $plan = $_SESSION['plan'];
    $numero_habitacion = $_SESSION['numero_habitacion'];

    // Obtener el precio de la habitación seleccionada
    if (!empty($numero_habitacion)) {
        $query = "SELECT precio FROM habitaciones WHERE numero_habitacion = '$numero_habitacion'";
        $result = mysqli_query($conexion, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $precio_habitacion = $row['precio'];
        } else {
            $precio_habitacion = 0; // Precio por defecto si no se encuentra la habitación
        }
    } else {
        $precio_habitacion = 0; // Precio por defecto si no se selecciona una habitación
    }

    // Preparar la consulta SQL para insertar los datos en la tabla de clientes
    $sql = "INSERT INTO clientes (nombre, fecha_llegada, fecha_salida, adultos, ninos, plan, numero_habitacion) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sssiisi", $nombre, $fecha_llegada, $fecha_salida, $adultos, $ninos, $plan, $numero_habitacion);

    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Cliente registrado exitosamente
        mysqli_stmt_close($stmt);
    } else {
        // Error al registrar el cliente
        die("Error al registrar el cliente: " . mysqli_error($conexion));
    }

    // Cerrar la conexión
    mysqli_close($conexion);
} else {
    // Si no existen datos en la sesión, redirigir a reserva.php
    header("Location: reserva.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
    <link rel="stylesheet" href="css/registroExitoso.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Registro de Cliente</h1>
        </div>
    </header>
    <main>
        <div class="container">
            <section>
                <h2>Cliente Registrado Exitosamente</h2>
                <p>Nombre: <?php echo $nombre; ?></p>
                <p>Fecha de Llegada: <?php echo $fecha_llegada; ?></p>
                <p>Fecha de Salida: <?php echo $fecha_salida; ?></p>
                <p>Adultos: <?php echo $adultos; ?></p>
                <p>Niños: <?php echo $ninos; ?></p>
                <p>Plan: <?php echo $plan; ?></p>
                <p>Número de Habitación: <?php echo $numero_habitacion; ?></p>
                <p>Precio de la Habitación: $<?php echo $precio_habitacion; ?></p>
            </section>
            <section>
                <h2>Pago con PayPal</h2>
                <!-- Formulario de PayPal -->
                <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="sb-m43tqh31071564@business.example.com">
                    <input type="hidden" name="item_name" value="Reserva de Hotel">
                    <input type="hidden" name="amount" value="<?php echo $precio_habitacion; ?>"> <!-- Monto a pagar (dinámico según el precio de la habitación) -->
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" name="return" value="fpdf/fpdf/PruebaV.php"> <!-- Página a la que se redirige después de realizar el pago -->
                    <input type="hidden" name="cancel_return" value="index.php"> <!-- Página a la que se redirige si el usuario cancela el pago -->
                    <input type="submit" value="Pagar con PayPal">
                </form>
            </section>

            <!-- Botones para regresar al formulario de reserva y generar el ticket -->
                <a href="reserva.php">Regresar al formulario de reserva</a>
            <br>
        <div class="text-right mb-2">
            <a href="fpdf/fpdf/PruebaV.php" target="_blank" class="btn btn-success"><i class="fas fa-file-pdf"></i>Generar ticket</a>
        </div>
        </main>
        <footer>
            <div class="container">
                <p>&copy; 2024 Reserva de Hotel. Todos los derechos reservados para Alejandro y Rubi.</p>
            </div>
    </footer>
</body>
</html>
