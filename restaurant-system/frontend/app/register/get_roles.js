document.addEventListener('DOMContentLoaded', function() {
    fetch('../../../backend/api/register/services/get_roles.php')
        .then(response => response.json())
        .then(data => {
            const rolSelect = document.getElementById('rol');
            data.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.id;
                option.textContent = rol.nombre;
                rolSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error al cargar roles:', error));
});