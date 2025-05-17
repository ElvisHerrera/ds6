<?php
include 'conexion.php'; // Incluye la conexión a la base de datos
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
            <h1>Sistema de Registro de Empleados</h1>
            <p>Complete el formulario con la información requerida</p>
        </header>

        <form action="/submit.php" method="post">
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
                            <select id="Cedula" name="Cedula" required>
                                <option value="" disabled selected></option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tomo">Tomo:</label>
                            <input type="text" id="tomo" name="tomo" maxlength="4" required>
                        </div>
                        <div class="form-group">
                            <label for="asiento">Asiento:</label>
                            <input type="text" id="asiento" name="asiento" maxlength="5" required>
                        </div>
                    </div>

                    <div class="form-row two-columns">
                        <div class="form-group">
                            <label for="first-name">Primer Nombre:</label>
                            <input type="text" id="first-name" name="first-name" required>
                        </div>
                        <div class="form-group">
                            <label for="second-name">Segundo Nombre:</label>
                            <input type="text" id="second-name" name="second-name">
                        </div>
                    </div>

                    <div class="form-row three-columns">
                        <div class="form-group">
                            <label for="first-lastname">Primer Apellido:</label>
                            <input type="text" id="first-lastname" name="first-lastname" required>
                        </div>
                        <div class="form-group">
                            <label for="second-lastname">Segundo Apellido:</label>
                            <input type="text" id="second-lastname" name="second-lastname">
                        </div>
                        <div class="form-group">
                            <label for="genero">Género:</label>
                            <select id="genero" name="genero" required>
                                <option value="" disabled selected></option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row three-columns">
                        <div class="form-group checkbox-group">
                            <label for="apellidocheck">¿Usa apellido de casada?</label>
                            <input type="checkbox" id="apellidocheck" name="apellidocheck">
                        </div>
                        <div class="form-group">
                            <label for="apellidoCasada">Apellido de casada:</label>
                            <input type="text" id="apellidoCasada" name="apellidoCasada" disabled>
                        </div>
                        <div class="form-group">
                            <label for="estadoCivil">Estado civil:</label>
                            <select id="estadoCivil" name="estadoCivil" required>
                                <option value="" disabled selected></option>
                                <option value="soltero">Soltero</option>
                                <option value="casado">Casado</option>
                                <option value="divorciado">Divorciado</option>
                                <option value="viudo">Viudo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row three-columns">
                        <div class="form-group">
                            <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                            <input type="date" id="fechaNacimiento" name="fechaNacimiento" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo_sangre">Tipo de Sangre:</label>
                            <select id="tipo_sangre" name="tipo_sangre" required>
                                <option value="" disabled selected></option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nacionalidad">Nacionalidad:</label>
                            <select id="nacionalidad" name="nacionalidad" required>
                                <option value="" disabled>Seleccione una nacionalidad</option>
                                <?php
                                $query = "SELECT codigo, pais FROM nacionalidad ORDER BY pais";
                                $result = $conexion->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    $selected = (strtolower($row['pais']) === 'panamá') ? 'selected' : '';
                                    echo "<option value='{$row['codigo']}' $selected>{$row['pais']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row two-columns">
                        <div class="form-group">
                            <label for="Celular">Celular:</label>
                            <input type="tel" id="Celular" name="Celular" maxlength="8" pattern="\d{8}" title="El número de celular debe tener exactamente 8 dígitos" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="tel" id="telefono" name="telefono" maxlength="7" pattern="\d{7}" title="El número de teléfono debe tener exactamente 7 dígitos" required>
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
                                <option value="" disabled selected>Seleccione una provincia</option>
                                <?php
                                $query = "SELECT codigo_provincia, nombre_provincia FROM provincia";
                                $result = $conexion->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['codigo_provincia']}'>{$row['nombre_provincia']}</option>";
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
                            <input type="text" id="calle" name="calle" required>
                        </div>
                        <div class="form-group">
                            <label for="casa">Casa:</label>
                            <input type="text" id="casa" name="casa" required>
                        </div>
                        <div class="form-group">
                            <label for="comunidad">Comunidad:</label>
                            <input type="text" id="comunidad" name="comunidad">
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
                        <input type="date" id="fechadecontratacion" name="fechadecontratacion" required>
                    </div>

                    <div class="form-row two-columns">
                        <div class="form-group">
                            <label for="departamento">Departamento:</label>
                            <select id="departamento" name="departamento" required onchange="cargarCargos(this.value)">
                                <option value="" disabled selected>Seleccione un departamento</option>
                                <?php
                                $query = "SELECT codigo, nombre FROM departamento";
                                $result = $conexion->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['codigo']}'>{$row['nombre']}</option>";
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
                            <input type="email" id="correo" name="correo" required>
                        </div>
                        <div class="form-group">
                            <label for="contrasena">Contraseña:</label>
                            <input type="password" id="contrasena" name="contrasena" required>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <select id="estado" name="estado" required>
                                <option value="" disabled selected></option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>   
                        </div>
                        <div class="form-group">
                            <label for="validar_contraseña">Confirmar Contraseña:</label>
                            <input type="password" id="validar_contraseña" name="validar_contraseña" required>
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

            // Validación personalizada antes de enviar el formulario
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                const firstNameInput = document.getElementById('first-name');
                const firstLastNameInput = document.getElementById('first-lastname');
                const firstName = firstNameInput.value.trim();
                const firstLastName = firstLastNameInput.value.trim();
                // Contar solo letras (ignorando espacios)
                const firstNameLetters = firstName.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ]/g, '');
                const firstLastNameLetters = firstLastName.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ]/g, '');
                let valid = true;
                let message = '';
                if (firstNameLetters.length < 2) {
                    valid = false;
                    message += 'El primer nombre debe tener al menos 2 letras.\n';
                }
                if (firstLastNameLetters.length < 2) {
                    valid = false;
                    message += 'El primer apellido debe tener al menos 2 letras.';
                }
                if (!valid) {
                    alert(message);
                    event.preventDefault();
                    // Opcional: enfocar el primer campo inválido
                    if (firstNameLetters.length < 2) {
                        firstNameInput.focus();
                    } else if (firstLastNameLetters.length < 2) {
                        firstLastNameInput.focus();
                    }
                } else {
                    // Limpiar espacios antes de enviar
                    firstNameInput.value = firstName;
                    firstLastNameInput.value = firstLastName;
                }
            });
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