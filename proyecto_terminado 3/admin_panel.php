<?php
// Verificar si se ha iniciado sesión como administrador
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Si no se ha iniciado sesión, redirigir al formulario de inicio de sesión
    header("Location: login.php");
    exit;
}

// Aquí puedes agregar el código para mostrar el panel de administración
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        p {
            text-align: center;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bienvenido al Panel de Administración</h2>
        <p><a href="index.php">Ir al formulario de reserva</a></p>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
</body>
</html>
