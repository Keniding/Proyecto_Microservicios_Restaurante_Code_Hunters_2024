document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const dni = document.getElementById('dni').value;
    const password = document.getElementById('password').value;

    login(dni, password);
    get_rol();
});

function login(dni, password) {
    const url = 'http://localhost:8001/api/login';
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
                //console.log('Token:', data.token);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al iniciar sesión');
        });
}

function get_rol() {
    let id;
    let nombre;
    fetch('http://localhost:8090/roles')
        .then(response => response.json())
        .then(data => {
            data.forEach(rol => {
                id = rol.id;
                nombre = rol.nombre;
                if (id === 1) {
                    window.location.href = 'http://localhost:8003/app/menu/menu.php';
                } else if (id === 9) {
                    window.location.href = 'http://localhost:8003/app/menu/roles/mesero/menu.php';
                }
                //console.log(id, nombre);
            });
        })
        .catch(error => console.error('Error:', error));
}

/**

function menu(rol) {
    const url = 'http://localhost:8200/api/login';
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
                //console.log('Token:', data.token);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al iniciar sesión');
        });
}
*/