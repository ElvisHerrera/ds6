<?php
// Configuración de conexión a la base de datos
$host = "localhost";  // Cambia si es necesario
$user = "ds62025";       // Usuario predeterminado en XAMPP
$pass = "1234";           // Contraseña predeterminada en XAMPP
$dbname = "ds6";      // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recoger los datos del formulario
$cedula = $_POST['Cedula'];
$tomo = $_POST['tomo'];
$asiento = $_POST['asiento'];
$first_name = $_POST['first-name'];
$second_name = $_POST['second-name'];
$first_lastname = $_POST['first-lastname'];
$second_lastname = $_POST['second-lastname'];
$apellidocheck = isset($_POST['apellidocheck']) ? 1 : 0;  // Si el checkbox está marcado
$apellidoCasada = $_POST['apellidoCasada'];
$estadoCivil = $_POST['estadoCivil'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$tipo_sangre = $_POST['tipo_sangre'];
$nacionalidad = $_POST['nacionalidad'];
$celular = $_POST['Celular'];
$telefono = $_POST['telefono'];
$provincia = $_POST['provincia'];
$distrito = $_POST['distrito'];
$corregimiento = $_POST['corregimiento'];
$address = $_POST['address'];
$city = $_POST['city'];
$calle = $_POST['calle'];
$casa = $_POST['casa'];
$comunidad = $_POST['comunidad'];
$fechadecontratacion = $_POST['fechadecontratacion'];
$cargo = $_POST['cargo'];
$departamento = $_POST['departamento'];
$correo = $_POST['correo'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Cifrado de la contraseña
$estado = $_POST['estado'];

// Preparar y ejecutar la consulta de inserción
$sql = "INSERT INTO empleados (cedula, tomo, asiento, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, apellido_casada, estado_civil, fecha_nacimiento, tipo_sangre, nacionalidad, celular, telefono, provincia, distrito, corregimiento, direccion, ciudad, calle, casa, comunidad, fecha_contratacion, cargo, departamento, correo, contrasena, estado) 
VALUES ('$cedula', '$tomo', '$asiento', '$first_name', '$second_name', '$first_lastname', '$second_lastname', '$apellidoCasada', '$estadoCivil', '$fechaNacimiento', '$tipo_sangre', '$nacionalidad', '$celular', '$telefono', '$provincia', '$distrito', '$corregimiento', '$address', '$city', '$calle', '$casa', '$comunidad', '$fechadecontratacion', '$cargo', '$departamento', '$correo', '$contrasena', '$estado')";

// Ejecutar la consulta y verificar si se insertó correctamente
if ($conn->query($sql) === TRUE) {
    echo "Registro exitoso!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
