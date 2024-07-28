import {apiBase} from 'config/config';

document.addEventListener('DOMContentLoaded', function () {
    const url = apiBase.apiBaseUrl;
    const endpoint = '/costumerForDni/';
    const endpointReniec = '/dniReniec/';
    const endpointPost = '/costumer';
    const verifyButton = document.getElementById('verifyButton');
    const id = document.getElementById('customerDni');

    const dataCostumerContainer = document.createElement('div');
    const dataCostumer = document.createElement('p');
    const result = document.getElementById('result');

    dataCostumerContainer.style.width = '100%';
    dataCostumerContainer.style.wordWrap = 'break-word';

    verifyButton.addEventListener('click', async function () {
        try {
            const customerData = await fetchCustomerData(url, endpoint, id.value);
            if (customerData) {
                displayCustomerData(customerData);
            } else {
                await handleCustomerNotFound(url, endpointReniec, endpointPost, id.value);
            }
        } catch (error) {
            console.log('Error en la solicitud:', error.message);
        }
    });

    async function fetchCustomerData(baseUrl, endpoint, customerId) {
        try {
            const response = await fetch(`${baseUrl}${endpoint}${customerId}`);
            if (!response.ok) {
                if (response.status === 500) {
                    return null;
                }
                return null;
            }
            return await response.json();
        } catch (error) {
            return null;
        }
    }

    function displayCustomerData(data) {
        dataCostumer.innerHTML = formatData(data);
        dataCostumerContainer.appendChild(dataCostumer);
        result.innerHTML = '';
        result.appendChild(dataCostumerContainer);
    }

    function formatData(data) {
        return Object.keys(data).map(key => `<strong>${key}:</strong> ${data[key]}<br>`).join('');
    }

    async function handleCustomerNotFound(baseUrl, endpointReniec, endpointPost, customerId) {
        clearResult();
        try {
            result.innerHTML = 'Cliente no encontrado, consultando Reniec...';

            const dataReniec = await fetchCustomerData(baseUrl, endpointReniec, customerId);
            if (dataReniec) {
                await saveNewCustomer(baseUrl, endpointPost, dataReniec);
            } else {
                result.innerHTML = 'No se encontraron datos en Reniec.';
            }
        } catch (error) {
            result.innerHTML = `Error al consultar Reniec: ${error.message}`;
        } finally {
            result.innerHTML = result.innerHTML.includes('consultando') ? 'Consulta completada.' : result.innerHTML;
        }
    }

    function clearResult() {
        dataCostumerContainer.innerHTML = '';
        dataCostumer.innerHTML = '';
        result.innerHTML = '';
    }

    async function saveNewCustomer(baseUrl, endpoint, dataReniec) {
        const newCustomerData = {
            dni: dataReniec.numeroDocumento || "",
            name: `${dataReniec.nombres || ""} ${dataReniec.apellidoPaterno || ""} ${dataReniec.apellidoMaterno || ""}`.trim(),
            email: "",
            telefono: "",
            direccion: ""
        };

        displayCustomerData(newCustomerData);

        try {
            const responsePost = await fetch(`${baseUrl}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(newCustomerData)
            });

            if (responsePost.ok) {
                const savedCustomerData = await responsePost.json();
                console.log('Nuevo cliente guardado exitosamente:', savedCustomerData);
            } else {
                const responseText = await responsePost.json();
                result.innerHTML = `Error al guardar el nuevo cliente: ${responseText.error}`;
            }
        } catch (error) {
            result.innerHTML = `Error en la solicitud: ${error.message}`;
        }
    }
});
