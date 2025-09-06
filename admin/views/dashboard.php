<?php
require_once '../includes/auth_middleware.php';
require_once '../includes/header.php';
require_once '../includes/get_vacantes.php';

authMiddleware();

if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $success_message = '';
    $error_message = '';
    
    switch ($message) {
        case 'add_success':
            $success_message = '¡Vacante agregada exitosamente!';
            break;
        case 'add_error':
            $error_message = 'Error al agregar la vacante. Inténtalo de nuevo.';
            break;
    }

    if (!empty($success_message)) {
        echo '<div class="alert success">' . htmlspecialchars($success_message) . '</div>';
    }
    if (!empty($error_message)) {
        echo '<div class="alert error">' . htmlspecialchars($error_message) . '</div>';
    }
}
?>
<div class="dashboard-container">
    <h1>Panel de Administración</h1>
    
    <div class="welcome-message">
        <p>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
    </div>

    <div class="dashboard-stats">        
        <div class="table-controls">
            <button class="btn btn-add" id="openAddJobModal">Agregar Nuevo Puesto</button>
        </div>
        <div class="dashboard-actions">
            <a href="change_password.php" class="btn btn-primary">Cambiar Contraseña</a>
            <a href="../actions/logout_action.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
        
        <div class="table-responsive">
            <table id="jobsTable" class="jobs-table">
                <thead>
                    <tr>
                        <th>Nombre del Puesto </th>
                        <th>Ubicación </th>
                        <th>Resumen</th>
                        <th>Requisitos</th>
                        <th>Edad </th>
                        <th>Sexo </th>
                        <th>Escolaridad </th>
                        <th>Conocimientos</th>
                        <th>Funciones</th>
                        <th>Beneficios</th>
                        <th>Sueldo </th>
                        <th>Prestaciones</th>
                        <th>Fecha Creación </th>
                        <th>Fecha Modificación </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($vacantes) > 0): ?>
                        <?php foreach ($vacantes as $vacante): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vacante['nombre_puesto']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['ubicacion']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['resumen']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['requisitos']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['edad']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['sexo']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['escolaridad']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['conocimientos']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['funciones']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['beneficios']); ?></td>
                                <td>$<?php echo htmlspecialchars(number_format($vacante['sueldo'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($vacante['prestaciones']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['fecha_creacion']); ?></td>
                                <td><?php echo htmlspecialchars($vacante['fecha_modificacion']); ?></td>
                                <td>
                                    <button class="btn btn-edit" data-id="<?php echo htmlspecialchars($vacante['id']); ?>">Editar</button>
                                    <button class="btn btn-delete" data-id="<?php echo htmlspecialchars($vacante['id']); ?>">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="15">No se encontraron registros de vacantes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addJobModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeAddJobModal">&times;</span>
        <h2>Agregar Nuevo Puesto</h2>
        <form action="../actions/add_job_action.php" method="POST">
            <div class="form-group">
                <label for="nombre_puesto">Nombre del Puesto:</label>
                <input type="text" id="nombre_puesto" name="nombre_puesto" required>
            </div>
            <div class="form-group">
                <label for="ubicacion">Ubicación:</label>
                <input type="text" id="ubicacion" name="ubicacion" required>
            </div>
            <div class="form-group">
                <label for="resumen">Resumen:</label>
                <textarea id="resumen" name="resumen" required></textarea>
            </div>
            <div class="form-group">
                <label for="requisitos">Requisitos:</label>
                <textarea id="requisitos" name="requisitos" required></textarea>
            </div>
            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" required>
            </div>
            <div class="form-group">
                <label for="sexo">Sexo:</label>
                <select id="sexo" name="sexo" required>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                    <option value="I">Indistinto</option>
                </select>
            </div>
            <div class="form-group">
                <label for="escolaridad">Escolaridad:</label>
                <textarea id="escolaridad" name="escolaridad" required></textarea>
            </div>
            <div class="form-group">
                <label for="conocimientos">Conocimientos:</label>
                <textarea id="conocimientos" name="conocimientos" required></textarea>
            </div>
            <div class="form-group">
                <label for="funciones">Funciones:</label>
                <textarea id="funciones" name="funciones" required></textarea>
            </div>
            <div class="form-group">
                <label for="beneficios">Beneficios:</label>
                <textarea id="beneficios" name="beneficios" required></textarea>
            </div>
            <div class="form-group">
                <label for="sueldo">Sueldo:</label>
                <input type="number" id="sueldo" name="sueldo" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="prestaciones">Prestaciones:</label>
                <textarea id="prestaciones" name="prestaciones" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Puesto</button>
            <button type="button" class="btn btn-secondary" id="cancelAddJobModal">Cancelar</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Variables para los modales
    const addJobModal = document.getElementById('addJobModal');
    const openAddJobModalBtn = document.getElementById('openAddJobModal');
    const closeAddJobModalBtn = document.getElementById('closeAddJobModal');
    const cancelAddJobModalBtn = document.getElementById('cancelAddJobModal');

    // Manejar modal de agregar
    if (openAddJobModalBtn && addJobModal) {
        openAddJobModalBtn.addEventListener('click', () => {
            addJobModal.style.display = 'block';
        });
    }

    if (closeAddJobModalBtn) {
        closeAddJobModalBtn.addEventListener('click', () => {
            addJobModal.style.display = 'none';
        });
    }

    if (cancelAddJobModalBtn) {
        cancelAddJobModalBtn.addEventListener('click', () => {
            addJobModal.style.display = 'none';
        });
    }

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', (event) => {
        if (event.target === addJobModal) {
            addJobModal.style.display = 'none';
        }
        
        const editModal = document.getElementById('editJobModal');
        if (event.target === editModal) {
            editModal.style.display = 'none';
        }
    });

    const jobsTable = document.getElementById('jobsTable');
    if (!jobsTable) {
        console.error("No se encontró el elemento con ID 'jobsTable'. Asegúrate de que exista en tu HTML.");
        return;
    }

    function showEditModal(vacante) {
        const existingModal = document.getElementById('editJobModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        const modalHtml = `
            <div id="editJobModal" class="modal" style="display:block;">
                <div class="modal-content">
                    <span class="close-btn" id="closeEditJobModal">&times;</span>
                    <h2>Editar Vacante: ${vacante.nombre_puesto}</h2>
                    <div id="edit-message"></div>
                    <form id="editJobForm">
                        <input type="hidden" name="id" value="${vacante.id}">
                        
                        <div class="form-group">
                            <label for="edit_nombre_puesto">Nombre del Puesto:</label>
                            <input type="text" id="edit_nombre_puesto" name="nombre_puesto" value="${vacante.nombre_puesto}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_ubicacion">Ubicación:</label>
                            <input type="text" id="edit_ubicacion" name="ubicacion" value="${vacante.ubicacion}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_sueldo">Sueldo:</label>
                            <input type="number" id="edit_sueldo" name="sueldo" value="${vacante.sueldo}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_resumen">Resumen:</label>
                            <textarea id="edit_resumen" name="resumen" required>${vacante.resumen}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_requisitos">Requisitos:</label>
                            <textarea id="edit_requisitos" name="requisitos" required>${vacante.requisitos}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_edad">Edad:</label>
                            <input type="number" id="edit_edad" name="edad" value="${vacante.edad}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_sexo">Sexo:</label>
                            <select id="edit_sexo" name="sexo" required>
                                <option value="Indistinto" ${vacante.sexo === 'Indistinto' ? 'selected' : ''}>Indistinto</option>
                                <option value="Masculino" ${vacante.sexo === 'Masculino' ? 'selected' : ''}>Masculino</option>
                                <option value="Femenino" ${vacante.sexo === 'Femenino' ? 'selected' : ''}>Femenino</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_escolaridad">Escolaridad:</label>
                            <textarea id="edit_escolaridad" name="escolaridad" required>${vacante.escolaridad}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_conocimientos">Conocimientos:</label>
                            <textarea id="edit_conocimientos" name="conocimientos" required>${vacante.conocimientos}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_funciones">Funciones:</label>
                            <textarea id="edit_funciones" name="funciones" required>${vacante.funciones}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_beneficios">Beneficios:</label>
                            <textarea id="edit_beneficios" name="beneficios" required>${vacante.beneficios}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_prestaciones">Prestaciones:</label>
                            <textarea id="edit_prestaciones" name="prestaciones" required>${vacante.prestaciones}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <button type="button" class="btn btn-secondary" id="cancelEditJobModal">Cancelar</button>
                    </form>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const editModal = document.getElementById('editJobModal');
        const closeEditBtn = document.getElementById('closeEditJobModal');
        const cancelEditBtn = document.getElementById('cancelEditJobModal');
        const editForm = document.getElementById('editJobForm');
        const messageDiv = document.getElementById('edit-message');

        if (closeEditBtn) {
            closeEditBtn.addEventListener('click', () => {
                editModal.remove();
            });
        }

        if (cancelEditBtn) {
            cancelEditBtn.addEventListener('click', () => {
                editModal.remove();
            });
        }

        editForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(editForm);
            
            editForm.style.display = 'none';
            messageDiv.innerHTML = '<p>Guardando cambios...</p>';

            fetch('../actions/update_job_action.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.innerHTML = `<p style="color: green;">${data.message}</p>`;
                    setTimeout(() => location.reload(), 1500); 
                } else {
                    messageDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
                    editForm.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.innerHTML = `<p style="color: red;">Error en la conexión. Inténtalo de nuevo.</p>`;
                editForm.style.display = 'block';
            });
        });
    }

    function deleteVacante(id, row) {
        if (confirm('¿Estás seguro de que deseas eliminar esta vacante? Esta acción no se puede deshacer.')) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('../actions/delete_job_action.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.remove();
                    alert(data.message);
                } else {
                    alert('Error al eliminar: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error en la conexión. No se pudo eliminar la vacante.');
            });
        }
    }

    jobsTable.addEventListener('click', (event) => {
        const target = event.target;
        const vacanteId = target.getAttribute('data-id');

        if (target.classList.contains('btn-edit')) {
            fetchVacanteDetails(vacanteId);
        } else if (target.classList.contains('btn-delete')) {
            const row = target.closest('tr');
            deleteVacante(vacanteId, row);
        }
    });

    function fetchVacanteDetails(id) {
        console.log('Obteniendo detalles para ID:', id);
        
        fetch(`../actions/get_job_details.php?id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(vacante => {
                console.log('Datos recibidos:', vacante);
                if (vacante.error) {
                    alert('Error al cargar la vacante: ' + vacante.error);
                    return;
                }
                showEditModal(vacante);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener los datos de la vacante. Revisa la consola del navegador para más detalles.');
            });
    }

});
</script>

<style>
.dashboard-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.welcome-message {
    margin-bottom: 20px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
}

.dashboard-stats {
    margin-bottom: 30px;
    display:flex;
    flex-wrap:wrap;
}

.table-controls {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.search-box {
    display: flex;
    gap: 10px;
}

.search-box input {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    min-width: 250px;
}

.table-responsive {
    overflow-x: auto;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
}

.jobs-table {
    width: 100%;
    border-collapse: collapse;
}

.jobs-table th, .jobs-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
    font-size: 14px;
}

.jobs-table th {
    background-color: #f8f9fa;
    position: sticky;
    top: 0;
    cursor: pointer;
}

.jobs-table th:hover {
    background-color: #e9ecef;
}

.jobs-table tr:hover {
    background-color: #f8f9fa;
}

.btn {
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    transition: background-color 0.3s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0069d9;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
}

.btn-search {
    background-color: #6c757d;
    color: white;
}

.btn-search:hover {
    background-color: #5a6268;
}

.btn-add {
    background-color: #28a745;
    color: white;
}

.btn-add:hover {
    background-color: #218838;
}

.btn-edit {
    background-color: #ffc107;
    color: black;
    padding: 5px 10px;
    margin-right: 5px;
}

.btn-edit:hover {
    background-color: #e0a800;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
    padding: 5px 10px;
}

.btn-delete:hover {
    background-color: #c82333;
}

.btn-pagination {
    background-color: #6c757d;
    color: white;
    margin: 0 5px;
}

.btn-pagination:hover {
    background-color: #5a6268;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.page-info {
    margin: 0 15px;
}

/* Modal CSS */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 800px;
    border-radius: 8px;
    position: relative;
    max-height: 90vh;
    overflow-y: auto;
}

.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close-btn:hover,
.close-btn:focus {
    color: black;
    text-decoration: none;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input, .form-group textarea, .form-group select {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert.success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.alert.error {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
}

/* Responsividad */
@media (max-width: 1200px) {
    .jobs-table {
        min-width: 1000px;
    }
}

@media (max-width: 768px) {
    .table-controls {
        flex-direction: column;
    }
    
    .search-box {
        width: 100%;
    }
    
    .search-box input {
        min-width: unset;
        flex-grow: 1;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
}
</style>

<?php include '../includes/footer.php'; ?>