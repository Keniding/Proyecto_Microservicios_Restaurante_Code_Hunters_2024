import {apiBase, authBase} from '/../../config/config.js';

document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const dni = document.getElementById('dni').value;
    const password = document.getElementById('password').value;

    login(dni, password);
});

function getdata(dni) {
    const url = `${apiBase.apiBaseUrl}/userForDni/${dni}`;
    fetch(url)
        .then(r => r.json())
        .then(data => {
            localStorage.setItem('user_id', data.id);
            localStorage.setItem('rol', data.rol_id);
            localStorage.setItem('name', data.username);

            pageforRol(data.rol_id)
        })
}

function login(dni, password) {
    const url = `${authBase.apiBaseUrl}/login`;
    const data = { dni: dni, password: password };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Inicio de sesión exitoso');
                getdata(dni);
                pageforRol();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al iniciar sesión');
        });
}

function pageforRol(id) {
    if (id === 1) {
        window.location.href = 'http://localhost:8100/app/menu/roles/administrador/menu.php';
    } else if (id === 9) {
        window.location.href = 'http://localhost:8100/app/menu/roles/mesero/menu.php';
    }
}
