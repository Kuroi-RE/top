import axios from 'axios';
window.axios = axios;

// Set API base URL dynamically based on current host
window.axios.defaults.baseURL = window.location.origin + '/api/v1';

// Set default headers
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';

// Enable credentials for CORS requests
window.axios.defaults.withCredentials = true;

// ── Token Interceptor ────────────────────────────────────────────────────────
// Inject token Sanctum dari localStorage ke setiap request
window.axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('topkema_api_token');
    if (token && !config.headers['Authorization']) {
      config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// ── Response Interceptor ─────────────────────────────────────────────────────
// Handle 401 Unauthorized: hapus token expired dari localStorage
window.axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      // Token tidak valid / expired — hapus dari localStorage
      const currentToken = localStorage.getItem('topkema_api_token');
      if (currentToken) {
        localStorage.removeItem('topkema_api_token');
        delete window.axios.defaults.headers.common['Authorization'];
        // Jangan redirect otomatis karena web session mungkin masih valid
      }
    }
    return Promise.reject(error);
  }
);
