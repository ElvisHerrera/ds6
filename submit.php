<?php
include 'conexion.php'; // Incluye la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $prefijo = $_POST['Cedula']; // Create $prefijo from the received 'Cedula'
    $tomo = $_POST['tomo'];
    $asiento = $_POST['asiento'];
    $cedula = $prefijo . '-' . $tomo . '-' . $asiento; // Concatenate $prefijo, $tomo, and $asiento with '-'
    $firstName = $_POST['first-name'];
    $secondName = $_POST['second-name'];
    $firstLastname = $_POST['first-lastname'];
    $secondLastname = $_POST['second-lastname'];
    $genero = $_POST['genero'];
    $apellidoCasada = isset($_POST['apellidocheck']) ? $_POST['apellidoCasada'] : null;
    $usa_ac = isset($_POST['apellidocheck']) ? 1 : 0; // 1 if checked, 0 if unchecked
    $estadoCivil = $_POST['estadoCivil'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $tipoSangre = $_POST['tipo_sangre'];
    $nacionalidad = $_POST['nacionalidad'];
    $celular = $_POST['Celular'];
    $telefono = $_POST['telefono'];
    $provincia = $_POST['provincia'];
    $distrito = $_POST['distrito'];
    $corregimiento = $_POST['corregimiento'];
    $calle = $_POST['calle'];
    $casa = $_POST['casa'];
    $comunidad = $_POST['comunidad'];
    $fechaContratacion = $_POST['fechadecontratacion'];
    $departamento = $_POST['departamento'];
    $cargo = $_POST['cargo'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $estado = $_POST['estado'];

    // Removed 'direccion' and 'ciudad' from the variables
    unset($_POST['direccion'], $_POST['ciudad']);

    // Insertar datos en la base de datos
    $query = "INSERT INTO empleados (prefijo, cedula, tomo, asiento, nombre1, nombre2, apellido1, apellido2, genero, apellidoc, usa_ac, estado_civil, f_nacimiento, tipo_sangre, nacionalidad, celular, telefono, provincia, distrito, corregimiento, calle, casa, comunidad, f_contra, departamento, cargo, correo, contraseña, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sssssssssssssssssssssssssssss', $prefijo, $cedula, $tomo, $asiento, $firstName, $secondName, $firstLastname, $secondLastname, $genero, $apellidoCasada, $usa_ac, $estadoCivil, $fechaNacimiento, $tipoSangre, $nacionalidad, $celular, $telefono, $provincia, $distrito, $corregimiento, $calle, $casa, $comunidad, $fechaContratacion, $departamento, $cargo, $correo, $contrasena, $estado);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registro exitoso.');
            window.location.href = 'dashboard.php';
        </script>";
    } else {
        echo "<script>
            alert('Error: " . $stmt->error . "');
            window.location.href = 'FormularioFinal.php';
        </script>";
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Método no permitido.";
}
?>