import {auth, config} from './config.js';

async function fetchFromApi(baseUrl, endpoint, options = {}) {
    const url = `${baseUrl}${endpoint}`;

    try {
        const response = await fetch(url, {
            ...options,
            body: options.body ? JSON.stringify(options.body) : undefined
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return await response.json();
        } else {
            return await response.text();
        }
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
}

export async function fetchData(endpoint, options = {}) {
    return fetchFromApi(config.apiBaseUrl, endpoint, options);
}

export async function fetchAuth(endpoint, options = {}) {
    return fetchFromApi(auth.apiBaseUrl, endpoint, options);
}