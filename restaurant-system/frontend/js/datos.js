import { apiBase } from 'config/config';

document.addEventListener('DOMContentLoaded', () => {
    const rol = localStorage.getItem('rol');
    let name = localStorage.getItem('name');
    let name_rol = '';

    let url = `${apiBase.apiBaseUrl}/rol/${rol}`;

    name = name.toUpperCase();

    const rol_page= document.getElementById('rol');
    const name_user= document.getElementById('name');
    name_user.innerHTML += `${name}`;

    try {
        fetch(`${url}`)
            .then(response => response.json())
            .then(data => {
                name_rol = data.nombre;
                rol_page.innerHTML += `${name_rol}`;
            });
    } catch (error) {
        console.error('Error loading roles:', error);
    }
});
