document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const dni = document.getElementById('dni').value;
    const password = document.getElementById('password').value;

    login(dni, password);
});

function login(dni, password) {
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


//Alert
document.getElementById('registerLink').addEventListener('click', function(event) {
    event.preventDefault(); // Evita que el enlace navegue a la página de registro
    showAlert();
});

function showAlert() {
    document.getElementById('customAlert').style.display = 'block';
}

function closeAlert() {
    document.getElementById('customAlert').style.display = 'none';
}