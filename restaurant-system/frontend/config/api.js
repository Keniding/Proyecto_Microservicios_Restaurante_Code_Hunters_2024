import config from './config.js';

async function fetchData(endpoint) {
    try {
        const response = await fetch(`${config.apiBaseUrl}${endpoint}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
}

export { fetchData };
