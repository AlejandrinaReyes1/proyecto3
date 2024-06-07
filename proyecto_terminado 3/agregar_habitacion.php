<?php
// Aquí va el código de conexión a la base de datos y cualquier otra lógica necesaria
// Datos de conexión
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

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $numero_habitacion = $_POST["numero_habitacion"];
    $cantidad = $_POST["cantidad"];
    $precio = $_POST["precio"];
    $tipo_habitacion = $_POST["tipo_habitacion"];
    $disponible = isset($_POST["disponible"]) ? 1 : 0; // Convertir el valor de disponible a entero

    // Preparar la consulta SQL para insertar una nueva habitación
    $sql = "INSERT INTO habitaciones (numero_habitacion, cantidad, precio, tipo_habitacion, disponible) 
            VALUES ('$numero_habitacion', '$cantidad', '$precio', '$tipo_habitacion', '$disponible')";

    // Ejecutar la consulta
    if (mysqli_query($conexion, $sql)) {
        // Redireccionar a admin_habitaciones.php después de agregar la habitación
        header("Location: admin_habitaciones.php");
        exit;
    } else {
        echo "Error al agregar la habitación: " . mysqli_error($conexion);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Habitación</title>
    <!-- Agrega aquí tus estilos CSS -->
</head>
<body>
    <h1>Agregar Nueva Habitación</h1>

    <!-- Formulario para agregar una nueva habitación -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="numero_habitacion">Número de Habitación:</label>
        <input type="text" id="numero_habitacion" name="numero_habitacion" required><br><br>
        
        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" required><br><br>
        
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" required><br><br>
        
        <label for="tipo_habitacion">Tipo de Habitación:</label>
        <input type="text" id="tipo_habitacion" name="tipo_habitacion" required><br><br>
        
        <label for="disponible">Disponible:</label>
        <input type="checkbox" id="disponible" name="disponible" value="1"><br><br>
        
        <input type="submit" value="Agregar Habitación">
    </form>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
