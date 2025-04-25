<?php
include 'conexion.php'; // Incluimos la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrativo</title>
    <link rel="stylesheet" href="dashboard.css">
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
                    <h3>Administrador</h3>
                    <p>admin@empresa.com</p>
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
                <li data-section="configuracion">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </li>
                <li onclick="window.location.href='login.html'">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </li>
            </ul>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <!-- Barra superior -->
            <div class="top-bar">
                <button id="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar...">
                </div>
                <div class="top-bar-actions">
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
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

                    <div class="stats-container">
                        <?php
                        // Consultar estadísticas
                        $totalEmpleados = $conexion->query("SELECT COUNT(*) AS total FROM empleados WHERE estado = 1")->fetch_assoc()['total'];
                        $nuevosIngresos = $conexion->query("SELECT COUNT(*) AS total FROM empleados WHERE f_contra >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)")->fetch_assoc()['total'];
                        $bajas = $conexion->query("SELECT COUNT(*) AS total FROM empleados WHERE estado = 0")->fetch_assoc()['total'];
                        $departamentos = $conexion->query("SELECT COUNT(*) AS total FROM departamento")->fetch_assoc()['total'];
                        ?>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Total Empleados</h3>
                                <p class="stat-number"><?php echo $totalEmpleados; ?></p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Nuevos Ingresos</h3>
                                <p class="stat-number"><?php echo $nuevosIngresos; ?></p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-minus"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Bajas</h3>
                                <p class="stat-number"><?php echo $bajas; ?></p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-info">
                                <h3>Departamentos</h3>
                                <p class="stat-number"><?php echo $departamentos; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="charts-container">
                        <div class="chart-card">
                            <div class="chart-header">
                                <h3>Distribución por Departamento</h3>
                            </div>
                            <div class="chart-placeholder">
                                <div class="chart-bars">
                                    <?php
                                    $departamentoData = $conexion->query("SELECT nombre, COUNT(e.cedula) AS total FROM departamento d LEFT JOIN empleados e ON d.codigo = e.departamento GROUP BY d.codigo");
                                    while ($row = $departamentoData->fetch_assoc()) {
                                        $height = ($row['total'] / $totalEmpleados) * 100;
                                        echo "<div class='chart-bar' style='height: {$height}%;' data-label='{$row['nombre']}'></div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="chart-card">
                            <div class="chart-header">
                                <h3>Actividad Reciente</h3>
                            </div>
                            <div class="activity-list">
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
                        <button class="btn-primary" onclick="window.location.href='FormularioFinal.html'">
                            <i class="fas fa-plus"></i> Crear Registro
                        </button>
                    </div>

                    <div class="table-actions">
                        <div class="table-filters">
                            <select id="department-filter">
                                <option value="">Todos los departamentos</option>
                                <?php
                                // Obtener los departamentos de la base de datos
                                $departamentos = $conexion->query("SELECT codigo, nombre FROM departamento");
                                while ($departamento = $departamentos->fetch_assoc()) {
                                    echo "<option value='{$departamento['codigo']}'>{$departamento['nombre']}</option>";
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
                                    $estadoClase = $empleado['estado'] == 1 ? 'active' : 'inactive';
                                    $estadoTexto = $empleado['estado'] == 1 ? 'Activo' : 'Inactivo';
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

                    <div class="pagination">
                        <button class="pagination-btn"><i class="fas fa-angle-double-left"></i></button>
                        <button class="pagination-btn"><i class="fas fa-angle-left"></i></button>
                        <button class="pagination-btn active">1</button>
                        <button class="pagination-btn">2</button>
                        <button class="pagination-btn">3</button>
                        <button class="pagination-btn"><i class="fas fa-angle-right"></i></button>
                        <button class="pagination-btn"><i class="fas fa-angle-double-right"></i></button>
                    </div>
                </section>

                <!-- Sección de Eliminados -->
                <section id="eliminados">
                    <div class="section-header">
                        <h1>Registros Eliminados</h1>
                        <p>Empleados eliminados temporalmente</p>
                    </div>

                    <div class="table-actions">
                        <div class="table-filters">
                            <select id="deleted-department-filter">
                                <option value="">Todos los departamentos</option>
                                <option value="IT">IT</option>
                                <option value="Ventas">Ventas</option>
                                <option value="RRHH">RRHH</option>
                                <option value="Desarrollo">Desarrollo</option>
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
                                    <th>Fecha Eliminación</th>
                                    <th>Eliminado por</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Datos de ejemplo -->
                                <tr>
                                    <td><input type="checkbox" class="deleted-row-checkbox"></td>
                                    <td>008</td>
                                    <td>Roberto</td>
                                    <td>Gómez</td>
                                    <td>IT</td>
                                    <td>Desarrollador</td>
                                    <td>10/04/2023</td>
                                    <td>admin</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="deleted-row-checkbox"></td>
                                    <td>009</td>
                                    <td>Sofía</td>
                                    <td>Hernández</td>
                                    <td>Ventas</td>
                                    <td>Ejecutivo</td>
                                    <td>22/03/2023</td>
                                    <td>admin</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="deleted-row-checkbox"></td>
                                    <td>010</td>
                                    <td>Javier</td>
                                    <td>Díaz</td>
                                    <td>RRHH</td>
                                    <td>Analista</td>
                                    <td>05/02/2023</td>
                                    <td>admin</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination">
                        <button class="pagination-btn"><i class="fas fa-angle-double-left"></i></button>
                        <button class="pagination-btn"><i class="fas fa-angle-left"></i></button>
                        <button class="pagination-btn active">1</button>
                        <button class="pagination-btn"><i class="fas fa-angle-right"></i></button>
                        <button class="pagination-btn"><i class="fas fa-angle-double-right"></i></button>
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
                            <label>Nombre:</label>
                            <p id="employee-name"></p>
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

    <script src="dashboard.js"></script>
</body>
</html>