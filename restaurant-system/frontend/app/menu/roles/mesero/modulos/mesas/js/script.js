import { apiBase } from 'config/config';

const restaurantElement = document.getElementById('restaurant');

let tables = [];
let statusMap = {};

function renderTables() {
    restaurantElement.innerHTML = '';
    tables.forEach(table => {
        const statusName = statusMap[table.id_estado_mesa];
        if (!statusName) {
            console.error(`Estado desconocido para id_estado_mesa: ${table.id_estado_mesa}`);
            return;
        }
        const tableElement = document.createElement('div');
        tableElement.className = `table ${statusName.replace(' ', '-')}`;
        tableElement.textContent = `Mesa ${table.id_mesa}`;
        tableElement.onclick = () => changeTableStatus(table.id_mesa);
        restaurantElement.appendChild(tableElement);
    });
}

function renderLegend() {
    const legendElement = document.getElementById('legend');
    legendElement.innerHTML = '';
    Object.entries(statusMap).forEach(([id, name]) => {
        const legendItem = document.createElement('div');
        legendItem.className = 'legend-item';

        const colorDiv = document.createElement('div');
        colorDiv.className = `legend-color ${name.replace(' ', '-')}`;

        const textDiv = document.createElement('div');
        textDiv.className = 'legend-text';
        textDiv.textContent = name.charAt(0).toUpperCase() + name.slice(1);

        legendItem.appendChild(colorDiv);
        legendItem.appendChild(textDiv);
        legendElement.appendChild(legendItem);
    });
}

function changeTableStatus(tableId) {
    const table = tables.find(t => t.id_mesa === tableId);
    if (table) {
        switch (table.id_estado_mesa) {
            case 1:
                table.id_estado_mesa = 2;
                break;
            case 2:
                table.id_estado_mesa = 3;
                break;
            case 3:
                table.id_estado_mesa = 4;
                break;
            case 4:
                table.id_estado_mesa = 1;
                break;
            default:
                console.error(`Estado desconocido para id_estado_mesa: ${table.id_estado_mesa}`);
                return;
        }
        renderTables();
    }
}

function fetchTables() {
    const url = `${apiBase.apiBaseUrl}/mesas`;
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            tables = data;
            renderTables();
        })
        .catch(error => {
            console.error('Error al obtener las mesas:', error);
        });
}

function fetchStatuses() {
    const url = `${apiBase.apiBaseUrl}/estadosMesa`;
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            data.forEach(status => {
                statusMap[status.id_estado_mesa] = status.nombre.toLowerCase();
            });
            renderLegend();
            fetchTables();
        })
        .catch(error => {
            console.error('Error al obtener los estados de mesa:', error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    fetchStatuses();
});
