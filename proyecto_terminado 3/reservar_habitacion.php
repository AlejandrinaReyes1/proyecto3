<?php
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

// Recuperar el número de habitación seleccionada desde el formulario
$habitacion = $_POST["habitacion"];

// Consulta SQL para obtener el ID de la habitación seleccionada
$sql_id_habitacion = "SELECT id FROM habitaciones WHERE numero_habitacion = '$habitacion'";

// Ejecutar la consulta para obtener el ID de la habitación
$resultado_id = mysqli_query($conexion, $sql_id_habitacion);

// Verificar si se encontró el ID de la habitación
if (mysqli_num_rows($resultado_id) > 0) {
    $fila = mysqli_fetch_assoc($resultado_id);
    $habitacion_id = $fila['id'];

    // Consulta SQL para registrar la reserva en la tabla correspondiente
    $sql_reserva = "INSERT INTO reservas (habitacion_id) VALUES ('$habitacion_id')";

    // Ejecutar la consulta para registrar la reserva
    if (mysqli_query($conexion, $sql_reserva)) {
        // Obtener el ID de la reserva recién insertada
        $reserva_id = mysqli_insert_id($conexion);

        // Consulta SQL para actualizar el número de habitación en el registro del cliente
        $sql_actualizar_cliente = "UPDATE clientes SET numero_habitacion = '$habitacion' WHERE id = (SELECT cliente_id FROM reservas WHERE id = '$reserva_id')";

        // Ejecutar la consulta para actualizar el número de habitación en el registro del cliente
        if (mysqli_query($conexion, $sql_actualizar_cliente)) {
            echo "La habitación número $habitacion ha sido reservada exitosamente.";
        } else {
            echo "Error al actualizar el número de habitación en el registro del cliente: " . mysqli_error($conexion);
        }
    } else {
        echo "Error al registrar la reserva: " . mysqli_error($conexion);
    }
} else {
    echo "No se encontró la habitación seleccionada.";
}

// Liberar resultado
mysqli_free_result($resultado_id);

// Cerrar conexión
mysqli_close($conexion);
?>

<!-- Botón para regresar al inicio -->
<a href="index.html">Regresar al Inicio</a>
