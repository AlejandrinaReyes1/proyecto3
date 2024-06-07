<?php
require_once "config.php";

// Verificar si se ha proporcionado el número de habitación a eliminar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['numero_habitacion'])) {
    // Obtener el número de habitación de la URL
    $numero_habitacion = $_GET['numero_habitacion'];

    // Preparar la consulta SQL para eliminar la habitación
    $sql = "DELETE FROM habitaciones WHERE numero_habitacion = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Vincular variables a la declaración preparada como parámetros
        $stmt->bind_param("i", $param_numero_habitacion);

        // Establecer los parámetros
        $param_numero_habitacion = $numero_habitacion;

        // Intentar ejecutar la declaración preparada
        if ($stmt->execute()) {
            // Redireccionar a la página de administración después de eliminar la habitación
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
    // Si no se proporciona un número de habitación en la URL, redireccionar a la página de error
    header("location: error.php");
    exit();
}
?>
