import { apiBase } from "config/config";

document.addEventListener('DOMContentLoaded', function() {
    let url = apiBase.apiBaseUrl;
    let endpoint = '/foods';
    let currentPage = 1;
    const rowsPerPage = 10;
    let id_category;

    getCategories();

    fetch(`${url}${endpoint}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('foodTableBody');
            const pagination = document.getElementById('pagination');

            function renderTable(page) {
                tableBody.innerHTML = '';
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const pageData = data.slice(start, end);

                pageData.forEach(food => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-id', food.id);

                    const idCell = document.createElement('td');
                    idCell.textContent = food.id;
                    row.appendChild(idCell);

                    const nameCell = document.createElement('td');
                    nameCell.textContent = food.nombre;
                    row.appendChild(nameCell);

                    const descriptionCell = document.createElement('td');
                    descriptionCell.textContent = food.descripcion;
                    row.appendChild(descriptionCell);

                    const priceCell = document.createElement('td');
                    priceCell.textContent = food.precio;
                    row.appendChild(priceCell);

                    const availabilityCell = document.createElement('td');
                    availabilityCell.textContent = food.disponibilidad;
                    row.appendChild(availabilityCell);

                    const actionsCell = document.createElement('td');

                    const editButton = document.createElement('button');
                    editButton.textContent = 'Editar';
                    editButton.addEventListener('click', () => editFood(food));
                    actionsCell.appendChild(editButton);

                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Eliminar';
                    deleteButton.addEventListener('click', () => deleteFood(food.id));
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


    async function getCategories() {
        let endpoint = '/categories';

        try {
            const response = await fetch(`${apiBase.apiBaseUrl}${endpoint}`);
            if (!response.ok) {
                throw new Error('Error al obtener las categorías');
            }
            const categories = await response.json();

            const selectElement = document.getElementById('categoryFood');
            selectElement.innerHTML = '';

            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                id_category = category.id;
                option.textContent = category.nombre;
                selectElement.appendChild(option);
            });
        } catch (error) {
            console.error('Error:', error);
        }
    }

});

document.getElementById('addButton').addEventListener('click', function() {
    const foodName = document.getElementById('foodName').value;
    const foodDescription = document.getElementById('foodDescription').value;
    const foodPriceValue = document.getElementById('foodPrice').value;
    const foodPrice = parseFloat(foodPriceValue);
    const foodAvailability = document.getElementById('foodAvailability').value;
    const categoryFood = document.getElementById('categoryFood').value;


    if (isNaN(foodPrice)) {
        console.error('El precio ingresado no es un número válido.');
    } else {
        console.log('Precio:', foodPrice);
    }

    let endpoint = '/food';

    const data = {
        nombre: foodName,
        descripcion: foodDescription,
        precio: foodPrice,
        disponibilidad: foodAvailability,
        categoria_id: categoryFood
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
                console.log('Food creada exitosamente');
            } else {
                console.error('Error al crear la comida:', data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

});

function editFood(food) {
    const row = document.querySelector(`tr[data-id="${food.id}"]`);

    if (!row) {
        console.error('Fila no encontrada para el id:', food.id);
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
    container.id = `edit-container-${food.id}`;
    container.style.margin = '20px';

    const input = document.createElement('input');
    input.type = 'text';
    input.placeholder = 'Nuevo nombre del plato de comida';
    input.id = `food-name-${food.id}`;
    input.classList.add('input-button-style');
    input.value = food.nombre;

    const descriptionInput = document.createElement('textarea');
    descriptionInput.placeholder = 'Descripción del plato de comida';
    descriptionInput.id = `food-description-${food.id}`;
    descriptionInput.classList.add('input-button-style');
    descriptionInput.value = food.descripcion;

    const priceInput = document.createElement('input');
    priceInput.type = 'number';
    priceInput.placeholder = 'Precio del plato de comida';
    priceInput.id = `food-price-${food.id}`;
    priceInput.classList.add('input-button-style');
    priceInput.step = '0.01';
    priceInput.value = food.precio;

    const availabilitySelect = document.createElement('select');
    availabilitySelect.id = `food-availability-${food.id}`;
    availabilitySelect.classList.add('input-button-style');

    const optionAvailable = document.createElement('option');
    optionAvailable.value = '1';
    optionAvailable.textContent = 'Disponible';
    availabilitySelect.appendChild(optionAvailable);

    const optionUnavailable = document.createElement('option');
    optionUnavailable.value = '0';
    optionUnavailable.textContent = 'No Disponible';
    availabilitySelect.appendChild(optionUnavailable);

    container.appendChild(input);
    container.appendChild(descriptionInput);
    container.appendChild(priceInput);
    container.appendChild(availabilitySelect);

    const button = document.createElement('button');
    button.innerText = 'Guardar';
    button.style.background = 'purple';
    button.onclick = function() {
        const newName = input.value;
        const newDescription = descriptionInput.value;
        const newPrice = parseFloat(priceInput.value);
        const newAvailability = availabilitySelect.value;

        if (newName && newDescription && !isNaN(newPrice) && newAvailability) {
            const foodData = {
                nombre: newName,
                descripcion: newDescription,
                precio: newPrice,
                disponibilidad: newAvailability
            };

            updateFood(food.id, foodData);
        } else {
            alert('Por favor, asegúrate de que todos los campos estén correctamente llenos.');
        }
    };

    container.appendChild(button);
    actionCell.appendChild(container);
}

function updateFood(id, foodData) {
    let editar = `/food/${id}`;

    fetch(`${apiBase.apiBaseUrl}${editar}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(foodData)
    })
        .then(response => {
            if (response.ok) {
                console.log('Comida editada con éxito');
                location.reload();
            } else {
                console.error('Error al editar el plato de comida');
            }
        })
        .catch(error => console.error('Error al editar el plato:', error));
}

function deleteFood(id) {
    let eliminar = `/food/${id}`;

    fetch(`${apiBase.apiBaseUrl}${eliminar}`, {
        method: 'DELETE'
    })
        .then(response => {
            if (response.ok) {
                console.log('Food eliminada con éxito');
                location.reload();
            } else {
                console.error('Error al eliminar la food');
            }
        })
        .catch(error => console.error('Error al eliminar la food:', error));
}