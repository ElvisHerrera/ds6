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

// Obtener datos del formulario
$user = $_POST['username'];
$pass = $_POST['password'];

// Consulta para verificar credenciales directamente en la tabla empleados
$sql = "SELECT departamento 
        FROM empleados 
        WHERE cedula = ? AND contraseña = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Usuario encontrado
    $row = $result->fetch_assoc();
    $departamento = $row['departamento'];

    if ($departamento === '04') { // Código para RRHH
        // Redirigir al dashboard
        header("Location: dashboard.php");
    } else {
        // Redirigir a vistaEmpleado con el username como parámetro
        header("Location: vistaEmpleado.php?username=" . urlencode($user));
    }
} else {
    // Credenciales incorrectas
    echo "<script>
        alert('Usuario o contraseña incorrectos');
        window.location.href = 'login.php';
    </script>";
}

$stmt->close();
$conn->close();
?>