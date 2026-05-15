import axios from 'axios';
window.axios = axios;

// Set API base URL
window.axios.defaults.baseURL = 'http://localhost:8000/api/v1';

// Set default headers
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';

// Enable credentials for CORS requests
window.axios.defaults.withCredentials = true;
