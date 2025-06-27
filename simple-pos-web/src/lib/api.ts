import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api/v1',
    headers: {
        Accept: 'application/vnd.api+json',
        'Content-Type': 'application/vnd.api+json',
        Authorization: 'Bearer 8b2e4f86d41f4f099aeec8a6f4937c4d3fb129d5a88e44c59c994dffb1d94b07', // <-- set your token
    },
});

export default api;
