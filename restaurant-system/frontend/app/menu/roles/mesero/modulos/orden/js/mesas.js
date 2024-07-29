document.addEventListener('DOMContentLoaded', () => {
    const openModalBtn = document.getElementById('openModalBtn');
    const mesaNumber = document.getElementById('mesaNumber'); // Asegúrate de tener este elemento en tu HTML

    window.data = localStorage.getItem('selectedTable');
    if (data) {
        const tableData = JSON.parse(data);
        console.log(`Dato: ${data}`);

        mesaNumber.innerHTML = `
            <div class="table-info">
                <div class="table-info-item">
                    <span class="table-info-label">Mesa:</span> <span class="table-info-value">${tableData.id_mesa}</span>
                </div>
                <div class="table-info-item">
                    <span class="table-info-label">Capacidad:</span> <span class="table-info-value">${tableData.capacidad}</span>
                </div>
            </div>`

        localStorage.removeItem('selectedTable');
    } else {
        console.log('No hay datos');
        mesaNumber.innerHTML = `
            <div class="table-info">
                <div class="table-info-item">
                    <span class="table-info-value">No hay datos</span>
                </div>
            </div>`
    }

    openModalBtn.addEventListener('click', () => {
        const currentUrl = window.location.href;
        localStorage.setItem('redirectUrl', currentUrl);

        window.location.href = 'http://localhost:8100/app/menu/roles/mesero/modulos/mesas/mesas.php';
    });
});
