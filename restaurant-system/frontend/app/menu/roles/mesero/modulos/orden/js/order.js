import { apiBase } from 'config/config';

document.addEventListener('DOMContentLoaded', function () {
    const url = apiBase.apiBaseUrl;
    const endpointFactura = '/factura';
    const endpointDetalle = '/detalle';
    const endpointModificationOrder = '/modificationOrder';
    const orderButton = document.getElementById('AgregarOrden');
    const idFactura= document.getElementById('facturaId');
    const dni = document.getElementById('customerDni');
    const cantidad = document.getElementById('quantity');
    const total = document.getElementById('totalPrice');

    const result = document.getElementById('result');

    const botonMesa = document.getElementById('openModalBtn');

    const   idElement = document.getElementById('foodId');

    const list = window.selectedTags;

    orderButton.addEventListener('click', async function (event) {
        try {
            event.preventDefault();
            console.log('Selected Tags:', list);
            console.log(data)

            const fields = [idFactura, dni, total, cantidad];

            fields.forEach(field => field.classList.remove('highlight'));

            if (data === null) {
                result.innerHTML = 'Seleccione mesa';
                botonMesa.style.backgroundColor = 'red';
                return;
            }

            let hasEmptyFields = false;
            fields.forEach(field => {
                if (!field.value) {
                    field.classList.add('highlight');
                    hasEmptyFields = true;
                }
            });

            if (hasEmptyFields) {
                result.innerHTML = 'Rellene los campos requeridos';
                return;
            }

            async function createFacturaAndDetails() {
                try {
                    const dataFactura = {
                        id: idFactura.value,
                        total: total.value,
                        dni: dni.value
                    };

                    console.log(dataFactura);

                    const responseFactura = await fetch(`${url}${endpointFactura}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(dataFactura)
                    });

                    if (!responseFactura.ok) {
                        throw new Error('Error en la creación de la factura');
                    }

                    const dataDetalle = {
                        factura: idFactura.value,
                        comida: idElement.value,
                        cantidad: cantidad.value,
                        precio: total.value
                    };

                    console.log(dataDetalle);

                    const responseDetalle = await fetch(`${url}${endpointDetalle}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(dataDetalle)
                    });

                    if (!responseDetalle.ok) {
                        throw new Error('Error en la creación del detalle de la factura');
                    }

                    const detalleData = await responseDetalle.json();

                    const newDetalleId = detalleData.id;

                    console.log(detalleData)

                    for (let i = 0; i < list.length; i++) {
                        const dataModificationOrder = {
                            order: newDetalleId,
                            modification: list[i].id,
                        };

                        console.log(dataModificationOrder);

                        const responsePost = await fetch(`${url}${endpointModificationOrder}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(dataModificationOrder)
                        });

                        if (!responsePost.ok) {
                            throw new Error('Error en la creación de la modificación');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            createFacturaAndDetails();

        } catch (error) {
            console.error('Error al obtener los datos:', error);
        }
    });
});
