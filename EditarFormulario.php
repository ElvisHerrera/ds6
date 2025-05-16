<?php
include 'conexion.php'; // Incluye la conexión a la base de datos

// Obtener la cédula desde el parámetro GET
$empleadoData = null;
if (isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];
    $stmt = $conexion->prepare("SELECT * FROM empleados WHERE cedula = ?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $empleadoData = $result->fetch_assoc();
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Cedula'], $_GET['cedula'])) {
    include 'conexion.php';
    $cedula_actual = $_GET['cedula'];
    // Recoger todos los campos del formulario
    $prefijo = $_POST['Cedula'];
    $tomo = $_POST['tomo'];
    $asiento = $_POST['asiento'];
    $nombre1 = $_POST['first-name'];
    $nombre2 = $_POST['second-name'];
    $apellido1 = $_POST['first-lastname'];
    $apellido2 = $_POST['second-lastname'];
    $genero = $_POST['genero'];
    $usa_ac = isset($_POST['apellidocheck']) ? 1 : 0;
    $apellidoc = isset($_POST['apellidoCasada']) ? $_POST['apellidoCasada'] : null;
    $estado_civil = $_POST['estadoCivil'];
    $f_nacimiento = $_POST['fechaNacimiento'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $nacionalidad = $_POST['nacionalidad'];
    $celular = $_POST['Celular'];
    $telefono = $_POST['telefono'];
    $provincia = $_POST['provincia'];
    $distrito = $_POST['distrito'];
    $corregimiento = $_POST['corregimiento'];
    $calle = $_POST['calle'];
    $casa = $_POST['casa'];
    $comunidad = $_POST['comunidad'];
    $f_contra = $_POST['fechadecontratacion'];
    $departamento = $_POST['departamento'];
    $cargo = $_POST['cargo'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $estado = ($_POST['estado'] === 'activo') ? 0 : 1;

    $stmt = $conexion->prepare("UPDATE empleados SET prefijo=?, tomo=?, asiento=?, nombre1=?, nombre2=?, apellido1=?, apellido2=?, genero=?, usa_ac=?, apellidoc=?, estado_civil=?, f_nacimiento=?, tipo_sangre=?, nacionalidad=?, celular=?, telefono=?, provincia=?, distrito=?, corregimiento=?, calle=?, casa=?, comunidad=?, f_contra=?, departamento=?, cargo=?, correo=?, contraseña=?, estado=? WHERE cedula=?");
    $stmt->bind_param(
        "ssssssssissssssssssssssssssis",
        $prefijo, $tomo, $asiento, $nombre1, $nombre2, $apellido1, $apellido2, $genero, $usa_ac, $apellidoc, $estado_civil, $f_nacimiento, $tipo_sangre, $nacionalidad, $celular, $telefono, $provincia, $distrito, $corregimiento, $calle, $casa, $comunidad, $f_contra, $departamento, $cargo, $correo, $contrasena, $estado, $cedula_actual
    );
    if ($stmt->execute()) {
        echo "<script>alert('Empleado actualizado correctamente.'); window.location.href='dashboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error al actualizar el empleado.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="formulario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistema de Edición de Registro de Empleados</h1>
            <p>Complete el formulario con la información requerida</p>
        </header>

        <form action="EditarFormulario.php?cedula=<?php echo urlencode($cedula); ?>" method="post">
            <!-- Sección Datos Generales -->
            <section class="form-section">
                <div class="section-header">
                    <i class="fas fa-user"></i>
                    <h2>Datos Generales</h2>
                </div>
                
                <div class="form-grid">
                    <div class="form-row three-columns">
                        <div class="form-group">
                            <label for="Cedula">Prefijo:</label>
                            <select id="Cedula" name="Cedula" required disabled>
                                <option value="" disabled></option>
                                <?php
                                for ($i = 1; $i <= 13; $i++) {
                                    $selected = ($empleadoData && isset($empleadoData['prefijo']) && $empleadoData['prefijo'] == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tomo">Tomo:</label>
                            <input type="text" id="tomo" name="tomo" maxlength="4" required disabled value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['tomo']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="asiento">Asiento:</label>
                            <input type="text" id="asiento" name="asiento" maxlength="5" required disabled value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['asiento']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-row two-columns">
                        <div class="form-group">
                            <label for="first-name">Primer Nombre:</label>
                            <input type="text" id="first-name" name="first-name" required value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['nombre1']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="second-name">Segundo Nombre:</label>
                            <input type="text" id="second-name" name="second-name" value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['nombre2']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-row three-columns">
                        <div class="form-group">
                            <label for="first-lastname">Primer Apellido:</label>
                            <input type="text" id="first-lastname" name="first-lastname" required value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['apellido1']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="second-lastname">Segundo Apellido:</label>
                            <input type="text" id="second-lastname" name="second-lastname" value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['apellido2']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="genero">Género:</label>
                            <select id="genero" name="genero" required>
                                <option value="" disabled></option>
                                <option value="masculino" <?php echo ($empleadoData && $empleadoData['genero'] == 'masculino') ? 'selected' : ''; ?>>Masculino</option>
                                <option value="femenino" <?php echo ($empleadoData && $empleadoData['genero'] == 'femenino') ? 'selected' : ''; ?>>Femenino</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row three-columns">
                        <div class="form-group checkbox-group">
                            <label for="apellidocheck">¿Usa apellido de casada?</label>
                            <input type="checkbox" id="apellidocheck" name="apellidocheck" <?php echo ($empleadoData && $empleadoData['usa_ac']) ? 'checked' : ''; ?>>
                        </div>
                        <div class="form-group">
                            <label for="apellidoCasada">Apellido de casada:</label>
                            <input type="text" id="apellidoCasada" name="apellidoCasada" <?php echo ($empleadoData && $empleadoData['usa_ac']) ? '' : 'disabled'; ?> value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['apellidoc']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="estadoCivil">Estado civil:</label>
                            <select id="estadoCivil" name="estadoCivil" required>
                                <option value="" disabled></option>
                                <option value="soltero" <?php echo ($empleadoData && $empleadoData['estado_civil'] == 'soltero') ? 'selected' : ''; ?>>Soltero</option>
                                <option value="casado" <?php echo ($empleadoData && $empleadoData['estado_civil'] == 'casado') ? 'selected' : ''; ?>>Casado</option>
                                <option value="divorciado" <?php echo ($empleadoData && $empleadoData['estado_civil'] == 'divorciado') ? 'selected' : ''; ?>>Divorciado</option>
                                <option value="viudo" <?php echo ($empleadoData && $empleadoData['estado_civil'] == 'viudo') ? 'selected' : ''; ?>>Viudo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row three-columns">
                        <div class="form-group">
                            <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                            <input type="date" id="fechaNacimiento" name="fechaNacimiento" required value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['f_nacimiento']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="tipo_sangre">Tipo de Sangre:</label>
                            <select id="tipo_sangre" name="tipo_sangre" required>
                                <option value="" disabled></option>
                                <?php
                                $tipos = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                                foreach ($tipos as $tipo) {
                                    $selected = ($empleadoData && $empleadoData['tipo_sangre'] == $tipo) ? 'selected' : '';
                                    echo "<option value='$tipo' $selected>$tipo</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nacionalidad">Nacionalidad:</label>
                            <select id="nacionalidad" name="nacionalidad" required>
                                <option value="" disabled>Seleccione una nacionalidad</option>
                                <?php
                                $query = "SELECT codigo, pais FROM nacionalidad ORDER BY pais ASC";
                                $result = $conexion->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    $selected = ($empleadoData && $empleadoData['nacionalidad'] == $row['codigo']) ? 'selected' : '';
                                    echo "<option value='{$row['codigo']}' $selected>{$row['pais']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row two-columns">
                        <div class="form-group">
                            <label for="Celular">Celular:</label>
                            <input type="tel" id="Celular" name="Celular" maxlength="8" pattern="\d{8}" title="El número de celular debe tener exactamente 8 dígitos" required value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['celular']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="tel" id="telefono" name="telefono" maxlength="7" pattern="\d{7}" title="El número de teléfono debe tener exactamente 7 dígitos" required value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['telefono']) : ''; ?>">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección Ubicación -->
            <section class="form-section">
                <div class="section-header">
                    <i class="fas fa-map-marker-alt"></i>
                    <h2>Ubicación</h2>
                </div>
                
                <div class="form-grid">
                    <div class="form-row three-columns">
                        <div class="form-group">
                            <label for="provincia">Provincia:</label>
                            <select id="provincia" name="provincia" required onchange="cargarDistritos(this.value)">
                                <option value="" disabled>Seleccione una provincia</option>
                                <?php
                                $query = "SELECT codigo_provincia, nombre_provincia FROM provincia";
                                $result = $conexion->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    $selected = ($empleadoData && $empleadoData['provincia'] == $row['codigo_provincia']) ? 'selected' : '';
                                    echo "<option value='{$row['codigo_provincia']}' $selected>{$row['nombre_provincia']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="distrito">Distrito:</label>
                            <select id="distrito" name="distrito" required onchange="cargarCorregimientos(this.value)">
                                <option value="" disabled selected>Seleccione un distrito</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="corregimiento">Corregimiento:</label>
                            <select id="corregimiento" name="corregimiento" required>
                                <option value="" disabled selected>Seleccione un corregimiento</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row two-columns">
                        <div class="form-group">
                            <label for="calle">Calle:</label>
                            <input type="text" id="calle" name="calle" required value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['calle']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="casa">Casa:</label>
                            <input type="text" id="casa" name="casa" required value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['casa']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="comunidad">Comunidad:</label>
                            <input type="text" id="comunidad" name="comunidad" value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['comunidad']) : ''; ?>">
                        </div>
                    </div>
            </section>

            <!-- Sección Empleado -->
            <section class="form-section">
                <div class="section-header">
                    <i class="fas fa-briefcase"></i>
                    <h2>Información del empleado</h2>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="fechadecontratacion">Fecha de Contratación:</label>
                        <input type="date" id="fechadecontratacion" name="fechadecontratacion" required value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['f_contra']) : ''; ?>">
                    </div>

                    <div class="form-row two-columns">
                        <div class="form-group">
                            <label for="departamento">Departamento:</label>
                            <select id="departamento" name="departamento" required onchange="cargarCargos(this.value)">
                                <option value="" disabled>Seleccione un departamento</option>
                                <?php
                                $query = "SELECT codigo, nombre FROM departamento";
                                $result = $conexion->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    $selected = ($empleadoData && $empleadoData['departamento'] == $row['codigo']) ? 'selected' : '';
                                    echo "<option value='{$row['codigo']}' $selected>{$row['nombre']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cargo">Cargo:</label>
                            <select id="cargo" name="cargo" required>
                                <option value="" disabled selected>Seleccione un cargo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row two-columns">
                        <div class="form-group">
                            <label for="correo">Correo Electrónico:</label>
                            <input type="email" id="correo" name="correo" required value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['correo']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="contrasena">Contraseña:</label>
                            <input type="password" id="contrasena" name="contrasena" value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['contraseña']) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <select id="estado" name="estado" required>
                                <option value="" disabled></option>
                                <option value="activo" <?php echo ($empleadoData && $empleadoData['estado'] == 0) ? 'selected' : ''; ?>>Activo</option>
                                <option value="inactivo" <?php echo ($empleadoData && $empleadoData['estado'] == 1) ? 'selected' : ''; ?>>Inactivo</option>
                            </select>   
                        </div>
                        <div class="form-group">
                            <label for="validar_contraseña">Confirmar Contraseña:</label>
                            <input type="password" id="validar_contraseña" name="validar_contraseña" required value="<?php echo $empleadoData ? htmlspecialchars($empleadoData['contraseña']) : ''; ?>">
                        </div>
                    </div>
                </div>
            </section>

            <div class="form-actions">
                <button type="button" class="btn btn-danger" onclick="window.location.href='dashboard.php';">
                    <i class="fas fa-arrow-left"></i> Regresar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enviar
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const apellidoCheck = document.getElementById('apellidocheck');
            const apellidoCasada = document.getElementById('apellidoCasada');
            
            apellidoCheck.addEventListener('change', function() {
                apellidoCasada.disabled = !this.checked;
                if (!this.checked) {
                    apellidoCasada.value = apellidoCasada.value; // Limpia el campo si se deshabilita
                }
            });

            const tomoInput = document.getElementById('tomo');
            const asientoInput = document.getElementById('asiento');

            function allowOnlyNumbers(event) {
                event.target.value = event.target.value.replace(/\D/g, ''); // Elimina cualquier carácter no numérico
            }

            tomoInput.addEventListener('input', allowOnlyNumbers);
            asientoInput.addEventListener('input', allowOnlyNumbers);

            const inputs = [
                document.getElementById('first-name'),
                document.getElementById('second-name'),
                document.getElementById('first-lastname'),
                document.getElementById('second-lastname')
            ];

            function allowOnlyLetters(event) {
                event.target.value = event.target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, ''); // Permite solo letras y espacios
            }

            inputs.forEach(input => {
                input.addEventListener('input', allowOnlyLetters);
            });

            apellidoCasada.addEventListener('input', allowOnlyLetters);

            const contrasena = document.getElementById('contrasena');
            const validarContrasena = document.getElementById('validar_contraseña');

            function validatePasswords() {
                if (validarContrasena.value !== contrasena.value) {
                    validarContrasena.setCustomValidity('Las contraseñas no coinciden');
                    validarContrasena.title = 'Las contraseñas no coinciden';
                } else {
                    validarContrasena.setCustomValidity('');
                    validarContrasena.title = '';
                }
            }

            contrasena.addEventListener('input', validatePasswords);
            validarContrasena.addEventListener('input', validatePasswords);

            const celularInput = document.getElementById('Celular');

            function validateCelular(event) {
                event.target.value = event.target.value.replace(/\D/g, ''); // Elimina cualquier carácter no numérico
                if (event.target.value.length > 8) {
                    event.target.value = event.target.value.slice(0, 8); // Limita a 8 dígitos
                }
            }

            celularInput.addEventListener('input', validateCelular);

            const telefonoInput = document.getElementById('telefono');

            function validateTelefono(event) {
                event.target.value = event.target.value.replace(/\D/g, ''); // Elimina cualquier carácter no numérico
                if (event.target.value.length > 7) {
                    event.target.value = event.target.value.slice(0, 7); // Limita a 7 dígitos
                }
            }

            telefonoInput.addEventListener('input', validateTelefono);

            // Si hay datos de empleado, cargar distritos, corregimientos y cargos
            <?php if ($empleadoData): ?>
            // Cargar distritos y seleccionar el correcto
            if ('<?php echo $empleadoData['provincia']; ?>') {
                fetch(`ajaxHandler.php?provincia=<?php echo $empleadoData['provincia']; ?>`)
                    .then(response => response.json())
                    .then(data => {
                        const distritoSelect = document.getElementById('distrito');
                        distritoSelect.innerHTML = '<option value="" disabled>Seleccione un distrito</option>';
                        data.forEach(distrito => {
                            const selected = distrito.codigo_distrito == '<?php echo $empleadoData['distrito']; ?>' ? 'selected' : '';
                            distritoSelect.innerHTML += `<option value="${distrito.codigo_distrito}" ${selected}>${distrito.nombre_distrito}</option>`;
                        });
                        // Cargar corregimientos después de distritos
                        if ('<?php echo $empleadoData['distrito']; ?>') {
                            fetch(`ajaxHandler.php?distrito=<?php echo $empleadoData['distrito']; ?>`)
                                .then(response => response.json())
                                .then(data => {
                                    const corregimientoSelect = document.getElementById('corregimiento');
                                    corregimientoSelect.innerHTML = '<option value="" disabled>Seleccione un corregimiento</option>';
                                    data.forEach(corregimiento => {
                                        const selected = corregimiento.codigo_corregimiento == '<?php echo $empleadoData['corregimiento']; ?>' ? 'selected' : '';
                                        corregimientoSelect.innerHTML += `<option value="${corregimiento.codigo_corregimiento}" ${selected}>${corregimiento.nombre_corregimiento}</option>`;
                                    });
                                });
                        }
                    });
            }
            // Cargar cargos y seleccionar el correcto
            if ('<?php echo $empleadoData['departamento']; ?>') {
                fetch(`ajaxHandler.php?departamento=<?php echo $empleadoData['departamento']; ?>`)
                    .then(response => response.json())
                    .then(data => {
                        const cargoSelect = document.getElementById('cargo');
                        cargoSelect.innerHTML = '<option value="" disabled>Seleccione un cargo</option>';
                        data.forEach(cargo => {
                            const selected = cargo.codigo == '<?php echo $empleadoData['cargo']; ?>' ? 'selected' : '';
                            cargoSelect.innerHTML += `<option value="${cargo.codigo}" ${selected}>${cargo.nombre}</option>`;
                        });
                    });
            }
            <?php endif; ?>
        });

        function cargarDistritos(provinciaId) {
            if (!provinciaId) return;

            fetch(`ajaxHandler.php?provincia=${provinciaId}`)
                .then(response => response.json())
                .then(data => {
                    const distritoSelect = document.getElementById('distrito');
                    distritoSelect.innerHTML = '<option value="" disabled selected>Seleccione un distrito</option>';
                    data.forEach(distrito => {
                        distritoSelect.innerHTML += `<option value="${distrito.codigo_distrito}">${distrito.nombre_distrito}</option>`;
                    });

                    // Limpiar corregimientos al cambiar de provincia
                    const corregimientoSelect = document.getElementById('corregimiento');
                    corregimientoSelect.innerHTML = '<option value="" disabled selected>Seleccione un corregimiento</option>';
                })
                .catch(error => console.error('Error al cargar los distritos:', error));
        }

        function cargarCorregimientos(distritoId) {
            if (!distritoId) return;

            fetch(`ajaxHandler.php?distrito=${distritoId}`)
                .then(response => response.json())
                .then(data => {
                    const corregimientoSelect = document.getElementById('corregimiento');
                    corregimientoSelect.innerHTML = '<option value="" disabled selected>Seleccione un corregimiento</option>';
                    data.forEach(corregimiento => {
                        corregimientoSelect.innerHTML += `<option value="${corregimiento.codigo_corregimiento}">${corregimiento.nombre_corregimiento}</option>`;
                    });
                })
                .catch(error => console.error('Error al cargar los corregimientos:', error));
        }

        function cargarCargos(departamentoId) {
            if (!departamentoId) return;

            fetch(`ajaxHandler.php?departamento=${departamentoId}`)
                .then(response => response.json())
                .then(data => {
                    const cargoSelect = document.getElementById('cargo');
                    cargoSelect.innerHTML = '<option value="" disabled selected>Seleccione un cargo</option>';
                    data.forEach(cargo => {
                        cargoSelect.innerHTML += `<option value="${cargo.codigo}">${cargo.nombre}</option>`;
                    });
                })
                .catch(error => console.error('Error al cargar los cargos:', error));
        }
    </script>
</body>
</html>