document.addEventListener('DOMContentLoaded', function() {
    // Navegación del sidebar
    const sidebarItems = document.querySelectorAll('.sidebar-menu li[data-section]');
    const sections = document.querySelectorAll('section');
    
    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            const sectionId = this.getAttribute('data-section');
            
            // Actualizar clases activas en el sidebar
            sidebarItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Mostrar la sección correspondiente
            sections.forEach(section => {
                section.classList.remove('active');
                if (section.id === sectionId) {
                    section.classList.add('active');
                }
            });
            
            // En móvil, cerrar el sidebar después de seleccionar
            if (window.innerWidth <= 768) {
                document.querySelector('.sidebar').classList.remove('active');
            }
        });
    });
    
    // Toggle del sidebar
    const toggleSidebarBtn = document.getElementById('toggle-sidebar');
    const sidebar = document.querySelector('.sidebar');
    
    toggleSidebarBtn.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('active');
        } else {
            sidebar.classList.toggle('collapsed');
            
            // Ajustar el margen del contenido principal
            const mainContent = document.querySelector('.main-content');
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = `${70}px`;
            } else {
                mainContent.style.marginLeft = `${250}px`;
            }
        }
    });
    
    // Gestión de selección de filas en tablas
    const selectAllCheckbox = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const actionButtons = document.querySelectorAll('#btn-view, #btn-edit, #btn-delete');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateActionButtons();
        });
    }
    
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateActionButtons);
    });
    
    function updateActionButtons() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        
        actionButtons.forEach(button => {
            button.disabled = checkedCount === 0;
        });
        
        // Si solo hay uno seleccionado, habilitar todos los botones
        // Si hay más de uno, solo habilitar el botón de eliminar
        if (checkedCount === 1) {
            actionButtons.forEach(button => {
                button.disabled = false;
            });
        } else if (checkedCount > 1) {
            document.getElementById('btn-view').disabled = true;
            document.getElementById('btn-edit').disabled = true;
            document.getElementById('btn-delete').disabled = false;
        }
    }
    
    // Gestión de selección en tabla de eliminados
    const deletedSelectAll = document.getElementById('deleted-select-all');
    const deletedRowCheckboxes = document.querySelectorAll('.deleted-row-checkbox');
    const deletedActionButtons = document.querySelectorAll('#deleted-btn-view, #btn-restore, #btn-delete-permanent');
    
    if (deletedSelectAll) {
        deletedSelectAll.addEventListener('change', function() {
            deletedRowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeletedActionButtons();
        });
    }
    
    deletedRowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateDeletedActionButtons);
    });
    
    function updateDeletedActionButtons() {
        const checkedCount = document.querySelectorAll('.deleted-row-checkbox:checked').length;
        
        deletedActionButtons.forEach(button => {
            button.disabled = checkedCount === 0;
        });
        
        if (checkedCount === 1) {
            deletedActionButtons.forEach(button => {
                button.disabled = false;
            });
        } else if (checkedCount > 1) {
            document.getElementById('deleted-btn-view').disabled = true;
            document.getElementById('btn-restore').disabled = false;
            document.getElementById('btn-delete-permanent').disabled = false;
        }
    }
    
    // Modales
    const viewEmployeeModal = document.getElementById('view-employee-modal');
    const deleteConfirmModal = document.getElementById('delete-confirm-modal');
    const restoreConfirmModal = document.getElementById('restore-confirm-modal');
    const closeModalButtons = document.querySelectorAll('.close-modal');
    
    // Botón Ver
    const viewButton = document.getElementById('btn-view');
    if (viewButton) {
        viewButton.addEventListener('click', function() {
            openModal(viewEmployeeModal);
        });
    }
    
    // Botón Ver en eliminados
    const deletedViewButton = document.getElementById('deleted-btn-view');
    if (deletedViewButton) {
        deletedViewButton.addEventListener('click', function() {
            openModal(viewEmployeeModal);
        });
    }
    
    // Botón Eliminar
    const deleteButton = document.getElementById('btn-delete');
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            openModal(deleteConfirmModal);
        });
    }
    
    // Botón Restaurar
    const restoreButton = document.getElementById('btn-restore');
    if (restoreButton) {
        restoreButton.addEventListener('click', function() {
            openModal(restoreConfirmModal);
        });
    }
    
    // Cerrar modales
    closeModalButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            closeModal(modal);
        });
    });
    
    // Botón cerrar en modal de ver empleado
    const closeViewModalBtn = document.getElementById('close-view-modal');
    if (closeViewModalBtn) {
        closeViewModalBtn.addEventListener('click', function() {
            closeModal(viewEmployeeModal);
        });
    }
    
    // Botón cancelar en modal de eliminar
    const cancelDeleteBtn = document.getElementById('cancel-delete');
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', function() {
            closeModal(deleteConfirmModal);
        });
    }
    
    // Botón cancelar en modal de restaurar
    const cancelRestoreBtn = document.getElementById('cancel-restore');
    if (cancelRestoreBtn) {
        cancelRestoreBtn.addEventListener('click', function() {
            closeModal(restoreConfirmModal);
        });
    }
    
    // Botón confirmar eliminar
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            // Aquí iría la lógica para eliminar los registros
            // Por ahora, solo cerramos el modal y mostramos un mensaje
            closeModal(deleteConfirmModal);
            alert('Registro(s) eliminado(s) correctamente');
            
            // Cambiar a la sección de eliminados
            const eliminadosItem = document.querySelector('.sidebar-menu li[data-section="eliminados"]');
            eliminadosItem.click();
        });
    }
    
    // Botón confirmar restaurar
    const confirmRestoreBtn = document.getElementById('confirm-restore');
    if (confirmRestoreBtn) {
        confirmRestoreBtn.addEventListener('click', function() {
            // Aquí iría la lógica para restaurar los registros
            closeModal(restoreConfirmModal);
            alert('Registro(s) restaurado(s) correctamente');
            
            // Cambiar a la sección de empleados
            const empleadosItem = document.querySelector('.sidebar-menu li[data-section="empleados"]');
            empleadosItem.click();
        });
    }
    
    // Funciones para abrir y cerrar modales
    function openModal(modal) {
        modal.classList.add('active');
    }
    
    function closeModal(modal) {
        modal.classList.remove('active');
    }
    
    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target);
        }
    });
    
    // Ajustar el layout en carga inicial
    if (window.innerWidth > 768) {
        document.querySelector('.main-content').style.marginLeft = `${250}px`;
    }
    
    // Responsive
    window.addEventListener('resize', function() {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('collapsed');
            document.querySelector('.main-content').style.marginLeft = '0';
        } else {
            if (sidebar.classList.contains('collapsed')) {
                document.querySelector('.main-content').style.marginLeft = `${70}px`;
            } else {
                document.querySelector('.main-content').style.marginLeft = `${250}px`;
            }
        }
    });
});