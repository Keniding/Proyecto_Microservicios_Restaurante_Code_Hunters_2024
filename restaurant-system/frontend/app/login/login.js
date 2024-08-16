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

function pageforRol() {
    let id;
    let nombre;
    let url = `${apiBase.apiBaseUrl}/roles`;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            data.forEach(rol => {
                id = rol.id;
                nombre = rol.nombre;
                if (id === 1) {
                    window.location.href = 'http://localhost:8100/app/menu/menu.php';
                } else if (id === 9) {
                    window.location.href = 'http://localhost:8100/app/menu/roles/mesero/menu.php';
                }
            });
        })
        .catch(error => console.error('Error:', error));
}
