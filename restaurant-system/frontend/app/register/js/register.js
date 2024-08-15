import { apiBase } from 'config/config';

document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const dni = document.getElementById('dni').value;
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;
    const telefono = document.getElementById('telefono').value;
    const rol = document.getElementById('rol').value;

    registerUser(dni, username, password, email, telefono, rol);
});

async function registerUser(dni, username, password, email, telefono, rol) {

    let url = apiBase.apiBaseUrl;
    let endpoint = '/user';

    const data = {
        dni: dni,
        username: username,
        password: password,
        email: email,
        telefono: telefono,
        rol: rol
    };

    try {
        const response = await fetch(`${url}${endpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Error en la creación de la factura');
        }
        window.location.href = 'http://localhost:8100/app/login/login.php';


    } catch (error) {
        console.error('Error:', error);
        alert('Error al registrar usuario');
    }
}
