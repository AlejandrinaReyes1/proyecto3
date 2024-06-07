<?php
session_start();

// Verificar si se ha iniciado sesión como cliente
if (!isset($_SESSION['cliente']) || $_SESSION['cliente'] !== true) {
    // Si no se ha iniciado sesión como cliente, redirigir al formulario de inicio de sesión
    header("Location: login_cliente.php");
    exit;
}

// Aquí puedes agregar cualquier lógica adicional que necesites para la página del panel del cliente

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Cliente</title>
    <link rel="stylesheet" href="css/cliente_panel.css">
</head>
<body>
    <header>
        <h1>Panel del Cliente</h1>
    </header>
    <nav>
        <!-- Aquí puedes agregar enlaces a otras páginas relevantes para el cliente -->
        <a href="reserva.php">Hazer una rezerva</a>
        <a href="logout.php">Cerrar Sesión</a>
    </nav>
    <main>
        <!-- Aquí puedes agregar el contenido principal de la página del panel del cliente -->
        <p>Bienvenido al Panel del Cliente. Aquí puedes realizar acciones para hacer una reserva o regresar al inicio de sesion.</p>
    </main>
    <footer>
        <p>© <?php echo date("Y"); ?> Reserva de Hotel. Todos los derechos reservados de Alejandro y Rubi.</p>
    </footer>
</body>
</html>
