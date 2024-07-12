import {fetchData} from "../../../config/api.js";

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
    const data = { dni, username, password, email, telefono, rol };

    try {
        const response = await fetchData('/user', {
            method: 'POST',
            body: data
        });

        if (typeof response === 'object' && response.status === 'success') {
            alert(response.message);
            window.location.href = 'http://localhost:8100/app/login/login.php';
        } else if (typeof response === 'string') {
            document.body.innerHTML = response;
        } else {
            alert('Error: ' + response.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al registrar usuario');
    }
}
