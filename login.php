<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="styles/loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <!-- Esta es la imagen de fondo -->
        </div>
        <div class="login-form-container">
            <div class="login-header">
                <i class="fas fa-user-circle"></i>
                <h1>Inicio de Sesión</h1>
                <p>Ingrese sus credenciales para acceder al sistema</p>
            </div>
            
            <form id="loginForm" method="POST" action="processLogin.php">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i>
                        Usuario:
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Ingrese su nombre de usuario" 
                        required 
                        pattern="[0-9\-]+" 
                        maxlength="12" 
                        title="Solo se permiten números y guiones, con un máximo de 12 caracteres"
                        oninput="filterUsernameInput(this)">
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Contraseña:
                    </label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </button>
            </form>
            
            <div class="login-footer">
                <p>&copy; 2025 Sistema Corporativo. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>

    <script>
        function showError(message) {
            // Verificar si ya existe un mensaje de error
            let errorElement = document.querySelector('.error-message');
            
            if (!errorElement) {
                // Crear el elemento de error si no existe
                errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                
                // Insertar antes del botón
                const loginButton = document.querySelector('.btn-login');
                loginButton.parentNode.insertBefore(errorElement, loginButton);
            }
            
            // Establecer el mensaje y mostrar con animación
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            
            // Hacer que el mensaje desaparezca después de 3 segundos
            setTimeout(() => {
                errorElement.style.opacity = '0';
                setTimeout(() => {
                    errorElement.style.display = 'none';
                    errorElement.style.opacity = '1';
                }, 300);
            }, 3000);
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function filterUsernameInput(input) {
            input.value = input.value.replace(/[^0-9\-]/g, ''); // Elimina cualquier carácter que no sea número o guión
        }
    </script>
</body>
</html>