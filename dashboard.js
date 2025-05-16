document.addEventListener("DOMContentLoaded", () => {
  // Configuración de paginación
  const ITEMS_PER_PAGE = 10
  let currentPageEmployees = 1
  let currentPageDeleted = 1
  let totalPagesEmployees = 1
  let totalPagesDeleted = 1

  // Navegación del sidebar
  const sidebarItems = document.querySelectorAll(".sidebar-menu li[data-section]")
  const sections = document.querySelectorAll("section")

  sidebarItems.forEach((item) => {
    item.addEventListener("click", function () {
      const sectionId = this.getAttribute("data-section")

      // Actualizar clases activas en el sidebar
      sidebarItems.forEach((i) => i.classList.remove("active"))
      this.classList.add("active")

      // Mostrar la sección correspondiente
      sections.forEach((section) => {
        section.classList.remove("active")
        if (section.id === sectionId) {
          section.classList.add("active")
        }
      })

      // En móvil, cerrar el sidebar después de seleccionar
      if (window.innerWidth <= 768) {
        document.querySelector(".sidebar").classList.remove("active")
      }
    })
  })

  // Gestión de selección de filas en tablas
  const selectAllCheckbox = document.getElementById("select-all")
  const rowCheckboxes = document.querySelectorAll(".row-checkbox")
  const actionButtons = document.querySelectorAll("#btn-view, #btn-edit, #btn-delete")

  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener("change", function () {
      const visibleRows = Array.from(document.querySelectorAll("#employees-table tbody tr")).filter(
        (row) => row.style.display !== "none",
      )
      const checkboxes = visibleRows.map((row) => row.querySelector(".row-checkbox"))

      checkboxes.forEach((checkbox) => {
        if (checkbox) checkbox.checked = this.checked
      })
      updateActionButtons()
    })
  }

  document.querySelector("#employees-table tbody").addEventListener("change", (e) => {
    if (e.target.classList.contains("row-checkbox")) {
      updateActionButtons()
    }
  })

  function updateActionButtons() {
    const checkedCount = document.querySelectorAll(
      '#employees-table tbody tr:not([style*="display: none"]) .row-checkbox:checked',
    ).length

    actionButtons.forEach((button) => {
      button.disabled = checkedCount === 0
    })

    // Si solo hay uno seleccionado, habilitar todos los botones
    // Si hay más de uno, solo habilitar el botón de eliminar
    if (checkedCount === 1) {
      actionButtons.forEach((button) => {
        button.disabled = false
      })
    } else if (checkedCount > 1) {
      document.getElementById("btn-view").disabled = true
      document.getElementById("btn-edit").disabled = true
      document.getElementById("btn-delete").disabled = false
    }
  }

  // Gestión de selección en tabla de eliminados (delegación de eventos para checkboxes dinámicos)
  const deletedTableBody = document.querySelector("#deleted-employees-table tbody")
  const deletedSelectAll = document.getElementById("deleted-select-all")
  const deletedActionButtons = document.querySelectorAll("#deleted-btn-view, #btn-restore, #btn-delete-permanent")

  function updateDeletedActionButtons() {
    const checkedCount = document.querySelectorAll(
      '#deleted-employees-table tbody tr:not([style*="display: none"]) .deleted-row-checkbox:checked',
    ).length
    deletedActionButtons.forEach((button) => {
      button.disabled = checkedCount === 0
    })
    if (checkedCount === 1) {
      deletedActionButtons.forEach((button) => {
        button.disabled = false
      })
    } else if (checkedCount > 1) {
      document.getElementById("deleted-btn-view").disabled = true
      document.getElementById("btn-restore").disabled = false
      document.getElementById("btn-delete-permanent").disabled = false
    }
  }

  if (deletedTableBody) {
    deletedTableBody.addEventListener("change", (e) => {
      if (e.target.classList.contains("deleted-row-checkbox")) {
        updateDeletedActionButtons()
      }
    })
  }
  if (deletedSelectAll) {
    deletedSelectAll.addEventListener("change", function () {
      const visibleRows = Array.from(document.querySelectorAll("#deleted-employees-table tbody tr")).filter(
        (row) => row.style.display !== "none",
      )
      const checkboxes = visibleRows.map((row) => row.querySelector(".deleted-row-checkbox"))

      checkboxes.forEach((checkbox) => {
        if (checkbox) checkbox.checked = this.checked
      })
      updateDeletedActionButtons()
    })
  }

  // Modales
  const viewEmployeeModal = document.getElementById("view-employee-modal")
  const deleteConfirmModal = document.getElementById("delete-confirm-modal")
  const restoreConfirmModal = document.getElementById("restore-confirm-modal")
  const closeModalButtons = document.querySelectorAll(".close-modal")

  // --- MODAL DE NOTIFICACIÓN ---
  function showNotificationModal(message, title = "Notificación") {
    const notificationModal = document.getElementById("notification-modal")
    const notificationTitle = document.getElementById("notification-title")
    const notificationMessage = document.getElementById("notification-message")
    notificationTitle.textContent = title
    notificationMessage.textContent = message
    openModal(notificationModal)
  }

  // Cerrar modal de notificación
  const closeNotificationModalBtn = document.getElementById("close-notification-modal")
  const okNotificationModalBtn = document.getElementById("ok-notification-modal")
  const notificationModal = document.getElementById("notification-modal")
  if (closeNotificationModalBtn) {
    closeNotificationModalBtn.addEventListener("click", () => closeModal(notificationModal))
  }
  if (okNotificationModalBtn) {
    okNotificationModalBtn.addEventListener("click", () => closeModal(notificationModal))
  }

  // Botón Ver
  const viewButton = document.getElementById("btn-view")
  if (viewButton) {
    viewButton.addEventListener("click", () => {
      const selectedRow = document
        .querySelector('#employees-table tbody tr:not([style*="display: none"]) .row-checkbox:checked')
        .closest("tr")
      if (selectedRow) {
        // Obtener los datos del empleado de los atributos de la fila seleccionada
        const employeeId = selectedRow.getAttribute("data-id")
        const employeeName = `${selectedRow.getAttribute("data-nombre")} ${selectedRow.getAttribute("data-apellido")}`
        const employeeDepartment = selectedRow.getAttribute("data-departamento")
        const employeePosition = selectedRow.getAttribute("data-cargo")
        const employeeHireDate = selectedRow.getAttribute("data-fecha")
        const employeeStatus = selectedRow.getAttribute("data-estado")

        // Asignar los datos al modal
        document.getElementById("employee-id").textContent = employeeId
        document.getElementById("employee-name").textContent = employeeName
        document.getElementById("employee-department").textContent = employeeDepartment
        document.getElementById("employee-position").textContent = employeePosition
        document.getElementById("employee-hire-date").textContent = employeeHireDate
        document.getElementById("employee-status").textContent = employeeStatus

        // Abrir el modal
        openModal(viewEmployeeModal)
      }
    })
  }

  // Botón Ver en eliminados
  const deletedViewButton = document.getElementById("deleted-btn-view")
  if (deletedViewButton) {
    deletedViewButton.addEventListener("click", () => {
      const selectedRow = document
        .querySelector('#deleted-employees-table tbody tr:not([style*="display: none"]) .deleted-row-checkbox:checked')
        ?.closest("tr")
      if (selectedRow) {
        // Obtener los datos del empleado de las celdas de la fila seleccionada
        const cells = selectedRow.querySelectorAll("td")
        const employeeId = cells[1]?.textContent || ""
        const employeeName = `${cells[2]?.textContent || ""} ${cells[3]?.textContent || ""}`.trim()
        const employeeDepartment = cells[4]?.textContent || ""
        const employeePosition = cells[5]?.textContent || ""
        const employeeHireDate = cells[6]?.textContent || ""
        const employeeStatus = "Eliminado"

        // Asignar los datos al modal
        document.getElementById("employee-id").textContent = employeeId
        document.getElementById("employee-name").textContent = employeeName
        document.getElementById("employee-department").textContent = employeeDepartment
        document.getElementById("employee-position").textContent = employeePosition
        document.getElementById("employee-hire-date").textContent = employeeHireDate
        document.getElementById("employee-status").textContent = employeeStatus

        openModal(viewEmployeeModal)
      }
    })
  }

  // Botón Eliminar
  const deleteButton = document.getElementById("btn-delete")
  if (deleteButton) {
    deleteButton.addEventListener("click", () => {
      openModal(deleteConfirmModal)
    })
  }

  // Botón Editar
  const editButton = document.getElementById("btn-edit")
  if (editButton) {
    editButton.addEventListener("click", () => {
      const selectedRow = document.querySelector(
        '#employees-table tbody tr:not([style*="display: none"]) .row-checkbox:checked',
      )
      if (selectedRow) {
        const tr = selectedRow.closest("tr")
        const cedula = tr.getAttribute("data-id")
        if (cedula) {
          window.location.href = `EditarFormulario.php?cedula=${encodeURIComponent(cedula)}`
        }
      }
    })
  }

  // Botón Restaurar
  const restoreButton = document.getElementById("btn-restore")
  if (restoreButton) {
    restoreButton.addEventListener("click", () => {
      openModal(restoreConfirmModal)
    })
  }

  // Cerrar modales
  closeModalButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const modal = this.closest(".modal")
      closeModal(modal)
    })
  })

  // Botón cerrar en modal de ver empleado
  const closeViewModalBtn = document.getElementById("close-view-modal")
  if (closeViewModalBtn) {
    closeViewModalBtn.addEventListener("click", () => {
      closeModal(viewEmployeeModal)
    })
  }

  // Botón cancelar en modal de eliminar
  const cancelDeleteBtn = document.getElementById("cancel-delete")
  if (cancelDeleteBtn) {
    cancelDeleteBtn.addEventListener("click", () => {
      closeModal(deleteConfirmModal)
    })
  }

  // Botón cancelar en modal de restaurar
  const cancelRestoreBtn = document.getElementById("cancel-restore")
  if (cancelRestoreBtn) {
    cancelRestoreBtn.addEventListener("click", () => {
      closeModal(restoreConfirmModal)
    })
  }

  // Botón confirmar eliminar
  const confirmDeleteBtn = document.getElementById("confirm-delete")
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener("click", () => {
      const selectedRows = document.querySelectorAll(
        '#employees-table tbody tr:not([style*="display: none"]) .row-checkbox:checked',
      )
      const employeeIds = Array.from(selectedRows).map((row) => row.closest("tr").dataset.id)

      if (employeeIds.length > 0) {
        fetch("ajaxHandler.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ action: "deleteEmployees", employeeIds }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              showNotificationModal("Registro(s) eliminado(s) correctamente", "Eliminación exitosa")

              // Eliminar filas seleccionadas de la tabla de empleados
              selectedRows.forEach((row) => row.closest("tr").remove())

              // Actualizar la tabla de eliminados
              updateDeletedEmployeesTable()

              // Actualizar estadísticas
              updateDashboardStats()

              // Actualizar paginación
              setupPagination()
            } else {
              showNotificationModal("Error al eliminar los registros.", "Error")
            }
          })
          .catch((error) => {
            console.error("Error:", error)
            showNotificationModal("Error al procesar la solicitud.", "Error")
          })
      } else {
        showNotificationModal("Por favor, seleccione al menos un registro para eliminar.", "Atención")
      }

      closeModal(deleteConfirmModal)
    })
  }

  // Botón confirmar restaurar
  const confirmRestoreBtn = document.getElementById("confirm-restore")
  if (confirmRestoreBtn) {
    confirmRestoreBtn.addEventListener("click", () => {
      // Obtener cédulas seleccionadas en la tabla de eliminados
      const selectedRows = document.querySelectorAll(
        '#deleted-employees-table tbody tr:not([style*="display: none"]) .deleted-row-checkbox:checked',
      )
      const employeeIds = Array.from(selectedRows).map((row) => row.closest("tr").children[1].textContent.trim())
      if (employeeIds.length > 0) {
        fetch("ajaxHandler.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ action: "restoreEmployees", employeeIds }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              showNotificationModal("Registro(s) restaurado(s) correctamente", "Restauración exitosa")
              // Actualizar ambas tablas
              updateDeletedEmployeesTable()

              // Actualizar estadísticas
              updateDashboardStats()

              // Recargar la tabla de empleados activos
              setTimeout(() => {
                location.reload()
              }, 1000)
            } else {
              showNotificationModal("Error al restaurar los registros.", "Error")
            }
          })
          .catch((error) => {
            console.error("Error:", error)
            showNotificationModal("Error al procesar la solicitud.", "Error")
          })
      } else {
        showNotificationModal("Por favor, seleccione al menos un registro para restaurar.", "Atención")
      }
      closeModal(restoreConfirmModal)
    })
  }

  // Botón Eliminar Permanente
  const deletePermanentButton = document.getElementById("btn-delete-permanent")
  if (deletePermanentButton) {
    deletePermanentButton.addEventListener("click", () => {
      const selectedRows = document.querySelectorAll(
        '#deleted-employees-table tbody tr:not([style*="display: none"]) .deleted-row-checkbox:checked',
      )
      const employeeIds = Array.from(selectedRows).map((row) => row.closest("tr").children[1].textContent.trim())
      if (employeeIds.length > 0) {
        fetch("ajaxHandler.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ action: "deletePermanent", employeeIds }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              showNotificationModal("Registro(s) eliminado(s) permanentemente", "Eliminación permanente")
              updateDeletedEmployeesTable()

              // Actualizar paginación
              setupPaginationDeleted()
            } else {
              showNotificationModal("Error al eliminar permanentemente los registros.", "Error")
            }
          })
          .catch((error) => {
            console.error("Error:", error)
            showNotificationModal("Error al procesar la solicitud.", "Error")
          })
      } else {
        showNotificationModal("Por favor, seleccione al menos un registro para eliminar.", "Atención")
      }
    })
  }

  // Funciones para abrir y cerrar modales
  function openModal(modal) {
    modal.classList.add("active")
  }

  function closeModal(modal) {
    modal.classList.remove("active")
  }

  // Cerrar modal al hacer clic fuera
  window.addEventListener("click", (event) => {
    if (event.target.classList.contains("modal")) {
      closeModal(event.target)
    }
  })

  // Ajustar el layout en carga inicial
  if (window.innerWidth > 768) {
    document.querySelector(".main-content").style.marginLeft = `${250}px`
  }

  // Responsive
  window.addEventListener("resize", () => {
    if (window.innerWidth <= 768) {
      document.querySelector(".main-content").style.marginLeft = "0"
    } else {
      document.querySelector(".main-content").style.marginLeft = `${250}px`
    }
  })

  // Función para actualizar la tabla de eliminados
  function updateDeletedEmployeesTable() {
    fetch("ajaxHandler.php?action=getDeletedEmployees")
      .then((response) => response.json())
      .then((data) => {
        const tableBody = document.querySelector("#deleted-employees-table tbody")
        tableBody.innerHTML = ""

        data.forEach((employee) => {
          // Formatear la fecha a dd/mm/yyyy
          let fecha = ""
          if (employee.f_contra) {
            const dateObj = new Date(employee.f_contra)
            if (!isNaN(dateObj.getTime())) {
              const day = String(dateObj.getDate()).padStart(2, "0")
              const month = String(dateObj.getMonth() + 1).padStart(2, "0")
              const year = dateObj.getFullYear()
              fecha = `${day}/${month}/${year}`
            } else {
              fecha = employee.f_contra
            }
          }
          const row = document.createElement("tr")
          row.innerHTML = `
                        <td><input type="checkbox" class="deleted-row-checkbox"></td>
                        <td>${employee.cedula}</td>
                        <td>${employee.nombre1}</td>
                        <td>${employee.apellido1}</td>
                        <td>${employee.departamento}</td>
                        <td>${employee.cargo}</td>
                        <td>${fecha}</td>
                    `
          tableBody.appendChild(row)
        })

        // Actualizar paginación después de cargar los datos
        setupPaginationDeleted()
      })
      .catch((error) => {
        console.error("Error al actualizar la tabla de eliminados:", error)
      })
  }

  // Implementar búsqueda en tabla de empleados
  const employeeSearch = document.getElementById("employee-search")
  if (employeeSearch) {
    employeeSearch.addEventListener("input", function () {
      filterEmployeeTable(this.value)
      // Resetear a la primera página después de filtrar
      currentPageEmployees = 1
      applyPaginationEmployees()
    })
  }

  function filterEmployeeTable(searchTerm) {
    const rows = document.querySelectorAll("#employees-table tbody tr")
    const term = searchTerm.toLowerCase()

    rows.forEach((row) => {
      const text = row.textContent.toLowerCase()
      if (text.includes(term)) {
        row.dataset.filtered = "false"
      } else {
        row.dataset.filtered = "true"
      }
    })

    // Actualizar paginación después de filtrar
    setupPagination()
  }

  // Implementar búsqueda en tabla de eliminados
  const deletedEmployeeSearch = document.getElementById("deleted-employee-search")
  if (deletedEmployeeSearch) {
    deletedEmployeeSearch.addEventListener("input", function () {
      filterDeletedEmployeeTable(this.value)
      // Resetear a la primera página después de filtrar
      currentPageDeleted = 1
      applyPaginationDeleted()
    })
  }

  function filterDeletedEmployeeTable(searchTerm) {
    const rows = document.querySelectorAll("#deleted-employees-table tbody tr")
    const term = searchTerm.toLowerCase()

    rows.forEach((row) => {
      const text = row.textContent.toLowerCase()
      if (text.includes(term)) {
        row.dataset.filtered = "false"
      } else {
        row.dataset.filtered = "true"
      }
    })

    // Actualizar paginación después de filtrar
    setupPaginationDeleted()
  }

  // Implementar filtros de departamento y estado
  const departmentFilter = document.getElementById("department-filter")
  const statusFilter = document.getElementById("status-filter")

  if (departmentFilter) {
    departmentFilter.addEventListener("change", () => {
      applyFilters()
      // Resetear a la primera página después de filtrar
      currentPageEmployees = 1
      applyPaginationEmployees()
    })
  }

  if (statusFilter) {
    statusFilter.addEventListener("change", () => {
      applyFilters()
      // Resetear a la primera página después de filtrar
      currentPageEmployees = 1
      applyPaginationEmployees()
    })
  }

  function applyFilters() {
    const departmentValue = departmentFilter.value.toLowerCase()
    const statusValue = statusFilter.value

    const rows = document.querySelectorAll("#employees-table tbody tr")

    rows.forEach((row) => {
      const departmentCell = row.querySelector("td:nth-child(5)").textContent.toLowerCase()
      const statusCell = row.querySelector("td:nth-child(8) span").textContent

      const departmentMatch = !departmentValue || departmentCell.includes(departmentValue)
      const statusMatch = !statusValue || statusCell === statusValue

      if (departmentMatch && statusMatch) {
        row.dataset.filtered = "false"
      } else {
        row.dataset.filtered = "true"
      }
    })

    // Actualizar paginación después de filtrar
    setupPagination()
  }

  // Implementar filtro de departamento en tabla de eliminados
  const deletedDepartmentFilter = document.getElementById("deleted-department-filter")
  if (deletedDepartmentFilter) {
    deletedDepartmentFilter.addEventListener("change", function () {
      const departmentValue = this.value.toLowerCase()
      const rows = document.querySelectorAll("#deleted-employees-table tbody tr")

      rows.forEach((row) => {
        const departmentCell = row.querySelector("td:nth-child(5)")
        if (!departmentCell) return // Previene error si la celda no existe

        const departmentText = departmentCell.textContent.toLowerCase()
        if (!departmentValue || departmentText.includes(departmentValue)) {
          row.dataset.filtered = "false"
        } else {
          row.dataset.filtered = "true"
        }
      })

      // Resetear a la primera página después de filtrar
      currentPageDeleted = 1
      applyPaginationDeleted()
    })
  }

  // Función para configurar la paginación de empleados
  function setupPagination() {
    const rows = document.querySelectorAll("#employees-table tbody tr")
    const filteredRows = Array.from(rows).filter((row) => row.dataset.filtered !== "true")

    totalPagesEmployees = Math.ceil(filteredRows.length / ITEMS_PER_PAGE)

    // Asegurarse de que la página actual no exceda el total de páginas
    if (currentPageEmployees > totalPagesEmployees) {
      currentPageEmployees = totalPagesEmployees > 0 ? totalPagesEmployees : 1
    }

    // Actualizar información de paginación
    const paginationInfo = document.getElementById("pagination-info-employees")
    const start = (currentPageEmployees - 1) * ITEMS_PER_PAGE + 1
    const end = Math.min(start + ITEMS_PER_PAGE - 1, filteredRows.length)

    if (filteredRows.length > 0) {
      paginationInfo.textContent = `Mostrando ${start}-${end} de ${filteredRows.length} registros`
    } else {
      paginationInfo.textContent = "No hay registros para mostrar"
    }

    // Generar botones de paginación
    generatePaginationButtons("pagination-numbers-employees", totalPagesEmployees, currentPageEmployees, (page) => {
      currentPageEmployees = page
      applyPaginationEmployees()
    })

    // Aplicar paginación
    applyPaginationEmployees()
  }

  // Función para configurar la paginación de eliminados
  function setupPaginationDeleted() {
    const rows = document.querySelectorAll("#deleted-employees-table tbody tr")
    const filteredRows = Array.from(rows).filter((row) => row.dataset.filtered !== "true")

    totalPagesDeleted = Math.ceil(filteredRows.length / ITEMS_PER_PAGE)

    // Asegurarse de que la página actual no exceda el total de páginas
    if (currentPageDeleted > totalPagesDeleted) {
      currentPageDeleted = totalPagesDeleted > 0 ? totalPagesDeleted : 1
    }

    // Actualizar información de paginación
    const paginationInfo = document.getElementById("pagination-info-deleted")
    const start = (currentPageDeleted - 1) * ITEMS_PER_PAGE + 1
    const end = Math.min(start + ITEMS_PER_PAGE - 1, filteredRows.length)

    if (filteredRows.length > 0) {
      paginationInfo.textContent = `Mostrando ${start}-${end} de ${filteredRows.length} registros`
    } else {
      paginationInfo.textContent = "No hay registros para mostrar"
    }

    // Generar botones de paginación
    generatePaginationButtons("pagination-numbers-deleted", totalPagesDeleted, currentPageDeleted, (page) => {
      currentPageDeleted = page
      applyPaginationDeleted()
    })

    // Aplicar paginación
    applyPaginationDeleted()
  }

  // Función para generar botones de paginación
  function generatePaginationButtons(containerId, totalPages, currentPage, callback) {
    const container = document.getElementById(containerId)
    container.innerHTML = ""

    // Determinar qué botones mostrar
    let startPage = Math.max(1, currentPage - 2)
    const endPage = Math.min(totalPages, startPage + 4)

    // Ajustar si estamos cerca del final
    if (endPage - startPage < 4 && startPage > 1) {
      startPage = Math.max(1, endPage - 4)
    }

    // Crear botones
    for (let i = startPage; i <= endPage; i++) {
      const button = document.createElement("button")
      button.className = "pagination-btn" + (i === currentPage ? " active" : "")
      button.textContent = i
      button.addEventListener("click", () => callback(i))
      container.appendChild(button)
    }
  }

  // Función para aplicar paginación a la tabla de empleados
  function applyPaginationEmployees() {
    const rows = document.querySelectorAll("#employees-table tbody tr")
    const filteredRows = Array.from(rows).filter((row) => row.dataset.filtered !== "true")

    const startIndex = (currentPageEmployees - 1) * ITEMS_PER_PAGE
    const endIndex = startIndex + ITEMS_PER_PAGE

    // Ocultar todas las filas
    rows.forEach((row) => {
      row.style.display = "none"
    })

    // Mostrar solo las filas de la página actual
    filteredRows.slice(startIndex, endIndex).forEach((row) => {
      row.style.display = ""
    })

    // Actualizar estado de los botones de navegación
    updatePaginationButtons("pagination-employees", currentPageEmployees, totalPagesEmployees)
  }

  // Función para aplicar paginación a la tabla de eliminados
  function applyPaginationDeleted() {
    const rows = document.querySelectorAll("#deleted-employees-table tbody tr")
    const filteredRows = Array.from(rows).filter((row) => row.dataset.filtered !== "true")

    const startIndex = (currentPageDeleted - 1) * ITEMS_PER_PAGE
    const endIndex = startIndex + ITEMS_PER_PAGE

    // Ocultar todas las filas
    rows.forEach((row) => {
      row.style.display = "none"
    })

    // Mostrar solo las filas de la página actual
    filteredRows.slice(startIndex, endIndex).forEach((row) => {
      row.style.display = ""
    })

    // Actualizar estado de los botones de navegación
    updatePaginationButtons("pagination-deleted", currentPageDeleted, totalPagesDeleted)
  }

  // Función para actualizar el estado de los botones de navegación
  function updatePaginationButtons(containerId, currentPage, totalPages) {
    const container = document.getElementById(containerId)
    const firstButton = container.querySelector('[data-action="first"]')
    const prevButton = container.querySelector('[data-action="prev"]')
    const nextButton = container.querySelector('[data-action="next"]')
    const lastButton = container.querySelector('[data-action="last"]')

    // Deshabilitar botones si estamos en la primera o última página
    firstButton.disabled = currentPage === 1
    prevButton.disabled = currentPage === 1
    nextButton.disabled = currentPage === totalPages || totalPages === 0
    lastButton.disabled = currentPage === totalPages || totalPages === 0

    // Actualizar clases para estilos
    firstButton.classList.toggle("disabled", currentPage === 1)
    prevButton.classList.toggle("disabled", currentPage === 1)
    nextButton.classList.toggle("disabled", currentPage === totalPages || totalPages === 0)
    lastButton.classList.toggle("disabled", currentPage === totalPages || totalPages === 0)
  }

  // Configurar eventos para botones de navegación
  document.getElementById("pagination-employees").addEventListener("click", (e) => {
    if (e.target.classList.contains("pagination-btn") && e.target.hasAttribute("data-action")) {
      const action = e.target.getAttribute("data-action")

      switch (action) {
        case "first":
          currentPageEmployees = 1
          break
        case "prev":
          currentPageEmployees = Math.max(1, currentPageEmployees - 1)
          break
        case "next":
          currentPageEmployees = Math.min(totalPagesEmployees, currentPageEmployees + 1)
          break
        case "last":
          currentPageEmployees = totalPagesEmployees
          break
      }

      applyPaginationEmployees()
    }
  })

  document.getElementById("pagination-deleted").addEventListener("click", (e) => {
    if (e.target.classList.contains("pagination-btn") && e.target.hasAttribute("data-action")) {
      const action = e.target.getAttribute("data-action")

      switch (action) {
        case "first":
          currentPageDeleted = 1
          break
        case "prev":
          currentPageDeleted = Math.max(1, currentPageDeleted - 1)
          break
        case "next":
          currentPageDeleted = Math.min(totalPagesDeleted, currentPageDeleted + 1)
          break
        case "last":
          currentPageDeleted = totalPagesDeleted
          break
      }

      applyPaginationDeleted()
    }
  })

  // Función para actualizar las estadísticas del dashboard
  function updateDashboardStats() {
    fetch("ajaxHandler.php?action=getDashboardStats")
      .then((response) => response.json())
      .then((data) => {
        // Actualizar los números en las tarjetas de estadísticas
        const totalEmpleadosElement = document.getElementById("total-empleados")
        const nuevosIngresosElement = document.getElementById("nuevos-ingresos")
        const bajasElement = document.getElementById("bajas")
        const departamentosElement = document.getElementById("departamentos")

        if (totalEmpleadosElement) {
          const oldValue = Number.parseInt(totalEmpleadosElement.textContent)
          totalEmpleadosElement.textContent = data.totalEmpleados
          if (oldValue !== Number.parseInt(data.totalEmpleados)) {
            totalEmpleadosElement.classList.add("updated")
            setTimeout(() => {
              totalEmpleadosElement.classList.remove("updated")
            }, 1000)
          }
        }

        if (nuevosIngresosElement) {
          const oldValue = Number.parseInt(nuevosIngresosElement.textContent)
          nuevosIngresosElement.textContent = data.nuevosIngresos
          if (oldValue !== Number.parseInt(data.nuevosIngresos)) {
            nuevosIngresosElement.classList.add("updated")
            setTimeout(() => {
              nuevosIngresosElement.classList.remove("updated")
            }, 1000)
          }
        }

        if (bajasElement) {
          const oldValue = Number.parseInt(bajasElement.textContent)
          bajasElement.textContent = data.bajas
          if (oldValue !== Number.parseInt(data.bajas)) {
            bajasElement.classList.add("updated")
            setTimeout(() => {
              bajasElement.classList.remove("updated")
            }, 1000)
          }
        }

        if (departamentosElement) {
          departamentosElement.textContent = data.departamentos
        }

        // Actualizar el gráfico de barras
        updateDepartmentChart(data.departamentoData, data.totalEmpleados)
      })
      .catch((error) => {
        console.error("Error al actualizar estadísticas:", error)
      })
  }

  // Función para actualizar el gráfico de barras de departamentos
  function updateDepartmentChart(departamentoData, totalEmpleados) {
    const chartContainer = document.getElementById("chart-departamentos")
    if (!chartContainer) return

    const chartBars = chartContainer.querySelector(".chart-bars")
    chartBars.innerHTML = ""

    departamentoData.forEach((dept) => {
      const height = (dept.total / (totalEmpleados > 0 ? totalEmpleados : 1)) * 100
      const bar = document.createElement("div")
      bar.className = "chart-bar"
      bar.style.height = `${height}%`
      bar.setAttribute("data-label", dept.nombre)
      bar.setAttribute("data-value", dept.total)
      chartBars.appendChild(bar)
    })
  }

  // Función para actualizar la lista de actividad reciente
  function updateActivityList() {
    fetch("ajaxHandler.php?action=getRecentActivity")
      .then((response) => response.json())
      .then((data) => {
        const activityList = document.getElementById("activity-list")
        if (!activityList) return

        activityList.innerHTML = ""

        data.forEach((activity) => {
          const activityItem = document.createElement("div")
          activityItem.className = "activity-item"
          activityItem.innerHTML = `
                        <div class="activity-icon"><i class="fas fa-user-plus"></i></div>
                        <div class="activity-details">
                            <p>${activity.nombre1} ${activity.apellido1} registrado</p>
                            <span>${activity.f_contra}</span>
                        </div>
                    `
          activityList.appendChild(activityItem)
        })
      })
      .catch((error) => {
        console.error("Error al actualizar actividad reciente:", error)
      })
  }

  // Configurar botones de actualización
  const refreshChartBtn = document.getElementById("refresh-chart")
  if (refreshChartBtn) {
    refreshChartBtn.addEventListener("click", function () {
      this.classList.add("rotating")
      updateDashboardStats()
      setTimeout(() => {
        this.classList.remove("rotating")
      }, 1000)
    })
  }

  const refreshActivityBtn = document.getElementById("refresh-activity")
  if (refreshActivityBtn) {
    refreshActivityBtn.addEventListener("click", function () {
      this.classList.add("rotating")
      updateActivityList()
      setTimeout(() => {
        this.classList.remove("rotating")
      }, 1000)
    })
  }

  // Inicializar paginación al cargar la página
  setupPagination()
  setupPaginationDeleted()

  // Llamar a la función para cargar la tabla de eliminados al inicio
  updateDeletedEmployeesTable()

  // Actualizar estadísticas periódicamente
  updateDashboardStats()
  setInterval(updateDashboardStats, 60000) // Actualizar cada minuto

  // Actualizar la tabla de eliminados cada 30 segundos
  setInterval(updateDeletedEmployeesTable, 30000)
})

