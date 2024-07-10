import { fetchData } from '../../../config/api.js';

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const data = await fetchData('/roles');
        const rolSelect = document.getElementById('rol');
        data.forEach(rol => {
            const option = document.createElement('option');
            option.value = rol.id;
            option.textContent = rol.nombre;
            rolSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading roles:', error);
    }
});
