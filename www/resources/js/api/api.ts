import axios, { AxiosInstance } from 'axios';

const api: AxiosInstance = axios.create({
    baseURL: '/api/v1',
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
    withCredentials: true, // permite cookies se usar Sanctum futuramente
    withXSRFToken: true,
});

// interceptors para logging e tratamento global de erros
api.interceptors.response.use(
    (response) => response,
    (error) => {
        console.error('[API ERROR]', error.response || error.message);
        throw error;
    }
);

export default api;
