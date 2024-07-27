import { apiBase } from 'config/config';

document.addEventListener('DOMContentLoaded', function () {
    const url = apiBase.apiBaseUrl;
    const endpoint = '';
    const orderButton = document.getElementById('AgregarOrden');
    const id = document.getElementById('facturaId');

    orderButton.addEventListener('click', function (event) {
        try {
            event.preventDefault();
            console.log(id.value)
        }catch (error) {
            console.error('Error al obtener los datos:', error);
        }
    });
});