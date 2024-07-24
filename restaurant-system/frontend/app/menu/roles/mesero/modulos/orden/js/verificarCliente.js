import { apiBase } from 'config/config';

document.addEventListener('DOMContentLoaded', function () {
    const url = apiBase.apiBaseUrl;
    const endpoint = '/costumerForDni/';
    const endpointReniec = '/dniReniec/';
    const endpointPost = '/costumer';
    const verifyButton = document.getElementById('verifyButton');
    const id = document.getElementById('customerDni');

    verifyButton.addEventListener('click', async function () {
        try {
            let response = await fetch(`${url}${endpoint}${id.value}`);
            let data = await response.json();

            if (data && Object.keys(data).length > 0) {
                console.log('Cliente encontrado:', data);
            } else {
                console.log('Cliente no encontrado, consultando Reniec...');
                let responseReniec = await fetch(`${url}${endpointReniec}${id.value}`);
                let dataReniec = await responseReniec.json();

                if (dataReniec && Object.keys(dataReniec).length > 0) {
                    console.log('Datos de Reniec obtenidos:', dataReniec);

                    let newCustomerData = {
                        dni: dataReniec.numeroDocumento || "",
                        name: `${dataReniec.nombres || ""} ${dataReniec.apellidoPaterno || ""} ${dataReniec.apellidoMaterno || ""}`.trim(),
                        email: "",
                        telefono: "",
                        direccion: ""
                    };

                    console.log(newCustomerData);

                    let responsePost = await fetch(`${url}${endpointPost}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(newCustomerData)
                    });

                    let responseText = await responsePost.text();

                    if (responsePost.ok) {
                        console.log('Nuevo cliente guardado exitosamente:', responseText);
                    } else {
                        console.error('Error al guardar el nuevo cliente:', responseText);
                    }
                } else {
                    console.error('No se encontraron datos en Reniec.');
                }
            }
        } catch (error) {
            console.error('Error en la solicitud:', error);
        }
    });
});
