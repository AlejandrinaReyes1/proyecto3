<?php
require('./fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Tiket de Reserva', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15); // Posición: a 1,5 cm del final
        $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

        $this->SetY(-15); // Posición: a 1,5 cm del final
        $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
        $hoy = date('d/m/Y');
        $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
    }
}

$pdf = new PDF();
$pdf->AddPage(); /* aquí entran dos parámetros (horientazion, tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la página / y total de páginas
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163); //colorBorde

// Establecer la conexión a la base de datos
$host = "localhost";
$usuario = "root"; // Usuario predeterminado de XAMPP
$contrasena = ""; // Contraseña predeterminada de XAMPP
$base_datos = "reservas_hotel"; // Nombre de la base de datos

// Crear conexión
$conexion = mysqli_connect($host, $usuario, $contrasena, $base_datos);

// Verificar la conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta SQL para obtener el último cliente registrado
$query = "SELECT * FROM clientes ORDER BY id DESC LIMIT 1";
$resultado = mysqli_query($conexion, $query);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $cliente = mysqli_fetch_assoc($resultado);

    // Información del cliente
    $nombre = $cliente['nombre'];
    $fecha_llegada = $cliente['fecha_llegada'];
    $fecha_salida = $cliente['fecha_salida'];

    // Consulta SQL para obtener la habitación del cliente
    $habitacion_query = "SELECT * FROM habitaciones WHERE numero_habitacion = {$cliente['numero_habitacion']}";
    $habitacion_resultado = mysqli_query($conexion, $habitacion_query);

    if ($habitacion_resultado && mysqli_num_rows($habitacion_resultado) > 0) {
        $habitacion = mysqli_fetch_assoc($habitacion_resultado);

        // Información de la habitación
        $numero_habitacion = $habitacion['numero_habitacion'];
        $precio = $habitacion['precio'];
        $tipo_habitacion = $habitacion['tipo_habitacion'];

        // Mostrar la información en el PDF
        $pdf->Cell(40, 10, utf8_decode('Nombre: ') . $nombre, 0, 1);
        $pdf->Cell(40, 10, utf8_decode('Fecha de llegada: ') . $fecha_llegada, 0, 1);
        $pdf->Cell(40, 10, utf8_decode('Fecha de salida: ') . $fecha_salida, 0, 1);
        $pdf->Cell(40, 10, utf8_decode('Número de habitación: ') . $numero_habitacion, 0, 1);
        $pdf->Cell(40, 10, utf8_decode('Precio: ') . $precio, 0, 1);
        $pdf->Cell(40, 10, utf8_decode('Tipo de habitación: ') . $tipo_habitacion, 0, 1);
    } else {
        // Manejo de caso donde no se encuentran registros de habitaciones...
    }
} else {
    // Manejo de caso donde no se encuentran registros de clientes...
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);

$pdf->Output('Tiket_de_Reserva.pdf', 'I'); //nombreDescarga, Visor(I->visualizar - D->descargar)
