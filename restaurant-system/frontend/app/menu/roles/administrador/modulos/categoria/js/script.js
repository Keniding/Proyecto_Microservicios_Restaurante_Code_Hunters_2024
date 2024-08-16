import { apiBase } from "config/config";

document.addEventListener('DOMContentLoaded', function() {
    let url = apiBase.apiBaseUrl;
    let endpoint = '/categories';
    let currentPage = 1;
    const rowsPerPage = 10;

    fetch(`${url}${endpoint}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('categoryTableBody');
            const pagination = document.getElementById('pagination');

            function renderTable(page) {
                tableBody.innerHTML = '';
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const pageData = data.slice(start, end);

                pageData.forEach(categoria => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-id', categoria.id);

                    const idCell = document.createElement('td');
                    idCell.textContent = categoria.id;
                    row.appendChild(idCell);

                    const nameCell = document.createElement('td');
                    nameCell.textContent = categoria.nombre;
                    row.appendChild(nameCell);

                    const actionsCell = document.createElement('td');

                    const editButton = document.createElement('button');
                    editButton.textContent = 'Editar';
                    editButton.addEventListener('click', () => editCategory(categoria.id));
                    actionsCell.appendChild(editButton);

                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Eliminar';
                    deleteButton.addEventListener('click', () => deleteCategory(categoria.id));
                    actionsCell.appendChild(deleteButton);

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
});

document.getElementById('addButton').addEventListener('click', () => {
    let endpoint = '/category';
    let nombre = document.getElementById('categoryName').value;

    const data = {
        nombre: nombre
    };

    const url = `${apiBase.apiBaseUrl}${endpoint}`;

    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    };

    fetch(url, options)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
                console.log('Categoría creada exitosamente');
            } else {
                console.error('Error al crear la categoría:', data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

});

function editCategory(id) {

    const row = document.querySelector(`tr[data-id="${id}"]`);

    if (!row) {
        console.error('Fila no encontrada para el id:', id);
        return;
    }

    const actionCell = row.querySelector('td:last-child');

    if (!actionCell) {
        console.error('Celda de acción no encontrada.');
        return;
    }

    const existingContainers = document.querySelectorAll('[id^="edit-container-"]');
    existingContainers.forEach(container => container.remove());

    const container = document.createElement('div');
    container.id = `edit-container-${id}`;
    container.style.margin = '20px';

    const input = document.createElement('input');
    input.type = 'text';
    input.placeholder = 'Nuevo nombre de categoría';
    input.id = `category-name-${id}`;
    input.classList.add('input-button-style');

    const button = document.createElement('button');
    button.innerText = 'Guardar';
    button.style.background = 'purple';
    button.onclick = function() {
        const newName = input.value;
        if (newName) {
            updateCategoryName(id, newName);
        } else {
            alert('El nombre no puede estar vacío');
        }
    };

    container.appendChild(input);
    container.appendChild(button);

    actionCell.appendChild(container);
}

function updateCategoryName(id, newName) {
    let editar = `/category/${id}`;

    fetch(`${apiBase.apiBaseUrl}${editar}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ nombre: newName })
    })
        .then(response => {
            if (response.ok) {
                console.log('Categoría editada con éxito');
                location.reload();
            } else {
                console.error('Error al editar la categoría');
            }
        })
        .catch(error => console.error('Error al editar la categoría:', error));
}

function deleteCategory(id) {
    let endpoint = `/foodForCategory/${id}`;
    let eliminar = `/category/${id}`;
    fetch(`${apiBase.apiBaseUrl}${endpoint}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                alert('No se puede eliminar esta categoría porque tiene dependencias.');
            } else {
                fetch(`${apiBase.apiBaseUrl}${eliminar}`, {
                    method: 'DELETE'
                })
                    .then(response => {
                        if (response.ok) {
                            console.log('Categoría eliminada con éxito');
                            location.reload();
                        } else {
                            console.error('Error al eliminar la categoría');
                        }
                    })
                    .catch(error => console.error('Error al eliminar la categoría:', error));
            }
        })
        .catch(error => console.error('Error al comprobar dependencias:', error));
}