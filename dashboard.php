<?php
include 'conexion.php'; // <-- Asegura la conexión SIEMPRE
// Validación de acceso RRHH antes de cualquier salida HTML
session_start();
if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $stmt = $conexion->prepare("SELECT departamento FROM empleados WHERE cedula = ? AND contraseña = ?");
    $stmt->bind_param("ss", $usuario, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo "<script>alert('Credenciales inválidas.'); window.location.href='login.php';</script>";
        exit;
    } else {
        $row = $result->fetch_assoc();
        if ($row['departamento'] !== '04') {
            echo "<script>alert('No tiene permisos para acceder a este panel.'); window.location.href='login.php';</script>";
            exit;
        }
        // Guardar sesión si se desea
        $_SESSION['usuario'] = $usuario;
        $_SESSION['departamento'] = $row['departamento'];
    }
    $stmt->close();
    // $conexion queda disponible para el resto del dashboard
} else if (!isset($_SESSION['usuario']) || !isset($_SESSION['departamento']) || $_SESSION['departamento'] !== '04') {
    // Si no hay sesión válida, redirigir a login
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrativo</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-building"></i>
                <h2>Sistema Corporativo</h2>
            </div>
            
            <div class="sidebar-user">
                <div class="user-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="user-info">
                    <h3><?php
                        // Mostrar el nombre del administrador logueado
                        if (isset($_SESSION['usuario'])) {
                            $cedula = $_SESSION['usuario'];
                            $nombreAdmin = '';
                            $cargoAdmin = '';
                            if (isset($conexion)) {
                                $stmt = $conexion->prepare("SELECT CONCAT(nombre1, ' ', apellido1) AS nombre, c.nombre AS cargo FROM empleados e LEFT JOIN cargo c ON e.cargo = c.codigo WHERE cedula = ?");
                                $stmt->bind_param("s", $cedula);
                                $stmt->execute();
                                $stmt->bind_result($nombre, $cargo);
                                if ($stmt->fetch()) {
                                    $nombreAdmin = $nombre;
                                    $cargoAdmin = $cargo;
                                }
                                $stmt->close();
                            }
                            echo htmlspecialchars($nombreAdmin ?: $cedula);
                        } else {
                            echo 'Administrador';
                        }
                    ?></h3>
                    <p><?php echo htmlspecialchars($cargoAdmin ?: 'Cargo'); ?></p>
                </div>
            </div>
            
            <ul class="sidebar-menu">
                <li class="active" data-section="inicio">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </li>
                <li data-section="empleados">
                    <i class="fas fa-users"></i>
                    <span>Empleados</span>
                </li>
                <li data-section="eliminados">
                    <i class="fas fa-trash-alt"></i>
                    <span>Eliminados</span>
                </li>
                <li class="sidebar-divider"></li>
                <li onclick="window.location.href='login.php'">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </li>
            </ul>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <!-- Barra superior -->
            <div class="top-bar">
                <div class="sidebar-indicator">
                    <i class="fas fa-building"></i>
                </div>
                <div class="welcome-message">
                    <h2>Bienvenido, <?php echo htmlspecialchars($nombreAdmin ?: 'Administrador'); ?></h2>
                    <p>Panel de administración de recursos humanos</p>
                </div>
                <div class="top-bar-actions">
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>         
                    </button>
                    <button class="profile-btn">
                        <i class="fas fa-user-circle"></i>
                    </button>
                </div>
            </div>

            <!-- Contenedor de secciones -->
            <div class="section-container">
                <!-- Sección de Inicio -->
                <section id="inicio" class="active">
                    <div class="section-header">
                        <h1>Dashboard</h1>
                        <p>Bienvenido al panel de administración</p>
                    </div>

                    <div class="stats-container" id="stats-container">
                        <?php
                        // Consultar estadísticas
                        $totalEmpleados = $conexion->query("SELECT COUNT(*) AS total FROM empleados WHERE estado = 0")->fetch_assoc()['total'];
                        $nuevosIngresos = $conexion->query("SELECT COUNT(*) AS total FROM empleados WHERE f_contra >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)")->fetch_assoc()['total'];
                        $bajas = $conexion->query("SELECT COUNT(*) AS total FROM empleados WHERE estado = 1")->fetch_assoc()['total'];
                        $departamentos = $conexion->query("SELECT COUNT(*) AS total FROM departamento")->fetch_assoc()['total'];
                        ?>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Total Empleados</h3>
                                <p class="stat-number" id="total-empleados"><?php echo $totalEmpleados; ?></p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Nuevos Ingresos</h3>
                                <p class="stat-number" id="nuevos-ingresos"><?php echo $nuevosIngresos; ?></p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-minus"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Bajas</h3>
                                <p class="stat-number" id="bajas"><?php echo $bajas; ?></p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Departamentos</h3>
                                <p class="stat-number" id="departamentos"><?php echo $departamentos; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="charts-container">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h3>Distribución por Departamento</h3>
                                <div class="chart-actions">
                                    <button id="refresh-chart"><i class="fas fa-sync-alt"></i></button>
                                </div>
                            </div>
                            <div class="chart-placeholder" id="chart-departamentos">
                                <div class="chart-bars">
                                    <?php
                                    $departamentoData = $conexion->query("SELECT d.nombre, COUNT(e.cedula) AS total FROM departamento d LEFT JOIN empleados e ON d.codigo = e.departamento GROUP BY d.codigo");
                                    while ($row = $departamentoData->fetch_assoc()) {
                                        $height = ($row['total'] / ($totalEmpleados > 0 ? $totalEmpleados : 1)) * 100;
                                        echo "<div class='chart-bar' style='height: {$height}%;' data-label='{$row['nombre']}' data-value='{$row['total']}'></div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="chart-card">
                            <div class="chart-header">
                                <h3>Registros Recientes</h3>
                                <div class="chart-actions">
                                    <button id="refresh-activity"><i class="fas fa-sync-alt"></i></button>
                                </div>
                            </div>
                            <div class="activity-list" id="activity-list">
                                <?php
                                $actividadReciente = $conexion->query("SELECT nombre1, apellido1, f_contra FROM empleados ORDER BY f_contra DESC LIMIT 5");
                                while ($row = $actividadReciente->fetch_assoc()) {
                                    echo "
                                    <div class='activity-item'>
                                        <div class='activity-icon'><i class='fas fa-user-plus'></i></div>
                                        <div class='activity-details'>
                                            <p>{$row['nombre1']} {$row['apellido1']} registrado</p>
                                            <span>{$row['f_contra']}</span>
                                        </div>
                                    </div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Sección de Empleados -->
                <section id="empleados">
                    <div class="section-header">
                        <h1>Gestión de Empleados</h1>
                        <button class="btn-primary" onclick="window.location.href='FormularioFinal.php'">
                            <i class="fas fa-plus"></i> Crear Registro
                        </button>
                    </div>

                    <div class="table-actions">
                        <div class="table-filters">
                            <div class="search-bar">
                                <i class="fas fa-search"></i>
                                <input type="text" id="employee-search" placeholder="Buscar empleado...">
                            </div>
                            <select id="department-filter">
                                <option value="">Todos los departamentos</option>
                                <?php
                                // Obtener los departamentos de la base de datos
                                $departamentos = $conexion->query("SELECT codigo, nombre FROM departamento");
                                while ($departamento = $departamentos->fetch_assoc()) {
                                    echo "<option value='{$departamento['nombre']}'>{$departamento['nombre']}</option>";
                                }
                                ?>
                            </select>
                            <select id="status-filter">
                                <option value="">Todos los estados</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="selected-actions" id="selected-actions">
                            <button class="btn-view" id="btn-view" disabled>
                                <i class="fas fa-eye"></i> Ver
                            </button>
                            <button class="btn-edit" id="btn-edit" disabled>
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn-delete" id="btn-delete" disabled>
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>

                    <div class="table-container">
                        <table id="employees-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Departamento</th>
                                    <th>Cargo</th>
                                    <th>Fecha Contratación</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Consultar empleados de la base de datos
                                $empleados = $conexion->query("
                                    SELECT e.cedula, e.nombre1, e.apellido1, d.nombre AS departamento, c.nombre AS cargo, e.f_contra, e.estado
                                    FROM empleados e
                                    LEFT JOIN departamento d ON e.departamento = d.codigo
                                    LEFT JOIN cargo c ON e.cargo = c.codigo
                                ");
                                while ($empleado = $empleados->fetch_assoc()) {
                                    $estadoClase = $empleado['estado'] == 0 ? 'active' : 'inactive';
                                    $estadoTexto = $empleado['estado'] == 0 ? 'Activo' : 'Inactivo';
                                    echo "
                                    <tr data-id='{$empleado['cedula']}' data-nombre='{$empleado['nombre1']}' data-apellido='{$empleado['apellido1']}' data-departamento='{$empleado['departamento']}' data-cargo='{$empleado['cargo']}' data-fecha='{$empleado['f_contra']}' data-estado='{$estadoTexto}'>
                                        <td><input type='checkbox' class='row-checkbox'></td>
                                        <td>{$empleado['cedula']}</td>
                                        <td>{$empleado['nombre1']}</td>
                                        <td>{$empleado['apellido1']}</td>
                                        <td>{$empleado['departamento']}</td>
                                        <td>{$empleado['cargo']}</td>
                                        <td>{$empleado['f_contra']}</td>
                                        <td><span class='status-badge {$estadoClase}'>{$estadoTexto}</span></td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-info">
                        <span id="pagination-info-employees">Mostrando 0-0 de 0 registros</span>
                    </div>
                    <div class="pagination" id="pagination-employees">
                        <button class="pagination-btn" data-action="first"><i class="fas fa-angle-double-left"></i></button>
                        <button class="pagination-btn" data-action="prev"><i class="fas fa-angle-left"></i></button>
                        <div class="pagination-numbers" id="pagination-numbers-employees">
                            <!-- Aquí se generarán los números de página dinámicamente -->
                        </div>
                        <button class="pagination-btn" data-action="next"><i class="fas fa-angle-right"></i></button>
                        <button class="pagination-btn" data-action="last"><i class="fas fa-angle-double-right"></i></button>
                    </div>
                </section>

                <!-- Sección de Eliminados -->
                <section id="eliminados">
                    <div class="section-header">
                        <h1>Empleados Eliminados</h1>
                        <p>Empleados eliminados temporalmente</p>
                    </div>

                    <div class="table-actions">
                        <div class="table-filters">
                            <div class="search-bar">
                                <i class="fas fa-search"></i>
                                <input type="text" id="deleted-employee-search" placeholder="Buscar empleado eliminado...">
                            </div>
                            <select id="deleted-department-filter">
                                <option value="">Todos los departamentos</option>
                                <?php
                                // Reutilizar la consulta de departamentos
                                $departamentos = $conexion->query("SELECT codigo, nombre FROM departamento");
                                while ($departamento = $departamentos->fetch_assoc()) {
                                    echo "<option value='{$departamento['nombre']}'>{$departamento['nombre']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="selected-actions" id="deleted-selected-actions">
                            <button class="btn-view" id="deleted-btn-view" disabled>
                                <i class="fas fa-eye"></i> Ver
                            </button>
                            <button class="btn-restore" id="btn-restore" disabled>
                                <i class="fas fa-undo"></i> Restaurar
                            </button>
                            <button class="btn-delete-permanent" id="btn-delete-permanent" disabled>
                                <i class="fas fa-trash"></i> Eliminar Permanente
                            </button>
                        </div>
                    </div>

                    <div class="table-container">
                        <table id="deleted-employees-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="deleted-select-all"></th>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Departamento</th>
                                    <th>Cargo</th>
                                    <th>Fecha Contratación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenará dinámicamente con AJAX -->
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-info">
                        <span id="pagination-info-deleted">Mostrando 0-0 de 0 registros</span>
                    </div>
                    <div class="pagination" id="pagination-deleted">
                        <button class="pagination-btn" data-action="first"><i class="fas fa-angle-double-left"></i></button>
                        <button class="pagination-btn" data-action="prev"><i class="fas fa-angle-left"></i></button>
                        <div class="pagination-numbers" id="pagination-numbers-deleted">
                            <!-- Aquí se generarán los números de página dinámicamente -->
                        </div>
                        <button class="pagination-btn" data-action="next"><i class="fas fa-angle-right"></i></button>
                        <button class="pagination-btn" data-action="last"><i class="fas fa-angle-double-right"></i></button>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles de empleado -->
    <div class="modal" id="view-employee-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detalles del Empleado</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="employee-details">
                    <div class="employee-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="employee-info-grid">
                        <div class="info-group">
                            <label>ID:</label>
                            <p id="employee-id"></p>
                        </div>
                        <div class="info-group">
                            <label>Cédula:</label>
                            <p id="employee-cedula"></p>
                        </div>
                        <div class="info-group">
                            <label>Nombre:</label>
                            <p id="employee-name"></p>
                        </div>
                        <div class="info-group">
                            <label>Apellido:</label>
                            <p id="employee-lastname"></p>
                        </div>
                        <div class="info-group">
                            <label>Departamento:</label>
                            <p id="employee-department"></p>
                        </div>
                        <div class="info-group">
                            <label>Cargo:</label>
                            <p id="employee-position"></p>
                        </div>
                        <div class="info-group">
                            <label>Fecha Contratación:</label>
                            <p id="employee-hire-date"></p>
                        </div>
                        <div class="info-group">
                            <label>Estado:</label>
                            <p id="employee-status"></p>
                        </div>
                        <div class="info-group">
                            <label>Correo:</label>
                            <p id="employee-email"></p>
                        </div>
                        <div class="info-group">
                            <label>Teléfono:</label>
                            <p id="employee-phone"></p>
                        </div>
                        <div class="info-group">
                            <label>Dirección:</label>
                            <p id="employee-address"></p>
                        </div>
                        <div class="info-group">
                            <label>Fecha de Nacimiento:</label>
                            <p id="employee-birthdate"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" id="close-view-modal">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal" id="delete-confirm-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmar Eliminación</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="confirm-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>¿Está seguro que desea eliminar el(los) registro(s) seleccionado(s)?</p>
                    <p class="warning-text">Esta acción moverá los registros a la sección de eliminados.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" id="cancel-delete">Cancelar</button>
                <button class="btn-danger" id="confirm-delete">Eliminar</button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para restaurar -->
    <div class="modal" id="restore-confirm-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmar Restauración</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="confirm-message">
                    <i class="fas fa-undo"></i>
                    <p>¿Está seguro que desea restaurar el(los) registro(s) seleccionado(s)?</p>
                    <p class="info-text">Esta acción devolverá los registros a la lista de empleados activos.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" id="cancel-restore">Cancelar</button>
                <button class="btn-primary" id="confirm-restore">Restaurar</button>
            </div>
        </div>
    </div>

    <!-- Modal de notificación -->
    <div class="modal" id="notification-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="notification-title">Notificación</h2>
                <button class="close-modal" id="close-notification-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="notification-message"></p>
            </div>
            <div class="modal-footer">
                <button class="btn-primary" id="ok-notification-modal">OK</button>
            </div>
        </div>
    </div>

    <!-- Archivo AJAX para actualizar datos dinámicamente -->
    <script src="scripts/dashboard.js"></script>
</body>
</html>
