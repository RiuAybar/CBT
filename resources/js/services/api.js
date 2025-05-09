import axios from 'axios';
import router from '../router'; // 👈 Asegúrate que la ruta sea correcta según tu estructura

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  }
});

// Interceptor de errores
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.data?.message === 'Unauthenticated.') {
      // ⚠️ Borrar token
      localStorage.removeItem('token');

      // ❌ Quitar el header de auth
      delete api.defaults.headers.common['Authorization'];

      // 🔁 Redirigir al login
      router.push({ name: 'login' });
    }

    return Promise.reject(error);
  }
);

// Función para establecer token
export function setAuthToken(token) {
  if (token) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  } else {
    delete api.defaults.headers.common['Authorization'];
  }
}

export default api;
