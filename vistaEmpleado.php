<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "ds62025";
$password = "1234";
$database = "ds6";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar que el acceso provenga de processLogin.php
if (!isset($_GET['username'])) {
    echo "<script>
        alert('Acceso no autorizado.');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$cedula = $_GET['username'];

// Consulta para obtener toda la información del empleado
$sql = "SELECT * FROM empleados WHERE cedula = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener los datos del empleado
    $empleado = $result->fetch_assoc();
} else {
    echo "<script>
        alert('No se encontró información para este usuario.');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Empleado</title>
    <link rel="stylesheet" href="perfilstyle.css">
</head>
<body>
    <div class="profile-container">
        <h1>Perfil del Empleado</h1>
        <table>
            <tr>
                <th>Cédula:</th>
                <td><?php echo htmlspecialchars($empleado['cedula']); ?></td>
            </tr>
            <tr>
                <th>Nombre Completo:</th>
                <td>
                    <?php 
                        echo htmlspecialchars($empleado['nombre1'] . " " . $empleado['nombre2'] . " " . 
                        $empleado['apellido1'] . " " . $empleado['apellido2']); 
                    ?>
                </td>
            </tr>
            <tr>
                <th>Género:</th>
                <td><?php echo $empleado['genero'] == 1 ? 'Masculino' : 'Femenino'; ?></td>
            </tr>
            <tr>
                <th>Estado Civil:</th>
                <td><?php echo htmlspecialchars($empleado['estado_civil']); ?></td>
            </tr>
            <tr>
                <th>Fecha de Nacimiento:</th>
                <td><?php echo htmlspecialchars($empleado['f_nacimiento']); ?></td>
            </tr>
            <tr>
                <th>Celular:</th>
                <td><?php echo htmlspecialchars($empleado['celular']); ?></td>
            </tr>
            <tr>
                <th>Correo:</th>
                <td><?php echo htmlspecialchars($empleado['correo']); ?></td>
            </tr>
            <tr>
                <th>Dirección:</th>
                <td>
                    <?php 
                        echo "Provincia: " . htmlspecialchars($empleado['provincia']) . ", Distrito: " . 
                        htmlspecialchars($empleado['distrito']) . ", Corregimiento: " . 
                        htmlspecialchars($empleado['corregimiento']) . ", Calle: " . 
                        htmlspecialchars($empleado['calle']) . ", Casa: " . 
                        htmlspecialchars($empleado['casa']);
                    ?>
                </td>
            </tr>
            <tr>
                <th>Cargo:</th>
                <td><?php echo htmlspecialchars($empleado['cargo']); ?></td>
            </tr>
            <tr>
                <th>Departamento:</th>
                <td><?php echo htmlspecialchars($empleado['departamento']); ?></td>
            </tr>
            <tr>
                <th>Estado:</th>
                <td><?php echo $empleado['estado'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
            </tr>
        </table>
        <a href="login.php" class="btn-logout">Cerrar Sesión</a>
    </div>
</body>
</html>