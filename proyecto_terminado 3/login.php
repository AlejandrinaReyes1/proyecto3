<?php
// Iniciamos la sesión al principio del archivo
session_start();

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

// Verificar si ya se ha iniciado sesión como administrador
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: admin_panel.php");
    exit;
}

// Verificar si ya se ha iniciado sesión como cliente
if (isset($_SESSION['cliente']) && $_SESSION['cliente'] === true) {
    header("Location: cliente_panel.php");
    exit;
}

// Variables para almacenar mensajes de error
$error_message_admin = $error_message_cliente = $success_message = '';

// Verificar si se envió el formulario de inicio de sesión de administrador
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_login'])) {
    $admin_username = $_POST["admin_username"];
    $admin_password = $_POST["admin_password"];
    
    // Verificar las credenciales de administrador
    if (($admin_username === "alejandro" && $admin_password === "alejandro") || 
        ($admin_username === "rubi" && $admin_password === "rubi")) {
        $_SESSION['admin'] = true;
        header("Location: admin_panel.php");
        exit;
    } else {
        $error_message_admin = "Usuario o contraseña incorrectos para el administrador";
    }
}

// Verificar si se envió el formulario de inicio de sesión de cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cliente_login'])) {
    $cliente_username = $_POST["cliente_username"];
    $cliente_password = $_POST["cliente_password"];

    // Consultar en la base de datos
    $sql = "SELECT * FROM usuario WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $cliente_username);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $cliente = $resultado->fetch_assoc();
        if (password_verify($cliente_password, $cliente['contrasena'])) {
            $_SESSION['cliente'] = true;
            header("Location: cliente_panel.php");
            exit;
        } else {
            $error_message_cliente = "Usuario o contraseña incorrectos para el cliente";
        }
    } else {
        $error_message_cliente = "Usuario o contraseña incorrectos para el cliente";
    }
}

// Verificar si se envió el formulario de registro de cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cliente_register'])) {
    $nuevo_usuario = $_POST["nuevo_usuario"];
    $nuevo_correo = $_POST["nuevo_correo"];
    $nuevo_contrasena = password_hash($_POST["nuevo_contrasena"], PASSWORD_DEFAULT);

    // Insertar nuevo cliente en la base de datos
    $sql = "INSERT INTO usuario (usuario, correo, contrasena) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nuevo_usuario, $nuevo_correo, $nuevo_contrasena);

    if ($stmt->execute()) {
        $success_message = "Registro exitoso. Ahora puedes iniciar sesión.";
    } else {
        $error_message_cliente = "Error al registrar el cliente. Intenta nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/inicioSesion.css">
    <title>Iniciar Sesión</title>
    <style>
        /*Estilos CSS aquí */
        .container {
            width: 100%;
            text-align: center;
            max-width: 500px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
        }
        input[type="submit"] {
            padding: 10px 20px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <!-- Sección para iniciar sesión como administrador -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h3>Iniciar Sesión - Administrador</h3>
            <?php if (!empty($error_message_admin)) { ?>
                <p class="error"><?php echo $error_message_admin; ?></p>
            <?php } ?>
            <label for="admin_username">Usuario:</label>
            <input type="text" id="admin_username" name="admin_username" required>
            <label for="admin_password">Contraseña:</label>
            <input type="password" id="admin_password" name="admin_password" required>
            <input type="submit" name="admin_login" value="Iniciar Sesión como Administrador">
        </form>

        <!-- Sección para iniciar sesión como cliente -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h3>Iniciar Sesión - Cliente</h3>
            <?php if (!empty($error_message_cliente)) { ?>
                <p class="error"><?php echo $error_message_cliente; ?></p>
            <?php } ?>
            <label for="cliente_username">Usuario:</label>
            <input type="text" id="cliente_username" name="cliente_username" required>
            <label for="cliente_password">Contraseña:</label>
            <input type="password" id="cliente_password" name="cliente_password" required>
            <input type="submit" name="cliente_login" value="Iniciar Sesión como Cliente">
        </form>

        <!-- Sección para registrar nuevos clientes -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h3>Registrarse - Cliente</h3>
            <?php if (!empty($success_message)) { ?>
                <p class="success"><?php echo $success_message; ?></p>
            <?php } ?>
            <?php if (!empty($error_message_cliente)) { ?>
                <p class="error"><?php echo $error_message_cliente; ?></p>
            <?php } ?>
            <label for="nuevo_usuario">Usuario:</label>
            <input type="text" id="nuevo_usuario" name="nuevo_usuario" required>
            <label for="nuevo_correo">Correo:</label>
            <input type="email" id="nuevo_correo" name="nuevo_correo" required>
            <label for="nuevo_contrasena">Contraseña:</label>
            <input type="password" id="nuevo_contrasena" name="nuevo_contrasena" required>
            <input type="submit" name="cliente_register" value="Registrarse">
        </form>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?>
