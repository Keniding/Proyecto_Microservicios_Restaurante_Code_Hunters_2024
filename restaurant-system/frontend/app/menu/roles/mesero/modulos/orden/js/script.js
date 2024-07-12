
import { apiBase } from 'config/config';

document.addEventListener('DOMContentLoaded', function() {
    let url = apiBase.apiBaseUrl;
    let endpoint = '/food/';
    const id = document.getElementById('foodId');
    const idOrden = document.getElementById('orderId');

    idOrden.value = `${generateUniqueId()}`;

    fetch(`${url}${endpoint}${id.value}`)
        .then(response => response.json())
        .then(data => {
            const precioElement = document.getElementById('unitPrice');
            const  cantidadElement = document.getElementById('quantity');
            const  totalElement = document.getElementById('totalPrice');
            if (precioElement) {
                precioElement.value = `${data.precio}`;
                cantidadElement.addEventListener('input', () => {
                    const cantidad = parseFloat(cantidadElement.value);
                    const precio = parseFloat(precioElement.value);
                    const total = cantidad * precio;
                    totalElement.value = total.toFixed(2);
                });
            } else {
                console.error("Elemento con ID 'precio' no encontrado.");
            }
        })
        .catch(error => {
            console.error('Error al obtener el precio:', error);
        });
});

function generateRandomId(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    const charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function generateUniqueId() {
    const timestamp = Date.now().toString(36);
    const randomString = generateRandomId(10);
    return `${timestamp}-${randomString}`;
}
