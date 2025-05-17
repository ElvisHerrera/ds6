document.addEventListener('DOMContentLoaded', function() {
    // Navegación del menú lateral
    const menuItems = document.querySelectorAll('.menu-item');
    const sections = document.querySelectorAll('.info-section');
    
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if (this.getAttribute('href') === 'login.php') {
                return; // Permitir que el enlace de cierre de sesión funcione normalmente
            }
            
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            
            // Actualizar clases activas en el menú
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Mostrar la sección correspondiente
            sections.forEach(section => {
                section.classList.remove('active');
                if (section.id === targetId) {
                    section.classList.add('active');
                }
            });
            
            // En móvil, cerrar el sidebar después de seleccionar
            if (window.innerWidth <= 768) {
                document.querySelector('.sidebar').classList.remove('active');
            }
        });
    });
    
    // Toggle del sidebar en móvil
    const toggleSidebarBtn = document.getElementById('toggle-sidebar');
    const sidebar = document.querySelector('.sidebar');
    
    if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
});