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
            <button class="btn btn-success" id="openCreateUserModal">Crear Usuario</button>
            <button class="btn btn-danger" id="openDeleteUserModal">Eliminar Usuario</button>
            <?php endif; ?>
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
    // Variables para los modales existentes
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

    // Nuevas variables para los modales de administración
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

    // Manejar nuevo modal de Crear Usuario
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

    // Manejar nuevo modal de Eliminar Usuario
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
    max-width: 1800px; /* Aumenté el ancho máximo */
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
    table-layout: auto; /* Cambiado a auto para permitir anchos variables */
}

.jobs-table th, .jobs-table td {
    padding: 12px 10px; /* Aumenté el padding vertical */
    text-align: left;
    border-bottom: 1px solid #dee2e6;
    font-size: 14px;
    word-wrap: break-word;
}

/* Especificar anchos personalizados para cada columna */
.jobs-table th:nth-child(1), .jobs-table td:nth-child(1) { /* Nombre del Puesto */
    min-width: 150px;
    max-width: 180px;
}

.jobs-table th:nth-child(2), .jobs-table td:nth-child(2) { /* Ubicación */
    min-width: 120px;
    max-width: 150px;
}

.jobs-table th:nth-child(3), .jobs-table td:nth-child(3) { /* Resumen */
    min-width: 200px;
    max-width: 250px;
}

.jobs-table th:nth-child(4), .jobs-table td:nth-child(4) { /* Requisitos */
    min-width: 200px;
    max-width: 250px;
}

.jobs-table th:nth-child(5), .jobs-table td:nth-child(5) { /* Edad */
    min-width: 70px;
    max-width: 90px;
}

.jobs-table th:nth-child(6), .jobs-table td:nth-child(6) { /* Sexo */
    min-width: 80px;
    max-width: 100px;
}

.jobs-table th:nth-child(7), .jobs-table td:nth-child(7) { /* Escolaridad */
    min-width: 120px;
    max-width: 150px;
}

.jobs-table th:nth-child(8), .jobs-table td:nth-child(8) { /* Conocimientos */
    min-width: 180px;
    max-width: 220px;
}

.jobs-table th:nth-child(9), .jobs-table td:nth-child(9) { /* Funciones */
    min-width: 180px;
    max-width: 220px;
}

.jobs-table th:nth-child(10), .jobs-table td:nth-child(10) { /* Beneficios */
    min-width: 180px;
    max-width: 220px;
}

.jobs-table th:nth-child(11), .jobs-table td:nth-child(11) { /* Sueldo */
    min-width: 100px;
    max-width: 120px;
}

.jobs-table th:nth-child(12), .jobs-table td:nth-child(12) { /* Prestaciones */
    min-width: 150px;
    max-width: 180px;
}

.jobs-table th:nth-child(13), .jobs-table td:nth-child(13) { /* Fecha Creación */
    min-width: 120px;
    max-width: 140px;
}

.jobs-table th:nth-child(14), .jobs-table td:nth-child(14) { /* Fecha Modificación */
    min-width: 120px;
    max-width: 140px;
}

.jobs-table th:nth-child(15), .jobs-table td:nth-child(15) { /* Acciones */
    min-width: 120px;
    max-width: 140px;
}

.jobs-table th {
    background-color: #f8f9fa;
    position: sticky;
    top: 0;
    cursor: pointer;
    white-space: nowrap;
}

.jobs-table th:hover {
    background-color: #e9ecef;
}

.jobs-table tr:hover {
    background-color: #f8f9fa;
}

/* Estilos para mejorar la visualización de textos largos */
.jobs-table td {
    vertical-align: top;
}

.jobs-table td:not(:nth-child(15)) { /* Aplicar a todas las celdas excepto Acciones */
    max-height: 120px;
    overflow-y: auto;
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
    max-width: 900px; /* Aumenté el ancho máximo del modal */
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

/* Mejoras de responsividad */
@media (max-width: 1600px) {
    .dashboard-container {
        max-width: 1500px;
    }
}

@media (max-width: 1400px) {
    .dashboard-container {
        max-width: 1300px;
    }
    
    .jobs-table th, .jobs-table td {
        font-size: 13px;
        padding: 10px 8px;
    }
}

@media (max-width: 1200px) {
    .dashboard-container {
        max-width: 1100px;
        padding: 15px;
    }
    
    .jobs-table {
        min-width: 1000px;
    }
}

@media (max-width: 992px) {
    .dashboard-container {
        max-width: 95%;
    }
    
    .modal-content {
        width: 90%;
        max-width: 95%;
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
}

/* Estilo para mejorar la visualización de textos largos */
.text-truncate {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.tooltip {
    position: relative;
    display: inline-block;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 250px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -125px;
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 13px;
    line-height: 1.4;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}
</style>

<?php include '../includes/footer.php'; ?>