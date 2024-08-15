import { apiBase } from 'config/config';

document.addEventListener('DOMContentLoaded', async () => {
    let url = apiBase.apiBaseUrl;
    let endpoint = '/roles';

    try {
        fetch(`${url}${endpoint}`)
            .then(response => response.json())
            .then(data => {
                const rolSelect = document.getElementById('rol');
                data.forEach(rol => {
                    const option = document.createElement('option');
                    option.value = rol.id;
                    option.textContent = rol.nombre;
                    rolSelect.appendChild(option);
                });
            });
    } catch (error) {
        console.error('Error loading roles:', error);
    }
});
