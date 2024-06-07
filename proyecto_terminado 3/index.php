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

// Si se envió el formulario de reserva
if (isset($_POST['submit'])) {
    // Recuperar datos del formulario
    $nombre = $_POST["nombre"];
    $fecha_llegada = $_POST["fecha-llegada"];
    $fecha_salida = $_POST["fecha-salida"];
    $adultos = $_POST["adultos"];
    $ninos = $_POST["ninos"];
    $plan = $_POST["plan"];
    $numero_habitacion = isset($_POST["numero_habitacion"]) ? $_POST["numero_habitacion"] : "";

    // Consultar si la habitación seleccionada está disponible
    $sql_disponibilidad = "SELECT disponible FROM habitaciones WHERE numero_habitacion = ?";
    $stmt = mysqli_prepare($conexion, $sql_disponibilidad);
    mysqli_stmt_bind_param($stmt, "i", $numero_habitacion);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $disponible);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($disponible == 0) {
        // Habitación no disponible, mostrar mensaje de error
        $error_message = "La habitación seleccionada ya está ocupada. Por favor, seleccione otra habitación.";
    } else {
        // Habitación disponible, redirigir al registro del cliente
        $_SESSION['nombre_cliente'] = $nombre;
        $_SESSION['fecha_llegada'] = $fecha_llegada;
        $_SESSION['fecha_salida'] = $fecha_salida;
        $_SESSION['adultos'] = $adultos;
        $_SESSION['ninos'] = $ninos;
        $_SESSION['plan'] = $plan;
        $_SESSION['numero_habitacion'] = $numero_habitacion;

        header("Location: registrar_cliente.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Hotel</title>
    <link rel="stylesheet" href="css/hotel.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.carousel').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true,
                autoplay: true,
                autoplaySpeed: 2000
            });
        });
    </script>

<!-- Validación del formulario -->
<script>
        function validarFormulario() {
            var nombre = document.getElementById("nombre").value;
            var fechaLlegada = document.getElementById("fecha-llegada").value;
            var fechaSalida = document.getElementById("fecha-salida").value;
            var adultos = document.getElementById("adultos").value;
            var ninos = document.getElementById("ninos").value;
            var numeroHabitacion = document.getElementById("numero-habitacion").value;
            var plan = document.getElementById("plan").value;

            var regexNombre = /^[a-zA-Z\s]+$/;
            var regexFecha = /^\d{4}-\d{2}-\d{2}$/;
            var regexNumero = /^[0-9]+$/;

            if (!nombre.match(regexNombre)) {
                alert("Por favor, ingrese un nombre válido (solo letras y espacios).");
                return false;
            }
            if (!fechaLlegada.match(regexFecha)) {
                alert("Por favor, ingrese una fecha de llegada válida.");
                return false;
            }
            if (!fechaSalida.match(regexFecha)) {
                alert("Por favor, ingrese una fecha de salida válida.");
                return false;
            }
            
            // Validar que la fecha de salida no sea antes de la fecha de llegada
            var fechaLlegadaDate = new Date(fechaLlegada);
            var fechaSalidaDate = new Date(fechaSalida);
            if (fechaSalidaDate < fechaLlegadaDate) {
                alert("La fecha de salida no puede ser antes de la fecha de llegada.");
                return false;
            }

            if (!adultos.match(regexNumero) || adultos < 1) {
                alert("Por favor, ingrese un número válido de adultos (al menos 1).");
                return false;
            }
            if (!ninos.match(regexNumero) || ninos < 0) {
                alert("Por favor, ingrese un número válido de niños (0 o más).");
                return false;
            }
            if (!numeroHabitacion.match(regexNumero)) {
                alert("Por favor, seleccione un número de habitación válido.");
                return false;
            }
            if (plan === "") {
                alert("Por favor, seleccione un plan.");
                return false;
            }
            return true;
        }
    </script>
</script>
    <!-- script libre 1 -->
    <script>
$(document).ready(function(){
    $(".change").on("click", function() {
        if($("body").hasClass("dark")) {
            $("body").removeClass("dark");
            $(".change").text("OFF");
        } else {
            $("body").addClass("dark");
            $(".change").text("ON");
        }
    });
});
</script>
    <!-- script libre 2 -->
    <script>
        $(document).ready(function(){
    alert("¡Bienvenido a nuestro sistema de reservas de hotel!");
});
    </script>
    <!-- script libre 3 -->
<script>
    $(document).ready(function(){
        var formModified = false;

        // Detectar cambios en el formulario
        $('#reservarForm').on('change', function() {
            formModified = true;
        });

        // Mostrar mensaje de confirmación al intentar cerrar la página con cambios no guardados
        $(window).on('beforeunload', function(){
            if (formModified) {
                return 'Tienes cambios sin guardar. ¿Estás seguro de que deseas salir?';
            }
        });

        // Resetear la variable formModified al enviar el formulario
        $('#reservarForm').on('submit', function() {
            formModified = false;
        });
    });
</script>



    <style>
        .carousel {
            width: 80%;
            margin: auto;
            margin-top: 20px;
        }
        .carousel img {
            width: 100%;
        }
        .container {
            display: flex;
            justify-content: space-between;
        }
        .formulario-reserva {
            flex: 1;
            margin-right: 20px;
        }
        .tabla-habitaciones {
            flex: 1;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
        }

        .change {
            cursor: pointer;
            border: 1px solid #555;
            border-radius: 40%;
            width: 20px;
            text-align: center;
            padding: 5px;
            margin-left: 8px;
        }

        body.dark {
            background-color: #121212;
            color: #e0e0e0;
        }

        body.dark .formulario-reserva,
        body.dark .tabla-habitaciones,
        body.dark header,
        body.dark footer {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }

        body.dark .form-group label,
        body.dark .form-group input, body.dark .form-group select {
            color: #e0e0e0;
        }

        body.dark .form-group input,
        body.dark .form-group select {
            background-color: #333;
            border: 1px solid #555;
        }

        body.dark .btn {
            background-color: #444;
            color: #e0e0e0;
        }

    </style>

</head>
<body>
    <header>
        <div class="container">
            <h1>Reserva de Hotel</h1>

            <!-- Botonsito de Dark Mode -->
            <div class="mode">
                Dark mode:             
                <span class="change">OFF</span>
        </div>
    </header>
    <div class="carousel">
        <div><img src="imagen/Desayuno2.jpg" alt="Desayuno"></div>
        <div><img src="imagen/TipoSencilla3.jpg" alt="Habitacion tipo Sencilla"></div>
        <div><img src="imagen/tipoDoble2.jpg" alt="Habitacion tipo Doble"></div>
        <div><img src="imagen/TipoSuit.jpg" alt="Habitacion tipo Suit"></div>
    </div>
    <main>
        <div class="container">
            <section class="formulario-reserva">
                <h2>Haz una Reserva</h2>
                <form id="reservarForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return validarFormulario()">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha-llegada">Fecha de Llegada:</label>
                        <input type="date" id="fecha-llegada" name="fecha-llegada" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha-salida">Fecha de Salida:</label>
                        <input type="date" id="fecha-salida" name="fecha-salida" required>
                    </div>
                    <div class="form-group">
                        <label for="adultos">Adultos:</label>
                        <input type="number" id="adultos" name="adultos" min="1" value="1">
                    </div>
                    <div class="form-group">
                        <label for="ninos">Niños:</label>
                        <input type="number" id="ninos" name="ninos" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label for="plan">Selecciona Plan:</label>
                        <select id="plan" name="plan">
                            <option value="solo-hospedaje">Solo Hospedaje</option>
                            <option value="hospedaje-desayuno">Hospedaje y Desayuno</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="numero-habitacion">Número de Habitación:</label>
                        <select id="numero-habitacion" name="numero_habitacion">
                            <?php
                            // Consulta SQL para obtener las habitaciones disponibles
                            $sql_habitaciones = "SELECT numero_habitacion FROM habitaciones WHERE disponible = 1";
                            $resultado_habitaciones = mysqli_query($conexion, $sql_habitaciones);
                            if ($resultado_habitaciones && mysqli_num_rows($resultado_habitaciones) > 0) {
                                while ($fila_habitacion = mysqli_fetch_assoc($resultado_habitaciones)) {
                                    echo "<option value='" . $fila_habitacion['numero_habitacion'] . "'>" . $fila_habitacion['numero_habitacion'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No hay habitaciones disponibles</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="submit">Reservar Ahora</button>
                    <a href="logout.php">Cerrar Sesión</a>
                    <?php if (isset($error_message)) : ?>
                        <p><?php echo $error_message; ?></p>
                    <?php endif; ?>
                </form>
            </section>
<section class="tabla-habitaciones">
<h2>Habitaciones Disponibles</h2>
<table>
<thead>
<tr>
<th>Número de Habitación</th>
<th>Cantidad</th>
<th>Precio</th>
<th>Tipo</th>
<th>Disponible</th>
</tr>
</thead>
<tbody>
<!-- Las filas de la tabla se generarán dinámicamente con PHP -->
<?php
// Consulta SQL para obtener las habitaciones disponibles
$sql = "SELECT numero_habitacion, cantidad, precio, tipo_habitacion, disponible FROM habitaciones";
$resultado = mysqli_query($conexion, $sql);
if ($resultado && mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        echo "<tr>";
        echo "<td>" . $fila['numero_habitacion'] . "</td>";
        echo "<td>" . $fila['cantidad'] . "</td>";
        echo "<td>" . $fila['precio'] . "</td>";
        echo "<td>" . $fila['tipo_habitacion'] . "</td>";
        echo "<td>" . ($fila['disponible'] ? 'Sí' : 'No') . "</td>";
        echo "</tr>";
    }
    } else {
     echo "<tr><td colspan='5'>No hay habitaciones disponibles en este momento.</td></tr>";
    }
?>
</tbody>
</table>
    <a href="admin_habitaciones.php" class="btn btn-primary">Administrar Habitaciones</a>
</section>
</div>
</main>
    <footer>
        <div class="container">
            <p>&copy; 2024 Reserva de Hotel. Todos los derechos reservados para Alejandro y Rubi.</p>
        </div>
    </footer>
</body>
</html>


<?php
// Cerramos la conexión al final del archivo
mysqli_close($conexion);
?>
