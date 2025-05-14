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
    
    // Gestión de selección en tabla de eliminados (delegación de eventos para checkboxes dinámicos)
    const deletedTableBody = document.querySelector('#deleted-employees-table tbody');
    const deletedSelectAll = document.getElementById('deleted-select-all');
    const deletedActionButtons = document.querySelectorAll('#deleted-btn-view, #btn-restore, #btn-delete-permanent');

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

    if (deletedTableBody) {
        deletedTableBody.addEventListener('change', function(e) {
            if (e.target.classList.contains('deleted-row-checkbox')) {
                updateDeletedActionButtons();
            }
        });
    }
    if (deletedSelectAll) {
        deletedSelectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.deleted-row-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeletedActionButtons();
        });
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
            const selectedRow = document.querySelector('.row-checkbox:checked').closest('tr');
            if (selectedRow) {
                // Obtener los datos del empleado de los atributos de la fila seleccionada
                const employeeId = selectedRow.getAttribute('data-id');
                const employeeName = `${selectedRow.getAttribute('data-nombre')} ${selectedRow.getAttribute('data-apellido')}`;
                const employeeDepartment = selectedRow.getAttribute('data-departamento');
                const employeePosition = selectedRow.getAttribute('data-cargo');
                const employeeHireDate = selectedRow.getAttribute('data-fecha');
                const employeeStatus = selectedRow.getAttribute('data-estado');

                // Asignar los datos al modal
                document.getElementById('employee-id').textContent = employeeId;
                document.getElementById('employee-name').textContent = employeeName;
                document.getElementById('employee-department').textContent = employeeDepartment;
                document.getElementById('employee-position').textContent = employeePosition;
                document.getElementById('employee-hire-date').textContent = employeeHireDate;
                document.getElementById('employee-status').textContent = employeeStatus;

                // Abrir el modal
                openModal(viewEmployeeModal);
            }
        });
    }
    
    // Botón Ver en eliminados
    const deletedViewButton = document.getElementById('deleted-btn-view');
    if (deletedViewButton) {
        deletedViewButton.addEventListener('click', function() {
            const selectedRow = document.querySelector('.deleted-row-checkbox:checked')?.closest('tr');
            if (selectedRow) {
                // Obtener los datos del empleado de las celdas de la fila seleccionada
                const cells = selectedRow.querySelectorAll('td');
                const employeeId = cells[1]?.textContent || '';
                const employeeName = `${cells[2]?.textContent || ''} ${cells[3]?.textContent || ''}`.trim();
                const employeeDepartment = cells[4]?.textContent || '';
                const employeePosition = cells[5]?.textContent || '';
                const employeeHireDate = cells[6]?.textContent || '';
                const employeeStatus = 'Eliminado';

                // Asignar los datos al modal
                document.getElementById('employee-id').textContent = employeeId;
                document.getElementById('employee-name').textContent = employeeName;
                document.getElementById('employee-department').textContent = employeeDepartment;
                document.getElementById('employee-position').textContent = employeePosition;
                document.getElementById('employee-hire-date').textContent = employeeHireDate;
                document.getElementById('employee-status').textContent = employeeStatus;

                openModal(viewEmployeeModal);
            }
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
            const selectedRows = document.querySelectorAll('#employees-table tbody input[type="checkbox"]:checked');
            const employeeIds = Array.from(selectedRows).map(row => row.closest('tr').dataset.id);

            if (employeeIds.length > 0) {
                fetch('ajaxHandler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ action: 'deleteEmployees', employeeIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registro(s) eliminado(s) correctamente');

                        // Eliminar filas seleccionadas de la tabla de empleados
                        selectedRows.forEach(row => row.closest('tr').remove());

                        // Actualizar la tabla de eliminados
                        updateDeletedEmployeesTable();
                    } else {
                        alert('Error al eliminar los registros.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud.');
                });
            } else {
                alert('Por favor, seleccione al menos un registro para eliminar.');
            }

            closeModal(deleteConfirmModal);
        });
    }
    
    // Botón confirmar restaurar
    const confirmRestoreBtn = document.getElementById('confirm-restore');
    if (confirmRestoreBtn) {
        confirmRestoreBtn.addEventListener('click', function() {
            // Obtener cédulas seleccionadas en la tabla de eliminados
            const selectedRows = document.querySelectorAll('#deleted-employees-table tbody input.deleted-row-checkbox:checked');
            const employeeIds = Array.from(selectedRows).map(row => row.closest('tr').children[1].textContent.trim());
            if (employeeIds.length > 0) {
                fetch('ajaxHandler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'restoreEmployees', employeeIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registro(s) restaurado(s) correctamente');
                        // Actualizar ambas tablas
                        updateDeletedEmployeesTable();
                        location.reload(); // Para refrescar la tabla de empleados activos
                    } else {
                        alert('Error al restaurar los registros.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud.');
                });
            } else {
                alert('Por favor, seleccione al menos un registro para restaurar.');
            }
            closeModal(restoreConfirmModal);
        });
    }
    
    // Botón Eliminar Permanente
    const deletePermanentButton = document.getElementById('btn-delete-permanent');
    if (deletePermanentButton) {
        deletePermanentButton.addEventListener('click', function() {
            if (!confirm('¿Está seguro que desea eliminar permanentemente el(los) registro(s) seleccionado(s)? Esta acción no se puede deshacer.')) return;
            const selectedRows = document.querySelectorAll('#deleted-employees-table tbody input.deleted-row-checkbox:checked');
            const employeeIds = Array.from(selectedRows).map(row => row.closest('tr').children[1].textContent.trim());
            if (employeeIds.length > 0) {
                fetch('ajaxHandler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'deletePermanent', employeeIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registro(s) eliminado(s) permanentemente');
                        updateDeletedEmployeesTable();
                    } else {
                        alert('Error al eliminar permanentemente los registros.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud.');
                });
            } else {
                alert('Por favor, seleccione al menos un registro para eliminar.');
            }
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
    
    function updateDeletedEmployeesTable() {
        fetch('ajaxHandler.php?action=getDeletedEmployees')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#deleted-employees-table tbody');
                tableBody.innerHTML = '';

                data.forEach(employee => {
                    // Formatear la fecha a dd/mm/yyyy
                    let fecha = '';
                    if (employee.f_contra) {
                        const dateObj = new Date(employee.f_contra);
                        if (!isNaN(dateObj.getTime())) {
                            const day = String(dateObj.getDate()).padStart(2, '0');
                            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                            const year = dateObj.getFullYear();
                            fecha = `${day}/${month}/${year}`;
                        } else {
                            fecha = employee.f_contra;
                        }
                    }
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><input type="checkbox" class="deleted-row-checkbox"></td>
                        <td>${employee.cedula}</td>
                        <td>${employee.nombre1}</td>
                        <td>${employee.apellido1}</td>
                        <td>${employee.departamento}</td>
                        <td>${employee.cargo}</td>
                        <td>${fecha}</td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error al actualizar la tabla de eliminados:', error);
            });
    }
    
    // Llamar a la función para cargar la tabla de eliminados al inicio
    updateDeletedEmployeesTable();
    
    // Actualizar la tabla de eliminados cada 5 segundos
    setInterval(updateDeletedEmployeesTable, 5000);
});