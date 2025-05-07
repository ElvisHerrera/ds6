<?php
date_default_timezone_set('America/Panama');

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
$sql = "SELECT e.*, 
               c.nombre AS cargo_nombre, 
               d.nombre AS departamento_nombre, 
               p.nombre_provincia, 
               dis.nombre_distrito, 
               cor.nombre_corregimiento 
        FROM empleados e
        LEFT JOIN cargo c ON e.cargo = c.codigo
        LEFT JOIN departamento d ON e.departamento = d.codigo
        LEFT JOIN provincia p ON e.provincia = p.codigo_provincia
        LEFT JOIN distrito dis ON e.distrito = dis.codigo_distrito
        LEFT JOIN corregimiento cor ON e.corregimiento = cor.codigo_corregimiento
        WHERE e.cedula = ?";
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

// Formatear el nombre completo
$nombreCompleto = $empleado['nombre1'] . " " . $empleado['nombre2'] . " " . 
                 $empleado['apellido1'] . " " . $empleado['apellido2'];

// Formatear la dirección completa
$direccionCompleta = "Provincia: " . $empleado['nombre_provincia'] . ", Distrito: " . 
                    $empleado['nombre_distrito'] . ", Corregimiento: " . 
                    $empleado['nombre_corregimiento'] . ", Calle: " . 
                    $empleado['calle'] . ", Casa: " . 
                    $empleado['casa'];

// Determinar el género para mostrar el icono correcto
$generoIcono = $empleado['genero'] == 1 ? 'male' : 'female';

// Determinar el estado para mostrar el badge correcto
$estadoClase = $empleado['estado'] == 1 ? 'active' : 'inactive';
$estadoTexto = $empleado['estado'] == 1 ? 'Activo' : 'Inactivo';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Empleado</title>
    <link rel="stylesheet" href="empleado-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="employee-container">
        <!-- Barra lateral con información básica -->
        <div class="sidebar">
            <div class="company-logo">
                <i class="fas fa-building"></i>
                <h2>Sistema Corporativo</h2>
            </div>
            
            <div class="employee-profile">
                <div class="profile-image">
                    <i class="fas fa-<?php echo $generoIcono; ?>"></i>
                </div>
                <h3 class="employee-name"><?php echo htmlspecialchars($nombreCompleto); ?></h3>
                <p class="employee-position"><?php echo htmlspecialchars($empleado['cargo_nombre']); ?></p>
                <p class="employee-department"><?php echo htmlspecialchars($empleado['departamento_nombre']); ?></p>
                <div class="employee-status">
                    <span class="status-badge <?php echo $estadoClase; ?>"><?php echo $estadoTexto; ?></span>
                </div>
            </div>
            
            <div class="sidebar-menu">
                <a href="#personal-info" class="menu-item active">
                    <i class="fas fa-user"></i>
                    <span>Información Personal</span>
                </a>
                <a href="#employment-info" class="menu-item">
                    <i class="fas fa-briefcase"></i>
                    <span>Información Laboral</span>
                </a>
                <a href="#contact-info" class="menu-item">
                    <i class="fas fa-address-card"></i>
                    <span>Contacto</span>
                </a>
                <a href="login.php" class="menu-item logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </div>
        
        <!-- Contenido principal -->
        <div class="main-content">
            <div class="top-bar">
                <button id="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="welcome-message">
                    <h2>Bienvenido, <?php echo htmlspecialchars($empleado['nombre1']); ?></h2>
                    <p>Última conexión: <?php echo date('d/m/Y - h:i A'); ?></p>
                </div>
                <div class="top-actions">
                    <a href="login.php" class="btn-logout-mobile">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
            
            <div class="content-wrapper">
                <!-- Sección de información personal -->
                <div id="personal-info" class="info-section active">
                    <div class="section-header">
                        <h3><i class="fas fa-user-circle"></i> Información Personal</h3>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Cédula</label>
                                <p><?php echo htmlspecialchars($empleado['cedula']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Nombre Completo</label>
                                <p><?php echo htmlspecialchars($nombreCompleto); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Género</label>
                                <p>
                                    <i class="fas fa-<?php echo $generoIcono; ?>"></i>
                                    <?php echo $empleado['genero'] == 1 ? 'Masculino' : 'Femenino'; ?>
                                </p>
                            </div>
                            <div class="info-item">
                                <label>Estado Civil</label>
                                <p>
                                    <?php
                                    switch ($empleado['estado_civil']) {
                                        case 1:
                                            echo 'Soltero/a';
                                            break;
                                        case 2:
                                            echo 'Casado/a';
                                            break;
                                        case 3:
                                            echo 'Divorciado/a';
                                            break;
                                        case 4:
                                            echo 'Viudo/a';
                                            break;
                                        default:
                                            echo 'Desconocido';
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="info-item">
                                <label>Fecha de Nacimiento</label>
                                <p><?php echo htmlspecialchars($empleado['f_nacimiento']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de información laboral -->
                <div id="employment-info" class="info-section">
                    <div class="section-header">
                        <h3><i class="fas fa-briefcase"></i> Información Laboral</h3>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-grid">
                        <div class="info-item">
                                <label>Departamento</label>
                                <p><?php echo htmlspecialchars($empleado['departamento_nombre']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Cargo</label>
                                <p><?php echo htmlspecialchars($empleado['cargo_nombre']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sección de información de contacto -->
                <div id="contact-info" class="info-section">
                    <div class="section-header">
                        <h3><i class="fas fa-address-card"></i> Información de Contacto</h3>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Celular</label>
                                <p>
                                    <i class="fas fa-phone"></i>
                                    <?php echo htmlspecialchars($empleado['celular']); ?>
                                </p>
                            </div>
                            <div class="info-item">
                                <label>Correo Electrónico</label>
                                <p>
                                    <i class="fas fa-envelope"></i>
                                    <?php echo htmlspecialchars($empleado['correo']); ?>
                                </p>
                            </div>
                            <div class="info-item full-width">
                                <label>Dirección</label>
                                <p>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <strong>Provincia:</strong> <?php echo htmlspecialchars($empleado['nombre_provincia']); ?></p>
                                <p style="margin-left: 18px;"><strong>Distrito:</strong> <?php echo htmlspecialchars($empleado['nombre_distrito']); ?></p>
                                <p style="margin-left: 18px;"><strong>Corregimiento:</strong> <?php echo htmlspecialchars($empleado['nombre_corregimiento']); ?></p>
                                <p style="margin-left: 18px;"><strong>Calle:</strong> <?php echo htmlspecialchars($empleado['calle']); ?></p>
                                <p style="margin-left: 18px;"><strong>Casa:</strong> <?php echo htmlspecialchars($empleado['casa']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="empleado-script.js"></script>
</body>
</html>