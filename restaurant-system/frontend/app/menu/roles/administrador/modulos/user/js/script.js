import {apiBase} from "config/config";

document.addEventListener('DOMContentLoaded', function() {
    let url = apiBase.apiBaseUrl;
    let endpoint = '/users';
    let currentPage = 1;
    const rowsPerPage = 10;
    let id_rol;

    /*
    Optimización de recuperación de nombres para mostrar en la tabla
    Si se hacia con /rol/{id} las peticiones probocaban una relentitud en las solicitudes
    Se guarda para luego usarse
     */
    let rolesMap = new Map();

    async function fetchRoles() {
        try {
            const response = await fetch(`${apiBase.apiBaseUrl}/roles`);
            const roles = await response.json();
            roles.forEach(role => {
                rolesMap.set(role.id, role.nombre);
            });
        } catch (error) {
            console.error('Error fetching roles:', error);
        }
    }

    function getRolName(id) {
        return rolesMap.get(id) || 'Unknown Role';
    }

    fetchRoles();

    fetch(`${url}${endpoint}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('userTableBody');
            const pagination = document.getElementById('pagination');

            function renderTable(page) {
                tableBody.innerHTML = '';
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const pageData = data.slice(start, end);

                pageData.forEach(async user => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-id', user.id);

                    const idCell = document.createElement('td');
                    idCell.textContent = user.id;
                    row.appendChild(idCell);

                    const nameCell = document.createElement('td');
                    nameCell.textContent = user.username;
                    row.appendChild(nameCell);

                    const emailCell = document.createElement('td');
                    emailCell.textContent = user.email;
                    row.appendChild(emailCell);

                    const telefonoCell = document.createElement('td');
                    telefonoCell.textContent = user.telefono;
                    row.appendChild(telefonoCell);

                    const rollCell = document.createElement('td');
                    try {
                        rollCell.textContent = await getRolName(user.rol_id);
                    } catch (error) {
                        rollCell.textContent = 'Error';
                    }
                    row.appendChild(rollCell);


                    const estadoCell = document.createElement('td');
                    estadoCell.textContent = user.estado === 1 ? 'Activo' : 'Inactivo';
                    if (user.estado === 1) {
                        estadoCell.style.backgroundColor = '#b2fab4';
                        estadoCell.style.color = '#2e7d32';
                    } else {
                        estadoCell.style.backgroundColor = '#ffcdd2';
                        estadoCell.style.color = '#c62828';
                    }
                    row.appendChild(estadoCell);

                    const actionsCell = document.createElement('td');

                    /*
                    const editButton = document.createElement('button');
                    editButton.textContent = 'Editar';
                    editButton.addEventListener('click', () => editUser(user));
                    actionsCell.appendChild(editButton);
                     */

                    const statusButton = document.createElement('button');
                    statusButton.textContent = 'Cambiar Estado';
                    statusButton.addEventListener('click', () => editStatusUser(user.id));
                    actionsCell.appendChild(statusButton);

                    /*
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Eliminar';
                    deleteButton.addEventListener('click', () => deleteUser(user.id));
                    actionsCell.appendChild(deleteButton);
                     */

                    row.appendChild(actionsCell);

                    tableBody.appendChild(row);
                });

                renderPagination();
            }

            function renderPagination() {
                pagination.innerHTML = '';
                const pageCount = Math.ceil(data.length / rowsPerPage);

                for (let i = 1; i <= pageCount; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.textContent = i;
                    pageButton.addEventListener('click', () => {
                        currentPage = i;
                        renderTable(currentPage);
                    });

                    if (i === currentPage) {
                        pageButton.classList.add('active');
                    }

                    pagination.appendChild(pageButton);
                }
            }

            renderTable(currentPage);
        })
        .catch(error => console.error('Error al obtener los datos:', error));
})


function editStatusUser(id) {
    let status = `/userStatus/${id}`;

    fetch(`${apiBase.apiBaseUrl}${status}`, {
        method: 'PUT'
    })
        .then(response => {
            if (response.ok) {
                console.log('Status actualizado con éxito');
                location.reload();
            } else {
                console.error('Error al actualizar Status de usuario');
            }
        })
        .catch(error => console.error('Error al actualizar estatus de user:', error));
}
