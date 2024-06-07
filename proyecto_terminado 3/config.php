<?php
// Configuración de la base de datos
define('DB_SERVER', 'localhost'); // Servidor de la base de datos
define('DB_USERNAME', 'root'); // Nombre de usuario de la base de datos
define('DB_PASSWORD', ''); // Contraseña de la base de datos
define('DB_NAME', 'reservas_hotel'); // Nombre de la base de datos

// Intentar establecer la conexión con la base de datos
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if($mysqli === false){
    die("ERROR: No se pudo conectar. " . $mysqli->connect_error);
}
?>
