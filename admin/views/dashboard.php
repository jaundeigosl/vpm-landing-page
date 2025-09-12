<?php
require_once '../includes/auth_middleware.php';
require_once '../includes/header.php';
require_once '../actions/get_vacantes.php';

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

// Asume que esta variable de sesión se establece en el inicio de sesión
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
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
            <a href="#" class="btn btn-primary" id="openChangePasswordBtn">Cambiar Contraseña</a>
            <a href="../actions/logout_action.php" class="btn btn-danger">Cerrar Sesión</a>
            
            <?php if ($is_admin): ?>
            <button class="btn btn-add" id="openCreateUserModal">Crear Usuario</button>
            <button class="btn btn-danger" id="openDeleteUserModal">Eliminar Usuario</button>
            <?php endif; ?>
        </div>
        
        <div class="table-responsive">
            <table id="jobsTable" class="jobs-table">
                <thead>
                    <tr>
                        <th>Nombre del Puesto</th>
                        <th>Aplicaciones</th> <!-- Nueva columna -->
                        <th>Fecha Creación</th>
                        <th>Fecha Modificación</th>
                        <th>Detalles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($vacantes) > 0): ?>
                        <?php foreach ($vacantes as $vacante): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vacante['nombre_puesto']); ?></td>
                                <td class="application-count">
                                    <?php 
                                    $count = isset($vacante['aplicaciones']) ? intval($vacante['aplicaciones']) : 0;
                                    echo htmlspecialchars($count);
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($vacante['fecha_creacion']); ?></td>
                                <td><?php $fecha_actualizacion = isset(($vacante['fecha_actualizacion'])) ? htmlspecialchars($vacante['fecha_actualizacion']) : "Sin modificaciones"; 
                                    echo $fecha_actualizacion;
                                ?></td>
                                <td>
                                    <button class="btn btn-view" data-id="<?php echo htmlspecialchars($vacante['id']); ?>">Ver Detalles</button>
                                </td>
                                <td>
                                    <button class="btn btn-edit" data-id="<?php echo htmlspecialchars($vacante['id']); ?>">Editar</button>
                                    <button class="btn btn-delete" data-id="<?php echo htmlspecialchars($vacante['id']); ?>">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No se encontraron registros de vacantes.</td> <!-- Cambiado a 6 columnas -->
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para agregar nuevo puesto -->
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
                <input type="text" id="edad" name="edad" required>
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


<!-- Modal para ver aplicaciones -->
<div id="viewApplicationsModal" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <span class="close-btn" id="closeViewApplicationsModal">&times;</span>
        <h2>Aplicaciones para: <span id="applications-job-title"></span></h2>
        <div id="applications-content" class="applications-content">
            <!-- Las aplicaciones se cargarán aquí mediante JavaScript -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="closeViewApplicationsBtn">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de la vacante (solo lectura) -->
<div id="viewJobModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <span class="close-btn" id="closeViewJobModal">&times;</span>
        <h2>Detalles del Puesto</h2>
        <div id="view-job-content" class="view-job-content">
            <!-- Los detalles se cargarán aquí mediante JavaScript -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="closeViewJobBtn">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal para cambiar contraseña -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeChangePasswordModal">&times;</span>
        <h2>Cambiar Contraseña</h2>
        <div id="change-password-message"></div>
        <form id="changePasswordForm" action="../actions/change_password_action.php" method="POST">
            <div class="form-group">
                <label for="current_password">Contraseña Actual:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Contraseña Nueva:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña Nueva:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <button type="button" class="btn btn-secondary" id="cancelChangePasswordModal">Cancelar</button>
        </form>
    </div>
</div>

<!-- Modal para crear usuario -->
<div id="createUserModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeCreateUserModal">&times;</span>
        <h2>Crear Nuevo Usuario</h2>
        <div id="create-user-message"></div>
        <form id="createUserForm" action="../actions/create_user_action.php" method="POST">
            <div class="form-group">
                <label for="new_username">Nombre de Usuario:</label>
                <input type="text" id="new_username" name="username" required>
            </div>
            <div class="form-group">
                <label for="new_user_password">Contraseña:</label>
                <input type="password" id="new_user_password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
            <button type="button" class="btn btn-secondary" id="cancelCreateUserModal">Cancelar</button>
        </form>
    </div>
</div>

<!-- Modal para eliminar usuario -->
<div id="deleteUserModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeDeleteUserModal">&times;</span>
        <h2>Eliminar Usuario</h2>
        <div id="delete-user-message"></div>
        <div class="form-group">
            <label for="userList">Selecciona un usuario a eliminar:</label>
            <select id="userList" name="user_id" required>
                </select>
        </div>
        <button type="button" class="btn btn-danger" id="confirmDeleteUserBtn">Eliminar Usuario Seleccionado</button>
        <button type="button" class="btn btn-secondary" id="cancelDeleteUserModal">Cancelar</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Variables para los modales

    const viewApplicationsModal = document.getElementById('viewApplicationsModal');
    const closeViewApplicationsBtn = document.getElementById('closeViewApplicationsBtn');
    const closeViewApplicationsModalBtn = document.getElementById('closeViewApplicationsModal');
    const applicationsContent = document.getElementById('applications-content');
    const applicationsJobTitle = document.getElementById('applications-job-title');
    const addJobModal = document.getElementById('addJobModal');

    const openAddJobModalBtn = document.getElementById('openAddJobModal');
    const closeAddJobModalBtn = document.getElementById('closeAddJobModal');
    const cancelAddJobModalBtn = document.getElementById('cancelAddJobModal');

    const changePasswordModal = document.getElementById('changePasswordModal');
    const openChangePasswordBtn = document.getElementById('openChangePasswordBtn');
    const closeChangePasswordBtn = document.getElementById('closeChangePasswordModal');
    const cancelChangePasswordBtn = document.getElementById('cancelChangePasswordModal');
    const changePasswordForm = document.getElementById('changePasswordForm');
    const changePasswordMessageDiv = document.getElementById('change-password-message');

    const createUserModal = document.getElementById('createUserModal');
    const openCreateUserBtn = document.getElementById('openCreateUserModal');
    const closeCreateUserBtn = document.getElementById('closeCreateUserModal');
    const cancelCreateUserBtn = document.getElementById('cancelCreateUserModal');
    const createUserForm = document.getElementById('createUserForm');
    const createUserMessageDiv = document.getElementById('create-user-message');

    const deleteUserModal = document.getElementById('deleteUserModal');
    const openDeleteUserBtn = document.getElementById('openDeleteUserModal');
    const closeDeleteUserBtn = document.getElementById('closeDeleteUserModal');
    const cancelDeleteUserBtn = document.getElementById('cancelDeleteUserModal');
    const userListSelect = document.getElementById('userList');
    const confirmDeleteUserBtn = document.getElementById('confirmDeleteUserBtn');
    const deleteUserMessageDiv = document.getElementById('delete-user-message');

    // Nuevos elementos para el modal de visualización
    const viewJobModal = document.getElementById('viewJobModal');
    const closeViewJobBtn = document.getElementById('closeViewJobBtn');
    const closeViewJobModalBtn = document.getElementById('closeViewJobModal');
    const viewJobContent = document.getElementById('view-job-content');

    // Manejar modal de agregar vacante
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
    
    // Manejar modal de cambiar contraseña
    if (openChangePasswordBtn && changePasswordModal) {
        openChangePasswordBtn.addEventListener('click', (event) => {
            event.preventDefault();
            changePasswordModal.style.display = 'block';
        });
    }
    if (closeChangePasswordBtn) {
        closeChangePasswordBtn.addEventListener('click', () => {
            changePasswordModal.style.display = 'none';
        });
    }
    if (cancelChangePasswordBtn) {
        cancelChangePasswordBtn.addEventListener('click', () => {
            changePasswordModal.style.display = 'none';
        });
    }
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(changePasswordForm);
            if (formData.get('new_password') !== formData.get('confirm_password')) {
                changePasswordMessageDiv.innerHTML = '<p style="color: red;">¡Las contraseñas nuevas no coinciden!</p>';
                return;
            }
            changePasswordForm.style.display = 'none';
            changePasswordMessageDiv.innerHTML = '<p>Guardando cambios...</p>';
            fetch(changePasswordForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    changePasswordMessageDiv.innerHTML = `<p style="color: green;">${data.message}</p>`;
                    setTimeout(() => {
                        changePasswordModal.style.display = 'none';
                        changePasswordForm.style.display = 'block';
                        changePasswordForm.reset();
                    }, 2000); 
                } else {
                    changePasswordMessageDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
                    changePasswordForm.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                changePasswordMessageDiv.innerHTML = `<p style="color: red;">Error en la conexión. Inténtalo de nuevo.</p>`;
                changePasswordForm.style.display = 'block';
            });
        });
    }

    // Manejar modal de Crear Usuario
    if (openCreateUserBtn) {
        openCreateUserBtn.addEventListener('click', () => {
            createUserModal.style.display = 'block';
            createUserMessageDiv.innerHTML = '';
            createUserForm.reset();
            createUserForm.style.display = 'block';
        });
    }
    if (closeCreateUserBtn) {
        closeCreateUserBtn.addEventListener('click', () => {
            createUserModal.style.display = 'none';
        });
    }
    if (cancelCreateUserBtn) {
        cancelCreateUserBtn.addEventListener('click', () => {
            createUserModal.style.display = 'none';
        });
    }
    if (createUserForm) {
        createUserForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(createUserForm);
            
            createUserForm.style.display = 'none';
            createUserMessageDiv.innerHTML = '<p>Creando usuario...</p>';
            
            fetch(createUserForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    createUserMessageDiv.innerHTML = `<p style="color: green;">${data.message}</p>`;
                    setTimeout(() => {
                        createUserModal.style.display = 'none';
                        createUserForm.style.display = 'block';
                        createUserForm.reset();
                    }, 2000);
                } else {
                    createUserMessageDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
                    createUserForm.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                createUserMessageDiv.innerHTML = `<p style="color: red;">Error en la conexión. Inténtalo de nuevo.</p>`;
                createUserForm.style.display = 'block';
            });
        });
    }

    // Manejar modal de ver aplicaciones
    if (closeViewApplicationsBtn) {
        closeViewApplicationsBtn.addEventListener('click', () => {
            viewApplicationsModal.style.display = 'none';
        });
    }

    if (closeViewApplicationsModalBtn) {
        closeViewApplicationsModalBtn.addEventListener('click', () => {
            viewApplicationsModal.style.display = 'none';
        });
    }

    // Manejar modal de Eliminar Usuario
    if (openDeleteUserBtn) {
        openDeleteUserBtn.addEventListener('click', () => {
            deleteUserModal.style.display = 'block';
            deleteUserMessageDiv.innerHTML = '';
            userListSelect.innerHTML = ''; // Limpia la lista anterior
            fetchUsersForDeletion();
        });
    }
    if (closeDeleteUserBtn) {
        closeDeleteUserBtn.addEventListener('click', () => {
            deleteUserModal.style.display = 'none';
        });
    }
    if (cancelDeleteUserBtn) {
        cancelDeleteUserBtn.addEventListener('click', () => {
            deleteUserModal.style.display = 'none';
        });
    }
    if (confirmDeleteUserBtn) {
        confirmDeleteUserBtn.addEventListener('click', () => {
            const userId = userListSelect.value;
            if (userId) {
                if (confirm('¿Estás seguro de que deseas eliminar a este usuario?')) {
                    deleteUser(userId);
                }
            } else {
                deleteUserMessageDiv.innerHTML = '<p style="color: red;">Por favor, selecciona un usuario.</p>';
            }
        });
    }

    // Manejar modal de ver detalles
    if (closeViewJobBtn) {
        closeViewJobBtn.addEventListener('click', () => {
            viewJobModal.style.display = 'none';
        });
    }

    if (closeViewJobModalBtn) {
        closeViewJobModalBtn.addEventListener('click', () => {
            viewJobModal.style.display = 'none';
        });
    }

    // Event listener para botones de ver detalles
    document.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-view')) {
            const vacanteId = event.target.getAttribute('data-id');
            fetchVacanteDetails(vacanteId, true); // true indica que es para vista
        }
    });

    // Función para obtener la lista de usuarios no administradores
    function fetchUsersForDeletion() {
        deleteUserMessageDiv.innerHTML = '<p>Cargando usuarios...</p>';
        fetch('../actions/get_usuarios.php')
            .then(response => response.json())
            .then(data => {
                deleteUserMessageDiv.innerHTML = '';
                if (data.success) {
                    if (data.users.length > 0) {
                        data.users.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = user.username;
                            userListSelect.appendChild(option);
                        });
                    } else {
                        deleteUserMessageDiv.innerHTML = '<p>No hay usuarios para eliminar.</p>';
                    }
                } else {
                    deleteUserMessageDiv.innerHTML = `<p style="color: red;">Error: ${data.message}</p>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                deleteUserMessageDiv.innerHTML = '<p style="color: red;">Error al cargar la lista de usuarios.</p>';
            });
    }

    // Función para eliminar un usuario
    function deleteUser(userId) {
        deleteUserMessageDiv.innerHTML = '<p>Eliminando usuario...</p>';
        const formData = new FormData();
        formData.append('id', userId);
        
        fetch('../actions/delete_user_action.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                deleteUserMessageDiv.innerHTML = `<p style="color: green;">${data.message}</p>`;
                setTimeout(() => {
                    deleteUserModal.style.display = 'none';
                }, 2000);
            } else {
                deleteUserMessageDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            deleteUserMessageDiv.innerHTML = '<p style="color: red;">Error en la conexión. No se pudo eliminar el usuario.</p>';
        });
    }

    // Función para mostrar detalles de vacante en modal de solo lectura
    function showViewModal(vacante) {
        viewJobContent.innerHTML = `
            <div class="view-details-container">
                <div class="detail-row">
                    <div class="detail-label">Nombre del Puesto:</div>
                    <div class="detail-value">${vacante.nombre_puesto}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Ubicación:</div>
                    <div class="detail-value">${vacante.ubicacion}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Resumen:</div>
                    <div class="detail-value">${vacante.resumen}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Requisitos:</div>
                    <div class="detail-value">${vacante.requisitos}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Edad:</div>
                    <div class="detail-value">${vacante.edad}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Sexo:</div>
                    <div class="detail-value">${vacante.sexo === 'M' ? 'Masculino' : (vacante.sexo === 'F' ? 'Femenino' : 'Indistinto')}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Escolaridad:</div>
                    <div class="detail-value">${vacante.escolaridad}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Conocimientos:</div>
                    <div class="detail-value">${vacante.conocimientos}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Funciones:</div>
                    <div class="detail-value">${vacante.funciones}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Beneficios:</div>
                    <div class="detail-value">${vacante.beneficios}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Sueldo:</div>
                    <div class="detail-value">$${parseFloat(vacante.sueldo).toLocaleString('es-MX', {minimumFractionDigits: 2})}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Prestaciones:</div>
                    <div class="detail-value">${vacante.prestaciones}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Fecha Creación:</div>
                    <div class="detail-value">${vacante.fecha_creacion}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Fecha Modificación:</div>
                    <div class="detail-value">${vacante.fecha_modificacion}</div>
                </div>
            </div>
        `;
        viewJobModal.style.display = 'block';
    }

    // Modificar la función fetchVacanteDetails para soportar ambos modos
    function fetchVacanteDetails(id, viewOnly = false) {
        fetch(`../actions/get_job_details.php?id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(vacante => {
                if (vacante.error) {
                    alert('Error al cargar la vacante: ' + vacante.error);
                    return;
                }
                
                if (viewOnly) {
                    showViewModal(vacante);
                } else {
                    showEditModal(vacante);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener los datos de la vacante.');
            });
    }

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', (event) => {
        if (event.target === addJobModal) {
            addJobModal.style.display = 'none';
        }
        if (event.target === changePasswordModal) {
            changePasswordModal.style.display = 'none';
        }
        if (event.target === createUserModal) {
            createUserModal.style.display = 'none';
        }
        if (event.target === deleteUserModal) {
            deleteUserModal.style.display = 'none';
        }
        if (event.target === viewJobModal) {
            viewJobModal.style.display = 'none';
        }
        
        const editModal = document.getElementById('editJobModal');
        if (event.target === editModal) {
            editModal.style.display = 'none';
        }
        if (event.target === viewApplicationsModal) {
            viewApplicationsModal.style.display = 'none';
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
                            <input type="text" id="edit_edad" name="edad" value="${vacante.edad}" required>
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
        // El evento para btn-view se maneja con el event listener general arriba
    });

    // Función para obtener y mostrar aplicaciones
function fetchApplications(vacanteId) {
    applicationsContent.innerHTML = '<p>Cargando aplicaciones...</p>';
    
    function fetchApplications(vacanteId) {
    applicationsContent.innerHTML = '<p>No hay sistema de gestión de aplicaciones implementado.</p>';
    applicationsJobTitle.textContent = 'Vacante ID: ' + vacanteId;
    viewApplicationsModal.style.display = 'block';
}
}

// Función para mostrar aplicaciones en modal
function showApplicationsModal(applications, jobTitle) {
    applicationsJobTitle.textContent = jobTitle;
    applicationsContent.innerHTML = `
        <div class="applications-info">
            <p>El conteo de aplicaciones se almacena en la columna "aplicaciones" de la tabla vacantes.</p>
            <p>Para un sistema completo de gestión de aplicaciones, se necesitaría:</p>
            <ul>
                <li>Una tabla separada "aplicaciones"</li>
                <li>Formularios de aplicación</li>
                <li>Sistema de upload de CVs</li>
                <li>Gestión de candidatos</li>
            </ul>
        </div>
    `;
    viewApplicationsModal.style.display = 'block';
}

});
</script>

<style>
.dashboard-container {
    padding: 20px;
    max-width: 1200px;
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
    max-width: 100%;
}

.jobs-table {
    width: 100%;
    border-collapse: collapse;
}

.jobs-table th, .jobs-table td {
    padding: 12px 10px;
    text-align: center;
    border-bottom: 1px solid #dee2e6;
}

.jobs-table th {
    background-color: #f8f9fa;
    position: sticky;
    top: 0;
}

.jobs-table tr:hover {
    background-color: #f8f9fa;
}

/* Estilos para los botones */
.btn {
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    transition: background-color 0.3s;
    margin: 2px;
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

.btn-add {
    background-color: #28a745;
    color: white;
}

.btn-add:hover {
    background-color: #218838;
}

.btn-view {
    background-color: #17a2b8;
    color: white;
}

.btn-view:hover {
    background-color: #138496;
}

.btn-edit {
    background-color: #ffc107;
    color: black;
}

.btn-edit:hover {
    background-color: #e0a800;
}

.btn-delete {
    background-color: #dc3545;
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
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
    max-width: 900px;
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

/* Estilos para el modal de visualización */
.view-job-content {
    max-height: 70vh;
    overflow-y: auto;
    padding: 10px;
}

.view-details-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 15px;
}

.detail-row {
    display: flex;
    flex-wrap: wrap;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.detail-label {
    font-weight: bold;
    min-width: 180px;
    margin-right: 15px;
    color: #555;
}

.detail-value {
    flex: 1;
    word-break: break-word;
}

.modal-footer {
    margin-top: 20px;
    text-align: right;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

/* Estilos para la columna de aplicaciones */
.application-count {
    text-align: center;
    font-weight: bold;
    min-width: 120px;
}

/* Estilos para el modal de aplicaciones */
.applications-content {
    max-height: 70vh;
    overflow-y: auto;
    padding: 10px;
}

.applications-table {
    width: 100%;
    border-collapse: collapse;
}

.applications-table th,
.applications-table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

.applications-table th {
    background-color: #f8f9fa;
    position: sticky;
    top: 0;
}

.btn-view-cv {
    background-color: #17a2b8;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.btn-view-cv:hover {
    background-color: #138496;
}

/* Responsividad */
@media (max-width: 768px) {
    .dashboard-container {
        max-width: 95%;
        padding: 15px;
    }
    
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
        padding: 15px;
    }
    
    .dashboard-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .dashboard-actions .btn {
        flex: 1;
        min-width: 120px;
        text-align: center;
    }
    
    .detail-row {
        flex-direction: column;
    }
    
    .detail-label {
        margin-bottom: 5px;
        margin-right: 0;
    }
    
    .jobs-table {
        font-size: 14px;
    }
    
    .btn {
        padding: 6px 10px;
        font-size: 13px;
    }
}
</style>

<?php include '../includes/footer.php'; ?>