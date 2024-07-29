const tables = [
    { id: 1, status: 'available' },
    { id: 2, status: 'occupied' },
    { id: 3, status: 'reserved' },
    { id: 4, status: 'available' },
    { id: 5, status: 'available' },
    { id: 6, status: 'occupied' },
];

const restaurantElement = document.getElementById('restaurant');

function renderTables() {
    restaurantElement.innerHTML = '';
    tables.forEach(table => {
        const tableElement = document.createElement('div');
        tableElement.className = `table ${table.status}`;
        tableElement.textContent = `Mesa ${table.id}`;
        tableElement.onclick = () => changeTableStatus(table.id);
        restaurantElement.appendChild(tableElement);
    });
}

function changeTableStatus(tableId) {
    const table = tables.find(t => t.id === tableId);
    if (table) {
        switch (table.status) {
            case 'available':
                table.status = 'occupied';
                break;
            case 'occupied':
                table.status = 'available';
                break;
            case 'reserved':
                table.status = 'occupied';
                break;
        }
        renderTables();
    }
}

renderTables();